<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasCuatro\SpaDefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\dsudatossubpasos;
use App\fopfotospasos;
use App\paupasosusuarios;

class EditarFopsExhibicionController extends Controller
{
    public function EditarFopsExhibicion(Request $request)
    {

        $respuesta = true;
        $mensaje   = "Tu información ha sido actualizada";
        $datos     = [];

        $dsuid = $request['dsuid'];
        $fotos = $request['fotos'];
        $pauid = $request['pauid'];
        
        $dsu = dsudatossubpasos::find($dsuid);
        $dsu->dsuplanogramacategoria = true;
        if($dsu->update()){

            foreach($fotos as $foto){
                if($foto['fopid'] == 0){
                    $imagen 	= base64_decode($foto['fopimagen']);
                    $fichero	= "/sistema/img/exhibicion/planograma/";
                    $nombre 	= Str::random(10).'.png';
                    file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);
    
                    $fopn = new fopfotospasos;
                    $fopn->dsuid = $dsuid;
                    $fopn->fopimagen = env('APP_URL').$fichero.$nombre;
                    $fopn->save();
                }
            }
        }

        $pau = paupasosusuarios::find($pauid);

        if($pau){
            $pau->pauestado = true;
            $pau->update();
        }

        $fops = fopfotospasos::where('dsuid', $dsuid)
                            ->get([
                                'fopid',
                                'fopimagen'
                            ]);

        $datos = $fops;

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
        ]);
        
    }
}
