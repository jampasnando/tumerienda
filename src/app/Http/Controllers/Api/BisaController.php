<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cobrosqr;
use App\Models\Detalleventa;
use App\Models\Inventario;
use App\Models\Qrgenerado;
use Illuminate\Support\Facades\Validator;
use App\Models\Token;
use App\Models\Vendedore;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
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
        $usuario='ADAMCZYK';
        $password='Tumerienda123';
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
            $nuevo=Cobrosqr::create([
            "alias"=>$request->alias,
            "numeroOrdenOriginante"=>$request->numeroOrdenOriginante,
            "monto"=>$request->monto,
            "idQr"=>$request->idQr,
            "moneda"=>$request->moneda,
            "fechaproceso"=>$request->fechaproceso,
            "cuentaCliente"=>$request->cuentaCliente,
            "nombreCliente"=>$request->nombreCliente,
            "documentoCliente"=>$request->documentoCliente,
            "fechareg"=>date("Y-m-d H:i:s")
            ]);
            $data=["codigo"=>"0000","mensaje"=>"Registro exitoso"];
        }
        else{
            $data=["codigo"=>"9999","mensaje"=>"No se pudo procesar"];
        }
        return response(json_encode($data),200)->header('Content-Type','application/json');
    }

    public function elprecioventa($id,$prodsinv){
        foreach ($prodsinv as $key => $unprodinv) {
            if($unprodinv["id"]==$id){
                return $unprodinv["precioventa"];
            }
        }
    }
    public function calculamontoqr($carrito,$prodsinv){
        $sumaxaqr=0;
        foreach ($carrito as $key => $unprodcarrito) {
            $precioventareal=$this->elprecioventa($unprodcarrito["id"],$prodsinv);
            $sumaxaqr+=$precioventareal * $unprodcarrito["cuantos"];
        }
        return $sumaxaqr;
    }

    public function obtieneqr(Request $request)
    {
        // return $request->input();
        // $arregloids = array_map(fn($unprod)=>$unprod["id"], $request->input());
        // $prodsinv = Inventario::whereIn('id', $arregloids)->get()->toArray();
        // $montoqr = $this->calculamontoqr($request->input(),$prodsinv);
        // $montoqr=array_reduce($request->input(),fn($suma,$unprod)=>$this->calculamontoqr($suma,$unprod,$prods),0);

        // return json_encode(["prods" => $prodsinv,"sumaparaqr"=>$montoqr]);
        $montoqr=0;
        $eltoken = "";
        $eltoken = $this->obtienetokenbisa();
        if ($eltoken == '') {
            return json_encode("eltoken vacio");
        } else {
            // return $eltoken;

            $hoy = date("Y-m-d");
            $alias = "qr" . date("dmYHis");
            $glosa = "EL OFERTON";
            // $monto = "25.0";
            $monto = $montoqr;
            $vencimiento = date('d/m/Y', strtotime('+1 day', strtotime($hoy)));

            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://sip.mc4.com.bo:8443/api/v1/generaQr');
            curl_setopt($ch, CURLOPT_URL, 'https://dev-sip.mc4.com.bo:8443/api/v1/generaQr');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"alias\": \"$alias\",\"callback\": \"https://ferreteriaoferton.com/api/confirmaPago\",\"detalleGlosa\": \"$glosa\",\"monto\": $monto,\"moneda\": \"BOB\",\"fechaVencimiento\": \"$vencimiento\",\"tipoSolicitud\": \"API\",\"unicoUso\": \"true\"}");
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"alias\": \"$alias\",\"callback\": \"https://tumerienda.sistembo.online/api/confirmaPago\",\"detalleGlosa\": \"$glosa\",\"monto\": $monto,\"moneda\": \"BOB\",\"fechaVencimiento\": \"$vencimiento\",\"tipoSolicitud\": \"API\",\"unicoUso\": \"true\"}");


            $headers = array();
            // $headers[] = 'Apikeyservicio: 4acaaf89843185d6df4de5f4b5202716a0ef65b06849df17';
            $headers[] = 'Apikeyservicio: 47094eb70f907e8fb6ba0bb9f6eb7fd205f9cad94924df9f';
            $headers[] = 'Authorization: Bearer ' . $eltoken;
            $headers[] = 'Content-Type: application/json';
            // return json_encode($headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);

            $dataqr = json_decode($response);
            $guardaqrgenerado = ["codigo" => $dataqr->codigo, "mensaje" => $dataqr->mensaje, "imagenQr" => $dataqr->objeto->imagenQr, "idQr" => $dataqr->objeto->idQr, "fechaVencimiento" => $dataqr->objeto->fechaVencimiento, "bancoDestino" => $dataqr->objeto->bancoDestino, "cuentaDestino" => $dataqr->objeto->cuentaDestino, "idTransaccion" => $dataqr->objeto->idTransaccion, "esImagen" => $dataqr->objeto->esImagen, "fechareg" => date("Y-m-d H:i:s"),"alias"=>$alias];
            // Qrgenerado::create($guardaqrgenerado);
            return json_encode(["imagenqr" => $dataqr->objeto->imagenQr, "idQr" => $dataqr->objeto->idQr, "prods" => $prodsinv,"alias"=>$alias]);
        }
    }
    public function obtienetokenbisa()
    {
        $ahora=date("Y-m-d H:i:s");
        $haytokenvigente = DB::select("select token,TIMESTAMPDIFF(minute, '".$ahora."', fechaexpira) as diferencia from tokens having diferencia>=10 limit 1");
        $res = count($haytokenvigente);
        if ($res > 0) {
            return $haytokenvigente[0]->token;
        } else {
            // return json_encode("No hay");
            $curl = curl_init();

            curl_setopt_array($curl, array(
                // CURLOPT_URL => 'https://sip.mc4.com.bo:8443/autenticacion/v1/generarToken',
                CURLOPT_URL => 'https://dev-sip.mc4.com.bo:8443/autenticacion/v1/generarToken',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                // "password":"mamier@XXLprod9",
                // "username":"EMISORMIER"
                "password":"Tumerienda123",
                "username":"ADAMCZYK"
            }',
                CURLOPT_HTTPHEADER => array(
                    // 'apikey: d4008dcf38d3aae9864b6efad53cb97ca35a59af1afe2fe8',
                    'apikey: 3f7cb0dac2e393b52d1a82bcec2ad0fd7116bcabd12b0d20',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $data = json_decode($response);
            $token = $data->objeto->token;
            $hoy = date("Y-m-d H:i:s");
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($hoy)));
            $guardaratoken = ["token" => $token, "fechareg" => $hoy, "fechaexpira" => $expira];
            // return json_encode($data->objeto->token);
            return $token;
        }
    }

    public function verificapagoqr(Request $request)
    {
        $idqr = $request->idqr;
        // $idqr = 0;
        $existepago = Cobrosqr::where("idQr", $idqr)->get();
        if (count($existepago) > 0) {
            $carrito = $request->carrito;
            $vendedor = $request->vendedor;
            $deposito = $request->deposito;
            $enc_caja = Vendedore::where("rol", "Enc. Tienda y caja")->where("ciudad", $deposito)->get();

            $idventa = uniqid();
            $hoy = date("dmY_His");
            $hoyxareg = date("Y-m-d H:i:s");
            $regventa = $this->registraventaqr($deposito, $carrito, $existepago, $enc_caja, $idventa, $hoyxareg, $vendedor);
            $linkgarantia = $this->pdfgarantia($carrito, $existepago, $enc_caja, $idventa, $hoy);

            return json_encode(["idQr" => $idqr, "carritorecibidoencontroller" => $carrito, "vendedor" => $vendedor, "EncCaja" => $enc_caja, "existepago" => $existepago, "garantia" => $linkgarantia]);
        }

        return json_encode($existepago);
    }

    public function registraventaqr($deposito, $carrito, $existepago, $enc_caja, $idventa, $hoyxareg, $vendedor)
    {
        if ($vendedor == null || $vendedor["id"] == null || $vendedor["id"] == 0) {
            $elquevende = $enc_caja[0]["id"];
        } else {
            $elquevende = $vendedor["id"];
        }
        $venta = ["idneg" => $deposito, "idventa" => $idventa, "total" => $existepago[0]["monto"], "cliente" => $existepago[0]["nombreCliente"], "telefono" => "", "nit" => $existepago[0]["documentoCliente"], "formapago" => "QR", "fecha" => $hoyxareg, "comentario" => "VENTA QR TIENDAONLINE", "vendedor" => $elquevende, "idusr" => $enc_caja[0]["id"], "idcliente" => "0", "pago" => $existepago[0]["monto"], "saldo" => "0", "pagomixto" => null];
        Venta::create($venta);
        foreach ($carrito as $key => $unprod) {
            $detalle = ["idventa" => $idventa, "idprod" => $unprod["idprod"], "preciolocal" => $unprod["preciolocal"], "precioventa" => $unprod["precioventa"], "preciofinal" => $unprod["preciofinal"], "cuantos" => $unprod["cuantos"], "descripcion" => $unprod["descripcion"], "vendedor" => $elquevende, "comision" => $unprod["comision"], "pagocomision" => null, "observaciones" => null, "cierre" => null];
            Detalleventa::insert($detalle);
            $updateprod = Inventario::findOrFail($unprod["id"]);
            $updateprod->cantidad = $updateprod->cantidad - $unprod["cuantos"];
            //$updateprod->save();
        }
    }

    public function pdfgarantia($carrito, $existepago, $enc_caja, $idventa, $hoy)
    {
        $pdforiginal = public_path("garantia2.pdf");
        $nuevagarantia = public_path("garantias/" . $hoy . ".pdf");
        $nombrearch = $hoy . ".pdf";
        $comprador = substr($existepago[0]["nombreCliente"], 0, 27);
        $telfcomprador = "";
        $vendedor = $enc_caja[0]["nombre"];
        $codcompra = $idventa;
        $fechacompra = date("d-m-Y");

        $pdf = new Fpdi();
        // $pdf = new \setasign\Fpdi\Fpdi();
        $pdf->AddPage();
        $pdf->SetFont("Helvetica");
        $pdf->setSourceFile($pdforiginal);
        $abrepagina1 = $pdf->importPage(1);
        $pdf->useTemplate($abrepagina1);

        $pdf->SetXY(75, 48);
        $pdf->Write(10, $vendedor);
        $pdf->SetXY(57, 57);
        $pdf->Write(10, $idventa);
        $pdf->SetXY(135, 57);
        $pdf->Write(10, $fechacompra);
        $pdf->SetXY(70, 65);
        $pdf->Write(10, $comprador);
        $pdf->SetXY(150, 65);
        $pdf->Write(10, $telfcomprador);

        $pdf->AddPage();
        $abrepagina2 = $pdf->importPage(2);
        $pdf->useTemplate($abrepagina2);
        $pdf->SetXY(0, 0);
        $pdf->Write(10, '');

        $pdf->SetFillColor(255, 215, 0);
        $pdf->SetFontSize(10);
        $pdf->SetXY(30, 50);
        $pdf->Cell(10, 5, "#", 1, 0, 'C', true, '');
        $pdf->SetXY(40, 50);
        $pdf->Cell(25, 5, "COD", 1, 0, 'C', true, '');
        $pdf->SetXY(65, 50);
        $pdf->Cell(125, 5, "Producto", 1, 0, 'C', true, '');

        $nroitems = count($carrito);
        $posy = 55;
        for ($k = 0; $k < $nroitems; $k++) {
            $pdf->SetXY(30, $posy);
            $pdf->Cell(10, 5, $carrito[$k]["cuantos"], 1, 0, 'C', false, '');
            $pdf->SetXY(40, $posy);
            $pdf->Cell(25, 5, $carrito[$k]["idprod"], 1, 0, 'C', false, '');
            $pdf->SetXY(65, $posy);
            $pdf->Cell(125, 5, $carrito[$k]["descripcion"], 1, 0, 'L', false, '');

            $posy += 5;
        }

        $pdf->SetXY(30, $posy);
        $pdf->Write(10, "Gracias por su compra.");
        $pdf->SetXY(30, $posy + 10);
        $pdf->Write(10, "Para consultas comuniquese con el Whatsapp 77939732");
        // $pdf->Text(100,50,$comprador);
        $pdf->Output($nuevagarantia, 'F');
        return $nombrearch;
    }

    public function saludo(Request $request){
        return response(json_encode("misaludo desde api"));
    }

    public function veestadoqr(Request $request){
        $eltoken = "";
        $eltoken = $this->obtienetokenbisa();
        if ($eltoken == '') {
            return json_encode("eltoken vacio");
        } else {
            $alias=$request->alias;
            // $alias = "qr24102024170718";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://sip.mc4.com.bo:8443/api/v1/estadoTransaccion');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"alias\": \"$alias\"}");

            $headers = array();
            $headers[] = 'Apikeyservicio: 4acaaf89843185d6df4de5f4b5202716a0ef65b06849df17';
            $headers[] = 'Authorization: Bearer ' . $eltoken;
            $headers[] = 'Content-Type: application/json';
            // return json_encode($headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);

            $dataqr = json_decode($response);
            $estadoActual=$dataqr->objeto->estadoActual;
            if($estadoActual=="PAGADO"){
                $nuevo=Cobrosqr::create([
                    "alias"=>$dataqr->objeto->alias,
                    "numeroOrdenOriginante"=>$dataqr->objeto->numeroOrdenOriginante,
                    "monto"=>$dataqr->objeto->monto,
                    "idQr"=>$dataqr->objeto->idQr,
                    "moneda"=>$dataqr->objeto->moneda,
                    "fechaproceso"=>$dataqr->objeto->fechaProcesamiento,
                    "cuentaCliente"=>$dataqr->objeto->cuentaCliente,
                    "nombreCliente"=>$dataqr->objeto->nombreCliente,
                    "documentoCliente"=>$dataqr->objeto->documentoCliente,
                    "fechareg"=>date("Y-m-d H:i:s")
                    ]);
                    return json_encode("PAGADO");
            }
            // $guardaqrgenerado = ["codigo" => $dataqr->codigo, "mensaje" => $dataqr->mensaje, "imagenQr" => $dataqr->objeto->imagenQr, "idQr" => $dataqr->objeto->idQr, "fechaVencimiento" => $dataqr->objeto->fechaVencimiento, "bancoDestino" => $dataqr->objeto->bancoDestino, "cuentaDestino" => $dataqr->objeto->cuentaDestino, "idTransaccion" => $dataqr->objeto->idTransaccion, "esImagen" => $dataqr->objeto->esImagen, "fechareg" => date("Y-m-d H:i:s"),"alias"=>$alias];
            // Qrgenerado::create($guardaqrgenerado);
            // return json_encode(["imagenqr" => $dataqr->objeto->imagenQr, "idQr" => $dataqr->objeto->idQr, "prods" => $prodsinv,"alias"=>$alias]);
            return json_encode("NOPAGADO");
        }
    }
}
