<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasNueve\SpaDefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\paupasosusuarios;
use App\dsudatossubpasos;
use App\copcomprobantespasos;
use App\caccantidadescomprobantes;

class EditarEntregaMercaderiaController extends Controller
{
    public function EditarEntregaMercaderia(Request $request)
    {
        $dsu = $request['dsu'];
        $pauid = $request['pauid'];

        $pau = paupasosusuarios::find($pauid);

        if($pau){
            $pau->pauestado = true;
            $pau->update();
        }

        $dsue = dsudatossubpasos::find($dsu['dsuid']);

        if($dsue){
            $dsue->dsuestado             = true;
            $dsue->dsuexisteprogramacion = $dsu['dsuexisteprogramacion'];
            $dsue->dsullegomercaderia    = $dsu['dsullegomercaderia'];
            $dsue->dsumercaderiatiempo   = $dsu['dsumercaderiatiempo'];
            $dsue->desupedidocompleto    = $dsu['desupedidocompleto'];
            if($dsue->update()){

                $tpcs = $dsu['tpcs'];
                foreach($tpcs as $tpc){
                    $cops = $tpc['cops'];

                    foreach($cops as $cop){
                        if($cop['copid'] == 0){
                            $copn = new copcomprobantespasos;
                            $copn->tpcid     = $tpc['tpcid'];
                            $copn->dsuid     = $dsue['dsuid'];
                            $copn->pasid     = $dsue['pasid'];
                            $copn->copnumero = $cop['copnumero'];
                            $copn->save();
                        }else{
                            $cope = copcomprobantespasos::find($cop['copid']);
                            $cope->copnumero = $cop['copnumero'];
                            $cope->update();
                        }
                    }
                }


                $tpcsOtros = $dsu['tpcsOtros'];
                $copsOtros = $tpcsOtros['cops'];

                foreach($copsOtros as $copOtros){
                    if($copOtros['copid'] == 0){
                        $copn = new copcomprobantespasos;
                        $copn->tpcid     = $tpcsOtros['tpcid'];
                        $copn->dsuid     = $dsue['dsuid'];
                        $copn->pasid     = $dsue['pasid'];
                        $copn->copnumero = $copOtros['copnumero'];
                        if($copn->save()){
                            $cacs = $copOtros['cacs'];
                            foreach($cacs as $cac){
                                $cacn = new caccantidadescomprobantes;
                                $cacn->copid        = $copn->copid;
                                $cacn->caccodigoean = $cac['caccodigoean'];
                                $cacn->caccantidad  = $cac['caccantidad'];
                                $cacn->save();
                            }
                        }
                    }else{
                        $cope = copcomprobantespasos::find($copOtros['copid']);
                        $cope->copnumero = $copOtros['copnumero'];
                        if($cope->update()){
                            $cacs = $copOtros['cacs'];
                            foreach($cacs as $cac){
                                if($cac['cacid'] == 0){
                                    $cacn = new caccantidadescomprobantes;
                                    $cacn->copid        = $cope->copid;
                                    $cacn->caccodigoean = $cac['caccodigoean'];
                                    $cacn->caccantidad  = $cac['caccantidad'];
                                    $cacn->save();
                                }else{
                                    $cace = caccantidadescomprobantes::find($cac['cacid']);
                                    $cace->caccodigoean = $cac['caccodigoean'];
                                    $cace->caccantidad  = $cac['caccantidad'];
                                    $cace->update();
                                }
                            }
                        }
                    }
                }




            }
        }

        return response()->json([
            'respuesta'      => true,
            'mensaje'        => "Tu información ha sido actualizada",
            'datos'          => [],
        ]);
    }
}
