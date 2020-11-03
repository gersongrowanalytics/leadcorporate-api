<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasTres\SpaDos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\pappasosproductos;
use App\paupasosusuarios;
use App\dsudatossubpasos;
use App\cpacategoriaspasos;
use App\mecmecanicas;
use App\mcpmarcascategoriasproveedores;

class SpaLdpController extends Controller
{
    public function EditarSpaLdp(Request $request)
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

        $dsu = dsudatossubpasos::find($dsuid);

        if($dsu){
            $dsu->dsuestado   = true;
            $dsu->update();
        }

        $cpa = cpacategoriaspasos::find($cpaid);
        if($cpa){
            $cpa->cpaestado = true;
            $cpa->update();
        }

        
        $pape = pappasosproductos::find($pap['papid']);
        $pape->papnombre           = $pap['pronombre'];
        $pape->papean              = $pap['papean'];
        $pape->papprecioregular    = $pap['papprecioregular'];
        $pape->papmarca            = $pap['papmarca'];
        $pape->pappromocion        = $pap['pappromocion'];
        $pape->papdescripcion      = $pap['papdescripcion'];
        $pape->pappreciopromocion  = $pap['pappreciopromocion'];
        $pape->papfechainicio      = $pap['papfechainicio'];
        $pape->papfechafin         = $pap['papfechafin'];
        $pape->mecid               = $pap['mecid'];
        $pape->papestatus          = true;
        if($pape->update()){
            $respuesta = true;
        }else{
            $respuesta = false;
        }

        $mecs = mecmecanicas::get(['mecid', 'mecnombre']);
        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvid)
                                                ->where('mcpmarcascategoriasproveedores.catid', $catid)
                                                ->get([
                                                    'mcpid',
                                                    'mar.marid',
                                                    'marnombre'
                                                ]);

        $paps = pappasosproductos::leftjoin('mecmecanicas as mec', 'mec.mecid', 'pappasosproductos.mecid')
                                ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                ->where('pappasosproductos.dsuid', $dsuid)
                                ->where('pro.catid', $catid)
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

        $datos = $paps;

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle
        ]);

    }
}
