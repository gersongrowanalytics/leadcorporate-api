<?php

namespace App\Http\Controllers\sistema\Cpa\Crear;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\tpetiposexhibicion;
use App\arrarrendados;
use App\capcategoriasproveedores;
use App\cpacategoriaspasos;
use App\catcategorias;
use App\mcpmarcascategoriasproveedores;
use App\prsproductossucursales;
use App\pappasosproductos;
use App\fppfotosproductospasos;

class CpaCrearPapsParaLdeController extends Controller
{
    public function CrearCpaParaPapsDeLde(Request $request)
    {

        $respuesta      = true;
        $mensaje        = 'Categorias asignadas correctamente';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $dsuid = $request['dsuid'];
        $prvid = $request['prvid'];
        $sucid = $request['sucid'];

        $logs = [];

        try{

            $caps = capcategoriasproveedores::where('prvid', $prvid)
                                        ->get([
                                            'capid',
                                            'prvid',
                                            'catid'
                                        ]);
        
            if(sizeof($caps) == 0){
                $logs[] = "Igual a 0";
                $cats = catcategorias::get(['catid', 'catnombre']);

                foreach($cats as $posicionCat => $cat){
                    $capn = new capcategoriasproveedores;
                    $capn->prvid = $prvid;
                    $capn->catid = $cat->catid;
                    $capn->save();
                }

                $caps = capcategoriasproveedores::where('prvid', $prvid)
                                            ->get([
                                                'capid',
                                                'prvid',
                                                'catid'
                                            ]);

                foreach($caps as $cap){
                    $cpan = new cpacategoriaspasos;
                    $cpan->dsuid     = $dsuid;
                    $cpan->capid     = $cap->capid;
                    $cpan->prvid     = $prvid;
                    $cpan->catid     = $cap->catid;
                    $cpan->cpaestado = false;
                    $cpan->save();
                }

            }else{
                $logs[] = "Mayor a 0";
                $cats = catcategorias::get(['catid', 'catnombre']);

                foreach($cats as $posicionCat => $cat){
                    foreach($caps as $posicionCap => $cap){
                        if($cat->catid == $cap->catid){
                            break;
                        }else if($posicionCap+1 == sizeof($caps)){
                            $capn = new capcategoriasproveedores;
                            $capn->prvid = $prvid;
                            $capn->catid = $cat->catid;
                            $capn->save();
                            break;
                        }
                    }
                }
                

                $caps = capcategoriasproveedores::where('prvid', $prvid)
                                            ->get([
                                                'capid',
                                                'prvid',
                                                'catid'
                                            ]);

                foreach($caps as $cap){
                    $cpan = new cpacategoriaspasos;
                    $cpan->dsuid     = $dsuid;
                    $cpan->capid     = $cap->capid;
                    $cpan->prvid     = $prvid;
                    $cpan->catid     = $cap->catid;
                    $cpan->cpaestado = false;
                    $cpan->save();
                }
            }

            $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                        ->where('cpacategoriaspasos.prvid', $prvid)
                                        ->where('cpacategoriaspasos.dsuid', $dsuid)
                                        ->get([
                                            'cpacategoriaspasos.cpaid',
                                            'cpacategoriaspasos.catid',
                                            'cpacategoriaspasos.cpaestado',
                                            'cat.catnombre',
                                            'cat.catimagen'
                                        ]);
            
            $tpes = tpetiposexhibicion::get(['tpeid', 'tpenombre']);
            $arrs = arrarrendados::get(['arrid', 'arrnombre']);

            foreach($cpas as $posicionCpas => $cpa){
                $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                        ->where('mcpmarcascategoriasproveedores.prvid', $prvid )
                                                        ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                        ->get([
                                                            'mcpid',
                                                            'mar.marid',
                                                            'marnombre'
                                                        ]);

                $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                ->where('pro.catid', $cpa->catid)
                                                ->where('prvid', $prvid)
                                                ->get([
                                                    'prsproductossucursales.prsid',
                                                    'pro.proid',
                                                    'pro.pronombre',
                                                ]);

                foreach($prss as $posicionPrss => $prs){

                    $papn = new pappasosproductos;
                    $papn->dsuid          = $dsuid;
                    $papn->prsid          = $prs->prsid;
                    $papn->proid          = $prs->proid;
                    $papn->papnombre      = $prs->pronombre;
                    $papn->papmarca       = "";
                    $papn->papdescripcion = "";
                    $papn->papestatus     = true;
                    $papn->papfechainicio = "";
                    $papn->papfechafin    = "";
                    $papn->papestado      = false;
                    $papn->save();
                }

                $paps = pappasosproductos::leftjoin('tpetiposexhibicion as tpe', 'tpe.tpeid', 'pappasosproductos.tpeid')
                                ->leftjoin('arrarrendados as arr', 'arr.arrid', 'pappasosproductos.arrid')
                                ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                ->where('prs.prvid', $prvid)
                                ->where('pappasosproductos.dsuid', $dsuid)
                                ->where('pro.catid', $cpa->catid)
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

                $cpas[$posicionCpas]['paps'] = $paps;
            }


            $datos = $cpas;


        } catch (Exception $e) {
            $mensajedev = $e->getMessage();
            $respuesta = false;
        }
        

        return response()->json([
            'logs'           => $logs,
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle,
            'mensajedev'     => $mensajedev
        ]);

    }
}
