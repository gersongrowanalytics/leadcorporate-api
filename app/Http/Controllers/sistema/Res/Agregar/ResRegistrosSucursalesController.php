<?php

namespace App\Http\Controllers\sistema\Res\Agregar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\resregistrossucursales;
use App\sucsucursales;
use App\paupasosusuarios;
use App\paspasos;
use App\usuusuarios;
use App\dsudatossubpasos;
use App\spasubpasos;
use App\prsproductossucursales;
use App\prvproveedores;
use App\pappasosproductos;
use App\tpctiposcomprobantes;

class ResRegistrosSucursalesController extends Controller
{
    public function AgregarRes(Request $request)
    {

        $respuesta      = false;
        $mensaje        = '';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $sucid    = $request['sucid'];
        $usutoken = $request->header('api_token');

        try{
            $usu = usuusuarios::where('usutoken', $usutoken)->first(['usuid']);

            $resn = new resregistrossucursales;
            $resn->sucid     = $sucid;
            $resn->usuid     = $usu->usuid;
            $resn->resestado = false;
            if($resn->save()){

                $suc = sucsucursales::find($sucid);
                $suc->sucestado = false;
                if($suc->update()){

                    $pass = paspasos::get(['pasid']);
                    if(sizeof($pass) > 0){
                        foreach($pass as $pas){
                            $paun = new paupasosusuarios;
                            $paun->pasid = $pas->pasid;
                            $paun->resid = $resn->resid;
                            $paun->usuid = $usu->usuid;
                            $paun->pauestado = false;
                            if($paun->save()){
                                $spas = spasubpasos::where('pasid', $pas->pasid)->get(['spaid']);

                                foreach($spas as $spa){
                                    $dsun = new dsudatossubpasos;
                                    $dsun->pauid                    = $paun->pauid;
                                    $dsun->resid                    = $resn->resid;
                                    $dsun->spaid                    = $spa->spaid;
                                    $dsun->pasid                    = $pas->pasid;
                                    $dsun->dsuordenado              = null;
                                    $dsun->dsulimpio                = null;
                                    $dsun->dsuplanogramacategoria   = null;
                                    $dsun->dsuexisteprogramacion    = null;
                                    $dsun->dsullegomercaderia       = null;
                                    $dsun->dsumercaderiatiempo      = null;
                                    $dsun->desupedidocompleto       = null;
                                    $dsun->dsucheckout              = null;
                                    $dsun->dsuestado                = false;
                                    $dsun->save();
                                    
                                }

                                

                            }else{

                            }
                        }

                        $paus = paupasosusuarios::where('resid', $resn->resid)
                                            ->get(['pauid', 'pasid', 'pauestado']);

                        foreach($paus as $posicionpau => $pau){

                            $dsus = dsudatossubpasos::where('pauid', $pau->pauid)
                                                    ->get([
                                                        'dsuid', 
                                                        'spaid', 
                                                        'dsuestado',

                                                        'dsuordenado',
                                                        'dsulimpio',
                                                        'dsuplanogramacategoria',
                                                        'dsuexisteprogramacion',
                                                        'dsullegomercaderia',
                                                        'dsumercaderiatiempo',
                                                        'desupedidocompleto',
                                                        'dsucheckout'
                                                    ]);

                            foreach($dsus as $posicionDsu => $dsu){
                                if($dsu->spaid == 1 || $dsu->spaid == 2 || $dsu->spaid == 3 ){
                                    $dsus[$posicionDsu]['fotos'] = [];
                                }else if($dsu->spaid == 4){
                                    $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                    ->where('sucid', $sucid)
                                                                    ->get([
                                                                        'prsproductossucursales.prsid',
                                                                        'pro.proid',
                                                                        'pro.pronombre',
                                                                    ]);

                                    foreach($prss as $posicionPrss => $prs){
                                        $prss[$posicionPrss]['papid'] = 0;
                                        $prss[$posicionPrss]['papcantidad'] = 0;
                                        $prss[$posicionPrss]['paprecepcion'] = 0;
                                    }

                                    $dsus[$posicionDsu]['paps'] = $prss;

                                }else if($dsu->spaid == 5){
                                    $prvsprincipales  = prvproveedores::where('prvprincipal', 1)->get(['prvid', 'prvnombre']);
                                    $prvscompetencias = prvproveedores::where('prvprincipal', 0)->get(['prvid', 'prvnombre']);

                                    foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                        $prvsprincipales[$contPrvPrincipal]['cpas'] = [];
                                    }
    
                                    foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                        $prvscompetencias[$contPrvCompetencia]['cpas'] = [];
                                    }

                                    $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                    $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;
                                }else if($dsu->spaid == 6){
                                    $paps = pappasosproductos::join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->get([
                                                            'pappasosproductos.papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'pro.prosku',
                                                            'pappasosproductos.papcantidad',
                                                            'pappasosproductos.papquiebre'
                                                        ]);
                                    if(sizeof($paps) > 0){
                                        $dsus[$posicionDsu]['paps'] = $paps;
                                    }else{
                                        $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                        ->where('sucid', $suc->sucid)
                                                                        ->where('prsestado', 1)
                                                                        ->get([
                                                                            'prsproductossucursales.prsid',
                                                                            'pro.proid',
                                                                            'pro.pronombre',
                                                                            'pro.prosku',
                                                                            'prsproductossucursales.prsstock'
                                                                        ]);

                                        foreach($prss as $posicionPrss => $prs){
                                            $prss[$posicionPrss]['papid'] = 0;
                                            $prss[$posicionPrss]['papcantidad'] = 0;
                                            $prss[$posicionPrss]['papquiebre'] = false;
                                        }

                                        $dsus[$posicionDsu]['paps'] = $prss;
                                    }
                                }else if($dsu->spaid == 7){
                                    $prvsprincipales  = prvproveedores::where('prvprincipal', 1)->get(['prvid', 'prvnombre']);
                                    $prvscompetencias = prvproveedores::where('prvprincipal', 0)->get(['prvid', 'prvnombre']);

                                    foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                        $prvsprincipales[$contPrvPrincipal]['cpas'] = [];
                                    }
    
                                    foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                        $prvscompetencias[$contPrvCompetencia]['cpas'] = [];
                                    }

                                    $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                    $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;

                                }else if($dsu->spaid == 8){
                                    $prvsprincipales  = prvproveedores::where('prvprincipal', 1)->get(['prvid', 'prvnombre']);
                                    $prvscompetencias = prvproveedores::where('prvprincipal', 0)->get(['prvid', 'prvnombre']);

                                    foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                        $prvsprincipales[$contPrvPrincipal]['cpas'] = [];
                                    }
    
                                    foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                        $prvscompetencias[$contPrvCompetencia]['cpas'] = [];
                                    }

                                    $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                    $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;
                                }else if($dsu->spaid == 9){
                                    $dsus[$posicionDsu]['fotos'] = [];

                                    $prvsprincipalesSpa9  = prvproveedores::where('prvprincipal', 1) ->get(['prvid', 'prvnombre']);

                                    $prvscompetenciasSpa9 = prvproveedores::where('prvprincipal', 0)->get(['prvid', 'prvnombre']);

                                    foreach($prvsprincipalesSpa9 as $contadorPrvPrincipal => $prvsprincipal){
                                        $prvsprincipalesSpa9[$contadorPrvPrincipal]['mpps'] = [];
                                    }

                                    foreach($prvscompetenciasSpa9 as $contadorPrvCompetencia => $prvCompetencia){
                                        $prvscompetenciasSpa9[$contadorPrvCompetencia]['mpps'] = [];
                                    }

                                    $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipalesSpa9;
                                    $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetenciasSpa9;

                                }else if($dsu->spaid == 10){
                                    $tpcs = tpctiposcomprobantes::where('tpcnombre', '!=', 'Otros')->get(['tpcid', 'tpcnombre']);

                                    foreach($tpcs as $posicionTpc => $tpc){
                                        $tpcs[$posicionTpc]['cops'] = [];
                                    }
                                    $dsus[$posicionDsu]['tpcs']     = $tpcs;

                                    $tpcsOtros = tpctiposcomprobantes::where('tpcnombre', 'Otros')->first(['tpcid', 'tpcnombre']);

                                    if($tpcsOtros){
                                        $tpcsOtros->cops = [];
                                    }

                                    $dsus[$posicionDsu]['tpcsOtros'] = $tpcsOtros;
                                }
                            }

                            $paus[$posicionpau]['dsus'] = $dsus;
                        }

                    }else{

                    }
                }else{

                }
                
                $respuesta = true;
                $datos = $paus;


            }else{
                $respuesta = false;
            }

        } catch (Exception $e) {
            $mensajedev = $e->getMessage();
            $respuesta = false;
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
