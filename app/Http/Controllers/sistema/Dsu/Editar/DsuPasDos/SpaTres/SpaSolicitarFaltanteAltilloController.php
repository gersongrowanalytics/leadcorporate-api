<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasDos\SpaTres;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pappasosproductos;
use App\paupasosusuarios;
use App\dsudatossubpasos;

class SpaSolicitarFaltanteAltilloController extends Controller
{
    public function EditarSpaSolicitarFaltanteAltillo(Request $request)
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
                $papid = 0;
                if(isset($productos[$cont]['papid'])){
                    $papid = 0;
                }else{
                    $papid = $productos[$cont]['papid'];
                }

                $pap = pappasosproductos::find($productos[$cont]['papid']);
                if($pap){
                    if($productos[$cont]['papcantidad'] == null){
                        $pap->papcantidad   = 0;
                    }else{
                        $pap->papcantidad   = $productos[$cont]['papcantidad'];
                    }
                    
                    if($productos[$cont]['paprecepcion'] == null){
                        $pap->paprecepcion  = false;
                    }else{
                        $pap->paprecepcion = $productos[$cont]['paprecepcion'];
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
                    
                    if($productos[$cont]['paprecepcion'] == null){
                        $papn->paprecepcion  = false;
                    }else{
                        $papn->paprecepcion = $productos[$cont]['paprecepcion'];
                    }
                    $papn->save();
                }
            }

            $datos = pappasosproductos::join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                        ->where('pappasosproductos.dsuid', $dsuid)
                                        ->get([
                                            'pappasosproductos.papid',
                                            'pro.pronombre',
                                            'pappasosproductos.papcantidad',
                                            'pappasosproductos.paprecepcion'
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
