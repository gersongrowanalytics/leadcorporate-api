<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasTres\SpaUno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pappasosproductos;
use App\paupasosusuarios;
use App\dsudatossubpasos;

class SpaValidarStockController extends Controller
{
    public function EditarSpaValidarStock(Request $request)
    {   

        $respuesta      = true;
        $mensaje        = 'Tu información ha sido actualizada';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;


        $dsuid      = $request['dsuid'];
        $productos  = $request['paps'];
        $pauid      = $request['pauid'];

        try{

            $pau = paupasosusuarios::find($pauid);

            if($pau){
                $pau->pauestado = true;
                $pau->update();
            }

            $dsu = dsudatossubpasos::find($dsuid);

            if($dsu){
                $dsu->dsuestado   = true;
                $dsu->update();
            }


            for($cont = 0; $cont < sizeof($productos); $cont++){

                $pap = pappasosproductos::find($productos[$cont]['papid']);
                if($pap){
                    if($productos[$cont]['papcantidad'] == null){
                        $pap->papcantidad   = 0;
                    }else{
                        $pap->papcantidad   = $productos[$cont]['papcantidad'];
                    }
                    
                    if($productos[$cont]['papquiebre'] == null){
                        $pap->papquiebre  = false;
                    }else{
                        $pap->papquiebre = $productos[$cont]['papquiebre'];
                    }
                    $pap->update();

                }else{
                    $papn = new pappasosproductos;
                    $papn->dsuid         = $dsuid;
                    $papn->prsid         = $productos[$cont]['prsid'];
                    $papn->proid         = $productos[$cont]['proid'];
                    if($productos[$cont]['papcantidad'] == null){
                        $papn->papcantidad   = 0;
                    }else{
                        $papn->papcantidad   = $productos[$cont]['papcantidad'];
                    }
                    
                    if($productos[$cont]['papquiebre'] == null){
                        $papn->papquiebre  = false;
                    }else{
                        $papn->papquiebre = $productos[$cont]['papquiebre'];
                    }
                    $papn->save();
                }
            }

            $datos = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->where('pappasosproductos.dsuid', $dsuid)
                                                        ->get([
                                                            'pappasosproductos.papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'pro.prosku',
                                                            'pappasosproductos.papcantidad',
                                                            'pappasosproductos.papquiebre',
                                                            'prs.prsstock'
                                                        ]);

        } catch (Exception $e) {
            $mensajedev = $e->getMessage();
            $respuesta = false;
            $mensaje   = 'Lo sentimos ocurrio un error al momento de guardar tu información';
        }

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle,
            'mensajedev'     => $mensajedev
        ]);

    }
}
