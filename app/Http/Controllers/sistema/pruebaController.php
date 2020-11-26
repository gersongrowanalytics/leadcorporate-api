<?php

namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class pruebaController extends Controller
{
    public function ProveedoresSkus()
    {
        $mcps = mcpmarcascategoriasproveedores::join('prvproveedores as prv', 'prv.prvid', 'mcpmarcascategoriasproveedores.prvid')
                                                ->join('catcategorias as cat', 'cat.catid', 'mcpmarcascategoriasproveedores.catid')
                                                ->join('proproductos as pro', 'pro.proid', 'cat.proid')
                                                ->get([
                                                    'prv.prvnombre',
                                                    'pro.prosku',
                                                    'pro.pronombre'
                                                ]);

        return response()->json([
            'datos' => $mcps
        ]);

    }
}
