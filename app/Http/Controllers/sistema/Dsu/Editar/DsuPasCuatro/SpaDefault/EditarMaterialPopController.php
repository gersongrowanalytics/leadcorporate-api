<?php

namespace App\Http\Controllers\sistema\Dsu\Editar\DsuPasCuatro\SpaDefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\mppmaterialespopspasos;
use App\fmpfotosmaterialespops;
use App\paupasosusuarios;
use App\mapmarcasproveedores;

class EditarMaterialPopController extends Controller
{
    public function EditarMaterialPop(Request $request)
    {
        $respuesta      = true;
        $mensaje        = "Tu información ha sido actualizada";
        $datos          = [];
        $mensajeDetalle = ""; 
        $logs = [];

        $mpp   = $request['mpp'];
        $pauid = $request['pauid'];
        $dsuid = $request['dsuid'];
        $prvid = $request['prvid'];

        $mppe = mppmaterialespopspasos::find($mpp['mppid']);
        $mppe->marid          = $mpp['marid'];
        $mppe->mppmarca       = $mpp['mppmarca'];
        $mppe->mppproducto    = $mpp['mppproducto'];
        $mppe->mppdescripcion = $mpp['mppdescripcion'];
        if($mppe->update()){
            $fmps = $mpp['fmps'];

            foreach($fmps as $fmp){
                if($fmp['fmpid'] == 0){
                    $imagen 	= base64_decode($fmp['fmpimagen']);
                    $fichero	= "/sistema/img/exhibicion/";
                    $nombre 	= Str::random(10).'.png';
                    file_put_contents(base_path().'/public'.$fichero.$nombre, $imagen);
    
                    $fmpn = new fmpfotosmaterialespops;
                    $fmpn->mppid = $mpp['mppid'];
                    $fmpn->fmpimagen = env('APP_URL').$fichero.$nombre;
                    $fmpn->save();
                }
            }

        }

        $pau = paupasosusuarios::find($pauid);

        if($pau){
            $pau->pauestado = true;
            $pau->update();
        }

        $maps = mapmarcasproveedores::join('marmarcas as mar', 'mar.marid', 'mapmarcasproveedores.marid')
                                    ->where('prvid', $prvid)
                                    ->get([
                                        'mapid',
                                        'mar.marid',
                                        'mar.marnombre'
                                    ]);

        $mpps = mppmaterialespopspasos::join('mtpmaterialespops as mtp', 'mtp.mtpid', 'mppmaterialespopspasos.mtpid')
                                ->where('dsuid', $dsuid)
                                ->where('prvid', $prvid)
                                ->get([
                                    'mppid',
                                    'mtp.mtpid',
                                    'mtpnombre',
                                    'mtpimagen',
                                    'marid',
                                    'mppmarca',
                                    'mppproducto',
                                    'mppdescripcion'
                                ]);

        foreach($mpps as $posicionMpp => $mpp){
            $fmps = fmpfotosmaterialespops::where('mppid', $mpp->mppid)
                                            ->get(['fmpid', 'fmpimagen']);

            $mpps[$posicionMpp]['fmps'] = $fmps;
            $mpps[$posicionMpp]['maps'] = $maps;
        }

        $datos = $mpps;

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle
        ]);

    }
}
