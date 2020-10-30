<?php

namespace App\Http\Controllers\sistema\Dsu\Editar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\dsudatossubpasos;
use App\paupasosusuarios;
use App\fopfotospasos;
use Illuminate\Support\Str;

class DsuDatosSubPasosController extends Controller
{
    public function EditarDsu(Request $request)
    {
        $respuesta      = true;
        $mensaje        = '';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $pauid = $request['pauid'];
        $dsuid = $request['dsuid'];
        $fotos = $request['fotos'];

        try{
            $pau = paupasosusuarios::find($pauid);

            if($pau){
                $pau->pauestado = true;
                $pau->update();
            }

            $dsu = dsudatossubpasos::find($dsuid);

            if($dsu){
                $dsu->dsuestado = true;
                $dsu->update();
            }

            for($contFot = 0; $contFot < sizeof($fotos); $contFot++){
                
                if($fotos[$contFot]['estado'] == "nuevo"){
                    $imagen 	= base64_decode($fotos[$contFot]['foto']);
                    $fichero	= "/sistema/img/IngresoTienda/";
                    $nombre 	= Str::random(10).'.png';
                    file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);
                    
                    $fopn = new fopfotospasos;
                    $fopn->dsuid = $dsuid;
                    $fopn->fopimagen = env('APP_URL').$fichero.$nombre;
                    $fopn->save();

                }else if($fotos[$contFot]['estado'] == "eliminado"){
                    $fop = fopfotospasos::find($fotos[$contFot]['fopid']);
                    $fop->delete();

                }else if($fotos[$contFot]['estado'] == "existente"){

                }
            }

            $datos = fopfotospasos::where('dsuid', $dsuid)->get(['fopid', 'fopimagen']);



        } catch (Exception $e) {
            $mensajedev = $e->getMessage();
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
