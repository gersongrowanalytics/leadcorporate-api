<?php

namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\mcpmarcascategoriasproveedores;

class pruebaController extends Controller
{
    public function ProveedoresSkus()
    {
        $mcps = mcpmarcascategoriasproveedores::join('prvproveedores as prv', 'prv.prvid', 'mcpmarcascategoriasproveedores.prvid')
                                                ->join('catcategorias as cat', 'cat.catid', 'mcpmarcascategoriasproveedores.catid')
                                                ->join('proproductos as pro', 'pro.catid', 'cat.catid')
                                                ->get([
                                                    'prv.prvnombre',
                                                    'cat.catnombre',
                                                    'pro.prosku',
                                                    'pro.pronombre'
                                                ]);

        // $mcps = mcpmarcascategoriasproveedores::join('prvproveedores as prv', 'prv.prvid', 'mcpmarcascategoriasproveedores.prvid')
        //                                         ->join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
        //                                         ->join('proproductos as pro', 'pro.marid', 'mar.marid')
        //                                         ->get([
        //                                             'prv.prvnombre',
        //                                             'mar.marnombre',
        //                                             'pro.prosku',
        //                                             'pro.pronombre'
        //                                         ]);

        return response()->json([
            'datos' => $mcps
        ]);

    }
}
