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
                    ]
                );

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
        // return json_encode($request->all());
        $configuracion = $this->getConfiguracion();
        Log::info("Configuracion obtenida: " . json_encode($configuracion));
        if (!$configuracion) {
            return response()->json(['error' => 'Configuración no encontrada'], 404);
        }
        $urlqr = $configuracion->urlqr;
        $apikeyServicio = $configuracion->apikeyServicio;
        $callback = $configuracion->callback;
        $glosa = $request->input("glosa");
        $monto = $request->input("monto");
        $alias = $request->input("alias");
        $eltoken = "";
        $eltoken = $this->obtienetokenbisa();
        Log::info("Token obtenido: " . $eltoken);
        if ($eltoken == '' || $eltoken == null) {
            return response()->json(['error' => 'Token de Bisa no disponible'], 404);
        } else {
            // return $eltoken;
            $hoy = date("Y-m-d");
            $vencimiento = date('d/m/Y', strtotime('+1 day', strtotime($hoy)));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlqr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"alias\": \"$alias\",\"callback\": \"$callback\",\"detalleGlosa\": \"$glosa\",\"monto\": $monto,\"moneda\": \"BOB\",\"fechaVencimiento\": \"$vencimiento\",\"tipoSolicitud\": \"API\",\"unicoUso\": \"true\"}");


            $headers = array();
            $headers[] = 'Apikeyservicio: ' . $apikeyServicio;
            $headers[] = 'Authorization: Bearer ' . $eltoken;
            $headers[] = 'Content-Type: application/json';
            // return json_encode($headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);

            $dataqr = json_decode($response);

            Log::info("Respuesta QR: " . json_encode($dataqr));
            return response()->json(["imagenqr" => $dataqr->objeto->imagenQr, "idQr" => $dataqr->objeto->idQr, "alias"=>$alias]);
        }
    }
    public function obtienetokenbisa()
    {
        $config = $this->getConfiguracion();
        Log::info("Configuracion obtenida para obtienetokenbisa(): " . json_encode($config));
        $username = $config->username;
        $password = $config->password;
        $apikey = $config->apikey;
        $urltoken = $config->urltoken;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urltoken,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "password":"'.$password.'",
                "username":"'.$username.'"
            }',
                CURLOPT_HTTPHEADER => array(
                    'apikey: '.$apikey.'',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            if((!$response) || ($response == '')){
                Log::error("Error al obtener token de Bisa: Respuesta vacía");
                return '';
            }
            $data = json_decode($response);
            Log::info("Respuesta de obtienetoken bisa " . json_encode($data));

            $token = $data->objeto->token;
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
