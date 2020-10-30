<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasDos\SpaCuatro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\dcpdatoscategoriaspasos;
use App\fcpfotoscategoriaspasos;
use App\cpacategoriaspasos;
use App\mpamarcaspasos;
use App\dsudatossubpasos;
use App\paupasosusuarios;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SpaMedidaGondolaController extends Controller
{
    public function EditarSpaMedidaGondola(Request $request)
    {
        $mpaid  = $request['mpaid'];
        $dcps   = $request['dcps'];
        $pauid  = $request['pauid'];
        $dsuid  = $request['dsuid'];

        $respuesta  = true;
		$mensaje    = "Tu información ha sido actualizada";
        $mensajedev = null;
        $mensajeDetalle = "";
        
        DB::beginTransaction(); 
		try {
            
            foreach($dcps as $dcp){
                if($dcp['dcpid'] == 0){
                    $dcpn = new dcpdatoscategoriaspasos;
                    $dcpn->cpaid      = $dcps[0]['cpaid'];
                    $dcpn->capid      = $dcps[0]['capid'];
                    $dcpn->catid      = $dcps[0]['catid'];
                    $dcpn->mpaid      = $dcps[0]['mpaid'];
                    $dcpn->dcpancho   = $dcp['dcpancho'];
                    $dcpn->dsuid      = $dcps[0]['dsuid'];
                    $dcpn->dcpalto    = $dcp['dcpalto'];
                    $dcpn->dcpfrentes = $dcp['dcpfrentes'];
                    $dcpn->mapid      = $dcps[0]['mapid'];
                    $dcpn->marid      = $dcps[0]['marid'];
                    $dcpn->dcpestado  = true;
                    if($dcpn->save()){
                        $fcps = $dcp['fcps'];
    
                        foreach($fcps as $fcp){
                            $imagen 	= base64_decode($fcp['fcpimagen']);
                            $fichero	= "/sistema/img/OrdenGondola/MedidaGondola/";
                            $nombre 	= Str::random(10).'.png';
                            file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);
    
                            $fcpn = new fcpfotoscategoriaspasos;
                            $fcpn->dcpid     = $dcpn['dcpid'];
                            $fcpn->fcpimagen = env('APP_URL').$fichero.$nombre;
                            $fcpn->save();
                        }
    
                    }
    
                }else{
                    if($dcp['dcpestado'] == false){
                        $dcpe = dcpdatoscategoriaspasos::find($dcp['dcpid']);
                        $dcpe->dcpestado  = true;
                        $dcpe->dcpalto    = $dcp['dcpalto'];
                        $dcpe->dcpfrentes = $dcp['dcpfrentes'];
                        $dcpe->dcpancho   = $dcp['dcpancho'];
                        if($dcpe->update()){
    
                            $fcps = $dcp['fcps'];
    
                            foreach($fcps as $fcp){
                                if(strpos($fcp['fcpimagen'], "http") == false){
                                    $imagen 	= base64_decode($fcp['fcpimagen']);
                                    $fichero	= "/sistema/img/OrdenGondola/MedidaGondola/";
                                    $nombre 	= Str::random(10).'.png';
                                    file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);
            
                                    $fcpn = new fcpfotoscategoriaspasos;
                                    $fcpn->dcpid     = $dcpe['dcpid'];
                                    $fcpn->fcpimagen = env('APP_URL').$fichero.$nombre;
                                    $fcpn->save();
                                }
                            }
                        }
                    }
                }
            }

            $pau = paupasosusuarios::find($pauid);
            $pau->pauestado = true;
            $pau->update();

            $dsu = dsudatossubpasos::find($dsuid);
            $dsu->dsuestado = true;
            $dsu->update();


            $cpa = cpacategoriaspasos::find($dcps[0]['cpaid']);
            $cpa->cpaestado = true;
            $cpa->update();

            $mpa = mpamarcaspasos::find($mpaid);
            $mpa->mpaestado = true;
            $mpa->update();

            $dcps = dcpdatoscategoriaspasos::where('mpaid', $mpaid)
                                            ->get();

            foreach($dcps as $posicionDcp => $dcp){
                $fcps = fcpfotoscategoriaspasos::where('dcpid', $dcp->dcpid)
                                                ->get([
                                                    'fcpid',
                                                    'dcpid',
                                                    'fcpimagen'
                                                ]);

                $dcps[$posicionDcp]['fcps'] = $fcps;
            }

            $datos = $dcps;
            DB::commit();
        } catch (Exception $e) {

			DB::rollBack();
			$mensajedev = $e->getMessage();
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
