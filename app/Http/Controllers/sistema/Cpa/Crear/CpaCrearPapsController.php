<?php

namespace App\Http\Controllers\sistema\Cpa\Crear;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\prsproductossucursales;
use App\capcategoriasproveedores;
use App\catcategorias;
use App\cpacategoriaspasos;
use App\mcpmarcascategoriasproveedores;
use App\mecmecanicas;
use App\pappasosproductos;

class CpaCrearPapsController extends Controller
{
    public function CrearCategoriasPasoParaPaps(Request $request)
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

            $mecs = mecmecanicas::get(['mecid', 'mecnombre']);

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

                foreach($prss as $prs){
                    $papn = new pappasosproductos;
                    $papn->dsuid                = $dsuid;
                    $papn->prsid                = $prs->prsid;
                    $papn->proid                = $prs->proid;
                    $papn->papnombre            = $prs->pronombre;
                    $papn->papean               = "";
                    $papn->papprecioregular     = "";
                    $papn->papmarca             = "";
                    $papn->pappromocion         = true;
                    $papn->papdescripcion       = "";
                    $papn->pappreciopromocion   = "";
                    $papn->papfechainicio       = "";
                    $papn->papfechafin          = "";
                    $papn->papestatus           = false;
                    $papn->save();
                }

                $paps = pappasosproductos::leftjoin('mecmecanicas as mec', 'mec.mecid', 'pappasosproductos.mecid')
                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                        ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                        ->where('pappasosproductos.dsuid', $dsuid)
                                        ->where('pro.catid', $cpa->catid)
                                        ->where('prs.prvid', $prvid)
                                        ->get([
                                            'papid',
                                            'pro.proid',
                                            'pro.pronombre',
                                            'papean',
                                            'papprecioregular',
                                            'papmarca',
                                            'pappromocion',
                                            'papdescripcion',
                                            'pappreciopromocion',
                                            'papfechainicio',
                                            'papfechafin',
                                            'pappasosproductos.mecid',
                                            'mec.mecnombre',
                                            'papestatus'
                                        ]);

                foreach($paps as $posicionPap => $pap){
                    $paps[$posicionPap]['mcps'] = $mcps;
                    $paps[$posicionPap]['mecs'] = $mecs;
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
