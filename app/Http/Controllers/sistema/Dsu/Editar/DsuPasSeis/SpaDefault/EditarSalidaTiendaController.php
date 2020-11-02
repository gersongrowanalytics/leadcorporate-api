<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasSeis\SpaDefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\paupasosusuarios;
use App\dsudatossubpasos;
use App\resregistrossucursales;
use App\sucsucursales;

class EditarSalidaTiendaController extends Controller
{
    public function EditarSalidaTienda(Request $request)
    {
        date_default_timezone_set("America/Lima");
        $fechaActual = date('Y-m-d');
        $horaActual  = date('H:i:s');

        $dsu = $request['dsu'];
        $pauid = $request['pauid'];

        $pau = paupasosusuarios::find($pauid);

        if($pau){
            $pau->pauestado = true;
            if($pau->update()){
                $res = resregistrossucursales::find($pau->resid);
                $res->resfechasalidatienda = $fechaActual;
                $res->reshorasalidatienda  = $horaActual;
                $res->resestado = true;
                if($res->update()){
                    $suc = sucsucursales::find($res->sucid);
                    $suc->sucestado = true;
                    $suc->update();

                }
            }
        }

        $dsue = dsudatossubpasos::find($dsu['dsuid']);

        if($dsue){
            $dsue->dsuestado   = true;
            $dsue->dsucheckout = $dsu['dsucheckout'];
            $dsue->update();
        }

        return response()->json([
            'respuesta' => true,
            'mensaje'   => "Tu información ha sido actualizada",
        ]);

    }
}
