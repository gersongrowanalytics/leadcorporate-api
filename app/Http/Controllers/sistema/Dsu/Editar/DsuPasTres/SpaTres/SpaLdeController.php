<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasTres\SpaTres;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\paupasosusuarios;
use App\dsudatossubpasos;
use App\cpacategoriaspasos;
use App\pappasosproductos;
use App\fppfotosproductospasos;
use App\tpetiposexhibicion;
use App\arrarrendados;
use App\mcpmarcascategoriasproveedores;

class SpaLdeController extends Controller
{
    public function EditarSpaLde(Request $request)
    {

        $respuesta      = true;
        $mensaje        = "Tu información ha sido actualizada";
        $datos          = [];
        $mensajeDetalle = ""; 

        $pap   = $request['pap'];
        $dsuid = $request['dsuid'];
        $prvid = $request['prvid'];
        $catid = $request['catid'];
        $pauid = $request['pauid'];
        $cpaid = $request['cpaid'];
        
        $pau = paupasosusuarios::find($pauid);

        if($pau){
            $pau->pauestado = true;
            $pau->update();
        }

        $cpa = cpacategoriaspasos::find($cpaid);
        if($cpa){
            $cpa->cpaestado = true;
            $cpa->update();
        }

        $pape = pappasosproductos::find($pap['papid']);
        $pape->papnombre           = $pap['pronombre'];
        $pape->tpeid               = $pap['tpeid'];
        $pape->papmarca            = $pap['papmarca'];
        $pape->papdescripcion      = $pap['papdescripcion'];
        $pape->papestatus          = $pap['papestatus'];
        $pape->papfechainicio      = $pap['papfechainicio'];
        $pape->papfechafin         = $pap['papfechafin'];
        $pape->arrid               = $pap['arrid'];
        $pape->papestado           = true;
        if($pape->update()){
            $dsuid = $pape->dsuid;

            $dsu = dsudatossubpasos::find($dsuid);

            if($dsu){
                $dsu->dsuestado   = true;
                $dsu->update();
            }


            $respuesta = true;
            $fpps = $pap['fpps'];
            foreach($fpps as $fpp){
                if($fpp['fppid'] == 0){

                    $imagen 	= base64_decode($fpp['fppimagen']);
                    $fichero	= "/sistema/img/RegistroInformacion/Lde/";
                    $nombre 	= Str::random(10).'.png';
                    file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);

                    
                    $fppn = new fppfotosproductospasos;
                    $fppn->papid = $pap['papid'];
                    $fppn->dsuid = $dsuid;
                    $fppn->prsid = $pape['prsid'];
                    $fppn->proid = $pap['proid'];
                    $fppn->fppimagen = env('APP_URL').$fichero.$nombre;
                    $fppn->save();
                    

                }
            }

        }else{
            $respuesta = false;
        }

        $tpes = tpetiposexhibicion::get(['tpeid', 'tpenombre']);
        $arrs = arrarrendados::get(['arrid', 'arrnombre']);
        
        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvid)
                                                ->where('mcpmarcascategoriasproveedores.catid', $catid)
                                                ->get([
                                                    'mcpid',
                                                    'mar.marid',
                                                    'marnombre'
                                                ]);

        $paps = pappasosproductos::leftjoin('tpetiposexhibicion as tpe', 'tpe.tpeid', 'pappasosproductos.tpeid')
                                ->leftjoin('arrarrendados as arr', 'arr.arrid', 'pappasosproductos.arrid')
                                ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                ->where('pappasosproductos.dsuid', $dsuid)
                                ->where('pro.catid', $catid)
                                ->where('prs.prvid', $prvid)
                                ->get([
                                    'papid',
                                    'pro.proid',
                                    'pro.pronombre',
                                    'tpe.tpeid',
                                    'tpe.tpenombre',
                                    'papmarca',
                                    'papdescripcion',
                                    'papestatus',
                                    'papfechainicio',
                                    'papfechafin',
                                    'arr.arrid',
                                    'arr.arrnombre',
                                    'papestado'
                                ]);

        foreach($paps as $posicionPap => $pap){
            $paps[$posicionPap]['mcps'] = $mcps;
            $paps[$posicionPap]['tpes'] = $tpes;
            $paps[$posicionPap]['arrs'] = $arrs;


            $fpps = fppfotosproductospasos::where('papid', $pap->papid)
                                        ->get([
                                            'fppid',
                                            'fppimagen'
                                        ]);

            $paps[$posicionPap]['fpps'] = $fpps;
        }

        $datos = $paps;

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle
        ]);
    }
}
