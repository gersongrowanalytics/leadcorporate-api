<?php

namespace App\Http\Controllers\sistema\Cpa\Crear;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\capcategoriasproveedores;
use App\catcategorias;
use App\cpacategoriaspasos;
use App\mcpmarcascategoriasproveedores;
use App\mpamarcaspasos;

class CpaCategoriasPasosController extends Controller
{
    public function CrearCategoriasPaso(Request $request)
    {

        $respuesta      = true;
        $mensaje        = 'Categorias asignadas correctamente';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $dsuid = $request['dsuid'];
        $prvid = $request['prvid'];

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
                                            'cpacategoriaspasos.capid',
                                            'cpacategoriaspasos.cpaestado',
                                            'cat.catnombre',
                                            'cat.catimagen'
                                        ]);

            foreach($cpas as $posicionCpas => $cpa){

                $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                    ->where('mcpmarcascategoriasproveedores.prvid', $prvid)
                                                    ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                    ->get([
                                                        'mcpmarcascategoriasproveedores.mcpid',
                                                        'mcpmarcascategoriasproveedores.mapid',
                                                        'mar.marid',
                                                        'mar.marnombre'
                                                    ]);

                foreach($mcps as $mcp){
                    $mpan = new mpamarcaspasos;
                    $mpan->mapid     = $mcp->mapid;
                    $mpan->prvid     = $prvid;
                    $mpan->marid     = $mcp->marid;
                    $mpan->dsuid     = $dsuid;

                    $mpan->cpaid     = $cpa->cpaid;
                    $mpan->capid     = $cpa->capid;
                    $mpan->catid     = $cpa->catid;
                    $mpan->mpaestado = false;
                    $mpan->save();
                }

                $mpas = mpamarcaspasos::join('marmarcas as mar', 'mar.marid', 'mpamarcaspasos.marid')
                                        ->where('mpamarcaspasos.prvid', $prvid)
                                        ->where('mpamarcaspasos.dsuid', $dsuid)
                                        ->where('mpamarcaspasos.catid', $cpa->catid)
                                        ->get([
                                            'mpamarcaspasos.mpaid',
                                            'mpamarcaspasos.mapid',
                                            'mpamarcaspasos.capid',
                                            'mar.marid',
                                            'mar.marnombre',
                                            'mpamarcaspasos.mpaestado'
                                        ]);

                foreach($mpas as $posicionMpa => $mpa){

                    $mpas[$posicionMpa]['dcps'] = [];
                }

                
                $cpas[$posicionCpas]['mpas'] = $mpas;

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
