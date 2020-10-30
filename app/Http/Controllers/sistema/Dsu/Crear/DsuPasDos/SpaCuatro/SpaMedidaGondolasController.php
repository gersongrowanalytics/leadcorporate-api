<?php

namespace App\Http\Controllers\sistema\Dsu\Crear\DsuPasDos\SpaCuatro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\dcpdatoscategoriaspasos;

class SpaMedidaGondolasController extends Controller
{
    public function CrearDatosCategoriasPasos(Request $request)
    {
        $respuesta      = false;
        $mensaje        = 'Se creo registro de medidas';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $dsuid = $request['dsuid'];
        $cpaid = $request['cpaid'];
        $mpaid = $request['mpaid'];
        $catid = $request['catid'];
        $marid = $request['marid'];
        $mapid = $request['mapid'];
        $capid = $request['capid'];

        $dcpn = new dcpdatoscategoriaspasos;
        $dcpn->cpaid                = $cpaid;
        $dcpn->capid                = $capid;
        $dcpn->catid                = $catid;
        $dcpn->mpaid                = $mpaid;
        $dcpn->dcpancho             = "0";
        $dcpn->dsuid                = $dsuid;
        $dcpn->dcpalto              = "0";
        $dcpn->dcpfrentes           = "0";
        $dcpn->mapid                = $mapid;
        $dcpn->marid                = $marid;
        $dcpn->dcpproveedor         = null;
        $dcpn->dcpnombre            = null;
        $dcpn->dcpean               = null;
        $dcpn->dcpprecioregular     = null;
        $dcpn->dcpmarca             = null;
        $dcpn->dcppromocion         = null;
        $dcpn->dcppreciopromocion   = null;
        $dcpn->dcpfechainicio       = null;
        $dcpn->dcpfechafin          = null;
        $dcpn->mecid                = null;
        $dcpn->dcpestado            = false;
        if($dcpn->save()){
            $respuesta = true;
            $dcpn->fcps = [];
            $datos = $dcpn;


        }else{
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
