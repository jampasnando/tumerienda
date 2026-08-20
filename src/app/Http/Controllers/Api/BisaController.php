<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BeneficiarioPlan;
use App\Models\Cobrosqr;
use App\Models\Detalleventa;
use App\Models\Inventario;
use App\Models\Qrgenerado;
use App\Models\Token;
use App\Models\Vendedore;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi;

class BisaController extends Controller
{
    //
    public function confirma(Request $request){
        // $usuario='bisaqr';
        // $password='Di0sEs@mor';
        // $usuario='qruserXXLprod1';
        // $password='Mamier@dmin2024';
        // $usuario='XXLqruser';
        // $password='Mamier@2024admin';
        $usuario='bisaqr';
        $password='Tumeriend@123';
        $header = $request->header('Authorization');
        $partes= explode(':',base64_decode(explode(' ',$header)[1]));
        if($partes[0]==$usuario && $partes[1]==$password){
            $validator=Validator::make($request->all(),[
                "alias"=>"required",
                "numeroOrdenOriginante"=>"required",
                "monto"=>"required",
                "idQr"=>"required",
                "moneda"=>"required",
                "fechaproceso"=>"required",
                "cuentaCliente"=>"required",
                "nombreCliente"=>"required",
                "documentoCliente"=>"required",
            ]);
            if($validator->fails()){
                $data=["codigo"=>"9999","mensaje"=>"No se pudo procesar"];
                return response(json_encode($data),200)->header('Content-Type','application/json');
            }
            // $nuevo=Cobrosqr::create([
            // "alias"=>$request->alias,
            // "numeroOrdenOriginante"=>$request->numeroOrdenOriginante,
            // "monto"=>$request->monto,
            // "idQr"=>$request->idQr,
            // "moneda"=>$request->moneda,
            // "fechaproceso"=>$request->fechaproceso,
            // "cuentaCliente"=>$request->cuentaCliente,
            // "nombreCliente"=>$request->nombreCliente,
            // "documentoCliente"=>$request->documentoCliente,
            // "fechareg"=>date("Y-m-d H:i:s")
            // ]);
            $nuevo=Cobrosqr::firstOrCreate(
                ['alias' => $request->alias],
                [
                    'numeroOrdenOriginante' => $request->numeroOrdenOriginante,
                    'monto' => $request->monto,
                    'idQr' => $request->idQr,
                    'moneda' => $request->moneda,
                    'fechaproceso' => $request->fechaproceso,
                    'cuentaCliente' => $request->cuentaCliente,
                    'nombreCliente' => $request->nombreCliente,
                    'documentoCliente' => $request->documentoCliente,
                    'fechareg' => now(),
                ]
            );
            if (preg_match('/^Susc(\d+)_Benef(\d+)_/', $request->alias, $matches)) {

                $planId = $matches[1];
                $beneficiarioId = $matches[2];
                BeneficiarioPlan::firstOrCreate(
                    [
                        'alias' => $request->alias,
                    ],
                    [
                        'beneficiario_id' => $beneficiarioId,
                        'plan_id' => $planId,
                        'alias' => $request->alias,
                        'detalle' => json_encode($request->all()),
                        'estado' => true,
                        'nrorecibidos' => 0,
                    ]
                );
                try {
                    $beneficiario = \App\Models\Beneficiario::with('tutorActivo.tutor','plan')
                        ->where('id', $beneficiarioId)
                        ->firstOrFail();
                    $plan = $beneficiario->beneficiarioPlans()
                        ->where('plan_id', $planId)
                        ->firstOrFail()
                        ->plan
                        ->nombre;
                    // $nombreTutor = $beneficiario->tutorActivo?->tutor?->nombre;
                    $correoTutor = $beneficiario->tutorActivo?->tutor?->email;
                    Mail::raw(
                            'Gracias por su Suscripción al plan: '.$plan.
                            ' para '.$beneficiario->nombre.
                            '. En su aplicación puede ahora elegir las fechas y meriendas a ser entregadas.'.
                            'Registro: '.$request->alias,
                            function ($message) use ($beneficiario, $correoTutor) {
                                $message->to($correoTutor)
                                        ->subject('Suscripción recibida para '.$beneficiario->nombre);
                            }
                        );

                        return response()->json([
                            'ok' => true,
                            'mensaje' => 'Correo enviado correctamente.'
                        ]);

                    } catch (\Exception $e) {

                        return response()->json([
                            'ok' => false,
                            'error' => $e->getMessage()
                        ],500);

                    }

            }

            $data=["codigo"=>"0000","mensaje"=>"Registro exitoso"];
        }
        else{
            $data=["codigo"=>"9999","mensaje"=>"No se pudo procesar"];
        }
        return response(json_encode($data),200)->header('Content-Type','application/json');
    }

    public function obtieneqr(Request $request)
    {
        // Obtener configuración
        $configuracion = $this->getConfiguracion();

        Log::info("Configuracion obtenida: " . json_encode($configuracion));

        if (!$configuracion) {
            return response()->json([
                'error' => 'Configuración no encontrada'
            ], 404);
        }

        $urlqr = $configuracion->urlqr;
        $apikeyServicio = $configuracion->apikeyServicio;
        $callback = $configuracion->callback;

        $glosa = $request->input("glosa");
        $monto = $request->input("monto");
        $alias = $request->input("alias");

        /*
        * ============================================================
        * OBTENER TOKEN DE BISA
        * ============================================================
        *
        * Intentamos hasta 3 veces.
        */
        $eltoken = null;

        for ($intento = 1; $intento <= 3; $intento++) {

            Log::info("Intentando obtener token de Bisa. Intento: {$intento}");

            $eltoken = $this->obtienetokenbisa();

            Log::info(
                "Resultado intento {$intento}: " .
                (!empty($eltoken) ? 'TOKEN OBTENIDO' : 'TOKEN VACÍO')
            );

            if (!empty($eltoken)) {
                break;
            }

            // Esperar 500 milisegundos antes del siguiente intento
            if ($intento < 3) {
                usleep(500000);
            }
        }

        /*
        * Si después de los 3 intentos no tenemos token,
        * devolvemos error al cliente.
        */
        if (empty($eltoken)) {

            Log::error(
                "No se pudo obtener el token de Bisa después de 3 intentos"
            );

            return response()->json([
                'error' => 'Token de Bisa no disponible'
            ], 503);
        }

        Log::info("Token de Bisa obtenido correctamente");

        /*
        * ============================================================
        * FECHA DE VENCIMIENTO
        * ============================================================
        */
        $hoy = date("Y-m-d");

        $vencimiento = date(
            'd/m/Y',
            strtotime('+1 day', strtotime($hoy))
        );

        /*
        * ============================================================
        * PREPARAR DATOS DEL QR
        * ============================================================
        */
        $datosQr = [
            'alias' => $alias,
            'callback' => $callback,
            'detalleGlosa' => $glosa,
            'monto' => $monto,
            'moneda' => 'BOB',
            'fechaVencimiento' => $vencimiento,
            'tipoSolicitud' => 'API',
            'unicoUso' => true,
        ];

        $jsonQr = json_encode($datosQr);

        /*
        * ============================================================
        * SOLICITAR QR A BISA
        * ============================================================
        */
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $urlqr,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,

            // Tiempo máximo para establecer conexión
            CURLOPT_CONNECTTIMEOUT => 10,

            // Tiempo máximo total de la petición
            CURLOPT_TIMEOUT => 30,

            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonQr,

            CURLOPT_HTTPHEADER => [
                'Apikeyservicio: ' . $apikeyServicio,
                'Authorization: Bearer ' . $eltoken,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($ch);

        /*
        * ============================================================
        * CONTROL DE ERRORES CURL
        * ============================================================
        */
        if ($response === false) {

            $curlError = curl_error($ch);
            $curlErrno = curl_errno($ch);

            Log::error("Error cURL al solicitar QR de Bisa", [
                'errno' => $curlErrno,
                'error' => $curlError,
            ]);

            curl_close($ch);

            return response()->json([
                'error' => 'Error de comunicación con el servicio de Bisa',
                'detalle' => $curlError,
            ], 503);
        }

        /*
        * Obtener código HTTP antes de cerrar cURL
        */
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        Log::info("Código HTTP respuesta QR Bisa: {$httpCode}");
        Log::info("Respuesta cruda QR Bisa: " . $response);

        /*
        * ============================================================
        * VALIDAR RESPUESTA
        * ============================================================
        */
        if (empty($response)) {

            Log::error(
                "Bisa devolvió una respuesta vacía al solicitar el QR"
            );

            return response()->json([
                'error' => 'Bisa devolvió una respuesta vacía'
            ], 503);
        }

        /*
        * ============================================================
        * DECODIFICAR JSON
        * ============================================================
        */
        $dataqr = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {

            Log::error("Respuesta de QR no es JSON válido", [
                'respuesta' => $response,
                'error_json' => json_last_error_msg(),
            ]);

            return response()->json([
                'error' => 'Respuesta inválida del servicio de Bisa'
            ], 502);
        }

        Log::info("Respuesta QR decodificada: " . json_encode($dataqr));

        /*
        * ============================================================
        * VALIDAR ESTRUCTURA DE RESPUESTA
        * ============================================================
        */
        if (
            !isset($dataqr->objeto) ||
            !isset($dataqr->objeto->imagenQr) ||
            !isset($dataqr->objeto->idQr)
        ) {

            Log::error("Respuesta de Bisa no contiene los datos esperados", [
                'respuesta' => $dataqr,
            ]);

            return response()->json([
                'error' => 'Bisa no devolvió los datos esperados del QR',
                'respuesta' => $dataqr,
            ], 502);
        }

        /*
        * ============================================================
        * DEVOLVER QR
        * ============================================================
        */
        return response()->json([
            'imagenqr' => $dataqr->objeto->imagenQr,
            'idQr' => $dataqr->objeto->idQr,
            'alias' => $alias,
        ]);
    }


    public function obtienetokenbisa()
    {
        /*
        * ============================================================
        * OBTENER CONFIGURACIÓN
        * ============================================================
        */
        $config = $this->getConfiguracion();

        Log::info(
            "Configuracion obtenida para obtienetokenbisa(): " .
            json_encode($config)
        );

        if (!$config) {

            Log::error(
                "No se encontró configuración para obtener token de Bisa"
            );

            return '';
        }

        $username = $config->username;
        $password = $config->password;
        $apikey = $config->apikey;
        $urltoken = $config->urltoken;

        /*
        * ============================================================
        * PREPARAR REQUEST
        * ============================================================
        */
        $datosLogin = [
            'password' => $password,
            'username' => $username,
        ];

        $jsonLogin = json_encode($datosLogin);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $urltoken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,

            // Tiempo máximo para establecer conexión
            CURLOPT_CONNECTTIMEOUT => 10,

            // Tiempo máximo total
            CURLOPT_TIMEOUT => 30,

            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

            CURLOPT_CUSTOMREQUEST => 'POST',

            CURLOPT_POSTFIELDS => $jsonLogin,

            CURLOPT_HTTPHEADER => [
                'apikey: ' . $apikey,
                'Content-Type: application/json',
            ],
        ]);

        /*
        * ============================================================
        * EJECUTAR REQUEST
        * ============================================================
        */
        $response = curl_exec($curl);

        /*
        * ============================================================
        * CONTROL DE ERROR CURL
        * ============================================================
        */
        if ($response === false) {

            $curlError = curl_error($curl);
            $curlErrno = curl_errno($curl);

            Log::error("Error cURL al obtener token de Bisa", [
                'errno' => $curlErrno,
                'error' => $curlError,
            ]);

            curl_close($curl);

            return '';
        }

        /*
        * Obtener código HTTP
        */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        /*
        * ============================================================
        * REGISTRAR RESPUESTA
        * ============================================================
        */
        Log::info(
            "Código HTTP respuesta token Bisa: {$httpCode}"
        );

        Log::info(
            "Respuesta cruda de obtienetoken Bisa: " . $response
        );

        /*
        * ============================================================
        * VALIDAR RESPUESTA VACÍA
        * ============================================================
        */
        if (empty($response)) {

            Log::error(
                "Error al obtener token de Bisa: Respuesta vacía"
            );

            return '';
        }

        /*
        * ============================================================
        * DECODIFICAR JSON
        * ============================================================
        */
        $data = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {

            Log::error("Respuesta de token de Bisa no es JSON válido", [
                'respuesta' => $response,
                'error_json' => json_last_error_msg(),
            ]);

            return '';
        }

        Log::info(
            "Respuesta decodificada de obtienetoken Bisa: " .
            json_encode($data)
        );

        /*
        * ============================================================
        * VALIDAR TOKEN
        * ============================================================
        */
        if (!isset($data->objeto) || !isset($data->objeto->token)) {

            Log::error(
                "Bisa no devolvió el token esperado",
                [
                    'respuesta' => $data,
                    'http_code' => $httpCode,
                ]
            );

            return '';
        }

        $token = $data->objeto->token;

        Log::info("Token de Bisa obtenido correctamente");

        return $token;
    }

    public function verificapagoqr(Request $request)
    {
        // $idqr = $request->idqr;
        // // $idqr = 0;
        // $existepago = Cobrosqr::where("idQr", $idqr)->get();
        // if (count($existepago) > 0) {
        //     $carrito = $request->carrito;
        //     $vendedor = $request->vendedor;
        //     $deposito = $request->deposito;
        //     $enc_caja = Vendedore::where("rol", "Enc. Tienda y caja")->where("ciudad", $deposito)->get();

        //     $idventa = uniqid();
        //     $hoy = date("dmY_His");
        //     $hoyxareg = date("Y-m-d H:i:s");
        //     $regventa = $this->registraventaqr($deposito, $carrito, $existepago, $enc_caja, $idventa, $hoyxareg, $vendedor);
        //     $linkgarantia = $this->pdfgarantia($carrito, $existepago, $enc_caja, $idventa, $hoy);

        //     return json_encode(["idQr" => $idqr, "carritorecibidoencontroller" => $carrito, "vendedor" => $vendedor, "EncCaja" => $enc_caja, "existepago" => $existepago, "garantia" => $linkgarantia]);
        // }
        $recibido = $request->all();
        Log::info("Datos recibidos: " . json_encode($recibido));
        DB::table('beneficiario_plan')->update(['detalle' => $recibido]);
        $respuesta = ["codigo" => "0000", "mensaje" => "Registro Exitoso"];
        return response()->json($respuesta);
    }
    public function saludo(Request $request){
        return response()->json("misaludo desde api");
    }

    public function veestadoqr(Request $request){
        $config = $this->getConfiguracion();
        Log::info("Configuracion obtenida para veestadoqr: " . json_encode($config));
        $eltoken = "";
        $eltoken = $this->obtienetokenbisa();
        if ($eltoken == '') {
            return response()->json(['error' => 'Token de Bisa no disponible'], 404);
        } else {
            $alias=$request->input("alias");
            Log::info("Alias: ".$alias);
            // $alias = "qr24102024170718";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config->urlestadoqr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"alias\": \"$alias\"}");

            $headers = array();
            $headers[] = 'Apikeyservicio: ' . $config->apikeyServicio;
            $headers[] = 'Authorization: Bearer ' . $eltoken;
            $headers[] = 'Content-Type: application/json';
            // return json_encode($headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);

            $dataqr = json_decode($response);
            Log::info("Respuesta estado QR: " . json_encode($dataqr));
            $estadoActual=$dataqr->objeto->estadoActual;
            if($estadoActual=="PAGADO"){
                    return response()->json($dataqr);
            }

            Log::info("QR no pagado: " . json_encode($dataqr));
            return response()->json("NOPAGADO");
        }
    }
    public function configuracion(){
        $configuracion = $this->getConfiguracion();
        return response()->json($configuracion);
    }

    private function getConfiguracion()
    {
        $configuracion = DB::select("select * from configuracion where estado = 1 limit 1");
        return $configuracion[0] ?? null;
    }
}
