<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasDos\SpaDos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\dsudatossubpasos;
use App\paupasosusuarios;
use App\fopfotospasos;
use Illuminate\Support\Str;

class SpaEstadoFinalController extends Controller
{
    public function EditarSpaEstadoFinal(Request $request)
    {
        $respuesta      = true;
        $mensaje        = 'Tu información ha sido actualizada';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $pauid    = $request['pauid'];
        $dsuid    = $request['dsuid'];
        $ordenado = $request['ordenado'];
        $limpio   = $request['limpio'];
        $fotos    = $request['fotos'];

        try{
            $pau = paupasosusuarios::find($pauid);

            if($pau){
                $pau->pauestado = true;
                $pau->update();
            }

            $dsu = dsudatossubpasos::find($dsuid);

            if($dsu){
                $dsu->dsuestado   = true;
                $dsu->dsuordenado = $ordenado;
                $dsu->dsulimpio   = $limpio;
                $dsu->update();
            }

            for($contFot = 0; $contFot < sizeof($fotos); $contFot++){
                
                if($fotos[$contFot]['estado'] == "nuevo"){
                    $imagen 	= base64_decode($fotos[$contFot]['foto']);
                    $fichero	= "/sistema/img/OrdenGondola/EstadoFinal/";
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
            $mensaje   = 'Lo sentimos ocurrio un error al momento de guardar tu información';
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
