<?php

namespace App\Http\Controllers\sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\mcpmarcascategoriasproveedores;
use App\sucsucursales;
use App\prsproductossucursales;
use App\proproductos;
use App\prvproveedores;
use App\pappasosproductos;

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

    public function EliminarDuplicadosStockSucursal()
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

    public function EliminarDuplicadosStockProveedor()
    {

        $prvs = prvproveedores::all();
        $pros = proproductos::all();
        $log = [];
        
        foreach($pros as $pro){

            foreach($prvs as $posicion => $prv){

            
                $prss = prsproductossucursales::where('prvid', $prv->prvid)
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
                            $log[] = "Se cambio el estado de: ".$prs->prsid." con el produto: ".$pro->proid." del proveedor: ".$prv->prvid;
                            $cambios[] = "Se cambio el estado de: ".$prs->prsid." con el produto: ".$pro->proid." del proveedor: ".$prv->prvid;
                            $prvs[$posicion]['cambios'] = $cambios;
                        }

                    }

                    $contador = $contador+1;
                }
    
            }
        }

        dd($log);

    }

    public function EncontrarProductosDuplicados()
    {
        // $arr = array(
        //     array(
        //         "proid"  => [],
        //         "prosku" => [],
        //         "pronombre" => "",
        //     )
        // );

        // $pros = proproductos::orderby('pronombre')->get();

        // $prosv2 = proproductos::orderby('pronombre')->get();

        // $contador = 0;

        // foreach($pros as $pro){

        //     $encontro = false;

        //     foreach($prosv2 as $prov2){
        //         if($pro->proid != $prov2->proid){
        //             if($pro->pronombre == $prov2->pronombre){
        //                 if($encontro == false){
        //                     $arr[$contador]['proid'][] = $pro->proid;
        //                     $arr[$contador]['prosku'][] = $pro->prosku;
        //                     $encontro = true;
        //                 }
        //                 $arr[$contador]['proid'][] = $prov2->proid;
        //                 $arr[$contador]['prosku'][] = $prov2->prosku;
        //                 $arr[$contador]['pronombre'] = $prov2->pronombre;

        //             }else{
    
        //             }
        //         }
        //     }

        //     if($encontro == true){
        //         $contador = $contador+1;
        //     }
        // }

        // // dd($arr);

        // for($i =0 ; $i < sizeof($arr); $i++){
        //     for($c = 0; $c < sizeof($arr[$i]['proid']); $c++){
        //         $prss = prsproductossucursales::join('sucsucursales as suc', 'suc.sucid', 'prsproductossucursales.sucid')
        //                                     ->join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
        //                                     ->where('pro.proid', $arr[$i]['proid'][$c])
        //                                     ->where('prsestado', 1)
        //                                     ->orderby('suc.sucid')
        //                                     ->get([
        //                                         'prsproductossucursales.prsid',
        //                                         'suc.sucid',
        //                                         'suc.sucnombre',
        //                                         'pro.pronombre',
        //                                         'pro.prosku',
        //                                     ]);
        //         if(sizeof($prss) > 0){
        //             foreach($prss as $prs){
        //                 echo $prs->prsid." - ".$prs->sucid." - ".$prs->sucnombre." - ".$prs->pronombre." - ".$prs->prosku."<br>";
        //             }
        //             echo "<br>-------------------------------------------------------------<br>";
        //         }
        //     }
        // }

        // echo "<br>-------------------------------------------------------------<br>";
        // echo "<br>-------------------------------------------------------------<br>";
        // echo "<br>-------------------------------------------------------------<br>";
        // echo "<br>-------------------------------------------------------------<br>";
        // echo "<br>-------------------------------------------------------------<br>";

        // for($i =0 ; $i < sizeof($arr); $i++){
        //     for($c = 0; $c < sizeof($arr[$i]['proid']); $c++){
        //         $prss = prsproductossucursales::join('prvproveedores as prv', 'prv.prvid', 'prsproductossucursales.prvid')
        //                                     ->join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
        //                                     ->where('pro.proid', $arr[$i]['proid'][$c])
        //                                     ->where('prsestado', 1)
        //                                     ->orderby('prv.prvid')
        //                                     ->get([
        //                                         'prsproductossucursales.prsid',
        //                                         'prv.prvid',
        //                                         'prv.prvnombre',
        //                                         'pro.proid',
        //                                         'pro.pronombre',
        //                                         'pro.prosku',
        //                                     ]);
        //         if(sizeof($prss) > 0){
        //             foreach($prss as $prs){
        //                 echo $prs->prsid." - ".$prs->prvid." - ".$prs->prvnombre." - ".$prs->proid." - ".$prs->pronombre." - ".$prs->prosku."<br>";
        //             }
        //             echo "<br>-------------------------------------------------------------<br>";
        //         }
        //     }
        // }

        $prvs = prvproveedores::all();

        foreach($prvs as $prv){
            $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                        ->join('prvproveedores as prv', 'prv.prvid', 'prsproductossucursales.prvid')
                                        ->where('prv.prvid', $prv->prvid)
                                        ->where('prsestado', 1)
                                        ->orderby('pronombre')
                                        ->get([
                                            'prsproductossucursales.prsid',
                                            'pro.proid',
                                            'pro.prosku',
                                            'pro.pronombre',
                                            'prv.prvnombre'
                                        ]);

            $prssv2 = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                        ->join('prvproveedores as prv', 'prv.prvid', 'prsproductossucursales.prvid')
                                        ->where('prv.prvid', $prv->prvid)
                                        ->where('prsestado', 1)
                                        ->orderby('pronombre')
                                        ->get([
                                            'prsproductossucursales.prsid',
                                            'pro.proid',
                                            'pro.pronombre'
                                        ]);

            foreach($prss as $prs){
                foreach($prssv2 as $prsv2){
                    if($prs->prsid != $prsv2->prsid){
                        if($prs->pronombre == $prsv2->pronombre){
                            echo $prs->prsid." - ".$prs->prvnombre." - ".$prs->proid." - ".$prs->prosku." - ".$prs->pronombre."<br>";
                        }else{
                            
                        }
                    }
                }
                echo "<br>-------------------------------------------------------------<br>";
            }

            
        }
        
    }

    public function EliminarDuplicadosPaps()
    {
        $logs = array(
            "eliminados" => [],
            "noeliminados" => []
        );

        // $paps = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
        //                             ->join('dsudatossubpasos as dsu', 'dsu.dsuid', 'pappasosproductos.dsuid')
        //                             ->where('prsestado', 0)
        //                             ->where('papcantidad', 0)
        //                             ->where('spaid', 6)
        //                             ->get([
        //                                 'pappasosproductos.papid',
        //                                 'prs.prsid',
        //                                 'papcantidad',
        //                                 'papstock',
        //                                 'prs.proid'
        //                             ]);


        // foreach($paps as $pap){
        //     $papd = pappasosproductos::find($pap->papid);
        //     if($papd->delete()){
        //         $logs['eliminados'][] = "El pap se elimino correctamente: ".$pap->papid;
        //     }else{
        //         $logs['noeliminados'][] = "El pap no se pudo eliminar: ".$pap->papid;
        //     }
        // }




        // $paps = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
        //                         ->join('dsudatossubpasos as dsu', 'dsu.dsuid', 'pappasosproductos.dsuid')
        //                         ->where('prsestado', 0)
        //                         ->where('spaid', 6)
        //                         ->get([
        //                             'pappasosproductos.papid',
        //                             'prs.prsid',
        //                             'papcantidad',
        //                             'papstock',
        //                             'prs.proid'
        //                         ]);


        // foreach($paps as $pap){
        //     if($pap->papcantidad == $pap->papstock){
        //         $papd = pappasosproductos::find($pap->papid);
        //         if($papd->delete()){
        //             $logs['eliminados'][] = "El pap se elimino correctamente: ".$pap->papid;
        //         }else{
        //             $logs['noeliminados'][] = "El pap no se pudo eliminar: ".$pap->papid;
        //         }
        //     }
        // }

        $paps = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                ->join('dsudatossubpasos as dsu', 'dsu.dsuid', 'pappasosproductos.dsuid')
                                ->where('prsestado', 0)
                                ->where('spaid', 6)
                                ->get([
                                    'pappasosproductos.papid',
                                    'prs.prsid',
                                    'papcantidad',
                                    'papstock',
                                    'prs.proid',
                                    'prs.sucid',
                                    'dsu.dsuid'
                                ]);

        foreach($paps as $pap){

            $paps2 = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                    ->join('dsudatossubpasos as dsu', 'dsu.dsuid', 'pappasosproductos.dsuid')
                                    ->where('spaid', 6)
                                    ->where('sucid', $pap->sucid)
                                    ->where('prs.proid', $pap->proid)
                                    ->where('dsu.dsuid', $pap->dsuid)

                                    ->where('prsestado', 1)
                                    ->get([
                                        'pappasosproductos.papid',
                                        'prs.prsid',
                                        'papcantidad',
                                        'papstock',
                                        'prs.proid',
                                        'prs.sucid'
                                    ]);
            echo "El pap1: ".$pap->papcantidad." y stock: ".$pap->papstock.': <br>';
            if(sizeof($paps2) > 0){
                foreach($paps2 as $pap2){
                    echo "El pap2: ".$pap2->papcantidad." y stock: ".$pap2->papstock.':';
                    if($pap->papcantidad < $pap2->papcantidad && $pap->papstock < $pap2->papstock ){
                        echo "ELIMINAR";
                    }
                    echo"<br>";
                }
            }else{
                echo "NO HAY UN PAPV2 <br>";
            }

            echo "<br><br>------------------------------------------<br><br>";

        }

        // dd($logs);
    }
}
