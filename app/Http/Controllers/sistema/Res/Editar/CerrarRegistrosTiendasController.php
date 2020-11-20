<?php

namespace App\Http\Controllers\sistema\Res\Editar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\resregistrossucursales;
use App\sucsucursales;

class CerrarRegistrosTiendasController extends Controller
{
    public function CerrarRegistroTiendas()
    {

        $fecActual = new \DateTime();
        date_default_timezone_set("America/Lima");
        $fechaActual = date('Y-m-d H:i:s');
        $horaActual  = date('H:i:s');

        $respuesta = true;
        $log = array(
            "res" => [],
            'suc' => []
        );
        $mensaje = "Tu información ha sido actualizada";

        $ress = resregistrossucursales::where('resestado', false)
                                        ->get([
                                            'resid'
                                        ]);

        foreach($ress as $res){
            $rese = resregistrossucursales::find($res->resid);
            $rese->resfechasalidatienda = $fechaActual;
            $rese->reshorasalidatienda  = $horaActual;
            $rese->resestado = true;
            if($rese->update()){

            }else{
                $respuesta = false;
                $mensaje = "Lo sentimos ocurrio un error al momento de editar el estado del registro";
                $log['res'][] = "No se pudo editar el registro: ".$res->resid." con fecha: ".$fechaActual;
            }
        }

        $sucs = sucsucursales::where('sucestado', false)
                                ->get([
                                    'sucid'
                                ]);

        foreach($sucs as $suc){
            $suce = sucsucursales::find($suc->sucid);
            $suce->sucestado = true;
            if($suce->update()){

            }else{
                $respuesta = false;
                $mensaje = "Lo sentimos ocurrio un error al momento de editar el estado de la sucursal";
                $log['suc'][] = "No se pudo editar la sucursal: ".$suc->sucid." con fecha: ".$fechaActual;
            }
        }

        return response()->json([
            'respuesta' => $respuesta,
            'mensaje'   => $mensaje,
            'log'       => $log
        ]);

    }
}
