<?php

namespace App\Http\Controllers\sistema\Dsu\Crear\DsuPasCuatro\SpaDefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\mtpmaterialespops;
use App\dsudatossubpasos;
use App\mppmaterialespopspasos;
use App\mapmarcasproveedores;

class CrearMppsController extends Controller
{
    public function CrearMppsParaExhibicion(Request $request)
    {

        $dsuid = $request['dsuid'];
        $prvid = $request['prvid'];

        $dsu = dsudatossubpasos::find($dsuid);

        $mtps = mtpmaterialespops::get(['mtpid']);

        foreach($mtps as $mtp){

            $mppn = new mppmaterialespopspasos;
            $mppn->mtpid            = $mtp->mtpid;
            $mppn->dsuid            = $dsuid;
            $mppn->pauid            = $dsu->pauid;
            $mppn->spaid            = $dsu->spaid;
            $mppn->pasid            = $dsu->pasid;
            $mppn->prvid            = $prvid;
            $mppn->mapid            = null;
            $mppn->marid            = null;
            $mppn->mppmarca         = null;
            $mppn->mppproducto      = null;
            $mppn->mppdescripcion   = null;
            $mppn->save();


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
                                            'mppmarca',
                                            'mppproducto',
                                            'mppdescripcion'
                                        ]);
        foreach($mpps as $posicionMpp => $mpp){
            $mpps[$posicionMpp]['fmps'] = [];
            $mpps[$posicionMpp]['maps'] = $maps;

        }
        
        return response()->json([
            'respuesta' => true,
            'mensaje'   => "Materiales POPS cargados correctamente",
            'datos'     => $mpps
        ]);
        

    }
}
