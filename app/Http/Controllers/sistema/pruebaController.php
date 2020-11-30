<?php

namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\mcpmarcascategoriasproveedores;
use App\sucsucursales;
use App\prsproductossucursales;
use App\proproductos;

class pruebaController extends Controller
{
    public function ProveedoresSkus()
    {
        // $mcps = mcpmarcascategoriasproveedores::join('prvproveedores as prv', 'prv.prvid', 'mcpmarcascategoriasproveedores.prvid')
        //                                         ->join('catcategorias as cat', 'cat.catid', 'mcpmarcascategoriasproveedores.catid')
        //                                         ->join('proproductos as pro', 'pro.catid', 'cat.catid')
        //                                         ->distinct('prv.prvid')
        //                                         ->get([
        //                                             'prv.prvnombre',
        //                                             'cat.catnombre',
        //                                             'pro.prosku',
        //                                             'pro.pronombre'
        //                                         ]);

        $mcps = mcpmarcascategoriasproveedores::join('prvproveedores as prv', 'prv.prvid', 'mcpmarcascategoriasproveedores.prvid')
                                                ->join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                ->join('proproductos as pro', 'pro.marid', 'mar.marid')
                                                ->distinct('prv.prvid')
                                                ->get([
                                                    'prv.prvnombre',
                                                    'mar.marnombre',
                                                    'pro.prosku',
                                                    'pro.pronombre'
                                                ]);

        return response()->json([
            'datos' => $mcps
        ]);

    }

    public function EliminarDuplicadosStock()
    {


        $sucs = sucsucursales::all();
        $pros = proproductos::all();
        $log = [];

        foreach($pros as $pro){

            foreach($sucs as $posicion => $suc){

            
                $prss = prsproductossucursales::where('sucid', $suc->sucid)
                                                ->where('proid', $pro->proid)
                                                ->get();
                
                $contador = 0;
                $cambios = [];
                foreach($prss as $prs){
                    
                    if($contador == 0){

                    }else{
                        $prse = prsproductossucursales::find($prs->prsid);
                        $prse->prsestado = 0;
                        if($prse->update()){
                            $log[] = "Se cambio el estado de: ".$prs->prsid." con el produto: ".$pro->proid." de la sucursal: ".$suc->sucid;
                            $cambios[] = "Se cambio el estado de: ".$prs->prsid." con el produto: ".$pro->proid." de la sucursal: ".$suc->sucid;
                            $sucs[$posicion]['cambios'] = $cambios;
                        }

                    }

                    $contador = $contador+1;
                }
    
            }
        }

        dd($log);
        
        


    }
}
