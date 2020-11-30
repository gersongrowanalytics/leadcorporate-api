<?php

namespace App\Http\Controllers\sistema\CargarData\Pro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\fecfechas;
use App\proproductos;
use App\sucsucursales;
use App\prsproductossucursales;
use App\rpsregistroproductossucursales;

class StockProductosController extends Controller
{
    public function CargarDataStockProducto()
    {
        $logs = [];

        $fichero_subido = base_path().'/public/sistema/excels/mstock.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            
            $sucursalid         = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $productoid         = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
            $cantidad           = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
            // $fechacreacion      = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
            // $fechaactualizacion = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
            
            $fechacreacion = new \DateTime(date("Y-m-d H:i:s", strtotime('2020-08-27')));
            $fechaactualizacion = new \DateTime(date("Y-m-d H:i:s", strtotime('2020-08-27')));

            $fec = fecfechas::where('fecfecha', $fechacreacion)->first(['fecid']);
            $fecid = 0;
            if($fec){
                $fecid = $fec->fecid;
            }else{
                $fecn = new fecfechas;
                $fecn->fecfecha = $fechacreacion;
                if($fecn->save()){
                    $fecid = $fecn->fecid;
                }else{
                    $logs[] = "No se pudo agregar la fecha";
                }
            }

            $pro = proproductos::find($productoid);
            $proid = 0;
            if($pro){
                $proid = $productoid;

                $suc = sucsucursales::find($sucursalid);
                $sucid = 0;
                if($suc){
                    $sucid = $sucursalid;


                    $prs = prsproductossucursales::where('sucid', $sucid)
                                                ->where('proid', $proid)
                                                ->where('catid', $pro->catid)
                                                ->where('marid', $pro->marid)
                                                ->first(['prsid']);
                    $prsid = 0;

                    if(!$prs){
                        $prsn = new prsproductossucursales;
                        $prsn->sucid = $sucid;
                        $prsn->proid = $proid;
                        $prsn->catid = $pro->catid;
                        $prsn->marid = $pro->marid;
                        $prsn->fecid = $fecid;
                        $prsn->prsstock = $cantidad;
                        $prsn->created_at = $fechacreacion;
                        $prsn->updated_at = $fechaactualizacion;
                        if($prsn->save()){
                            $prsid = $prsn->prsid;
                        }else{
                            $logs[] = "No se pudo agregar el stock";
                        }
                    }else{
                        $prsid = $prs->prsid;
                        $prs->prsstock = $cantidad;
                        $prs->fecid    = $fecid;
                        $prs->update();
                    }

                    $rps = new rpsregistroproductossucursales;
                    $rps->prsid    = $prsid;
                    $rps->fecid    = $fecid;
                    $rps->rpsstock = $cantidad;
                    $rps->save();




                    


                }else{
                    $logs[] = "La sucursal no existe: ".$productoid;
                }


            }else{
                $logs[] = "El producto no existe: ".$productoid;
            }



        }
        
        dd($logs);
    }

    public function CargarDataStockProductosUniversal()
    {
        $logs = array(
            "globales" => [],
            "proproductos"  => [],
            "sucsucursales" => [],
            "prsproductossucursales" => [],
            "rpsregistroproductossucursales" => []
        );

        $fichero_subido = base_path().'/public/sistema/excels/stocks/AL_29_11_I.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            
            $codSucursal            = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $nombreSucursal         = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
            $nombreMaterial         = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
            $codMaterial            = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
            $cantidadMaterial       = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
            
            
            if($codSucursal){

                date_default_timezone_set("America/Lima");
                $fechaActual = date('Y-m-d');

                $fec = fecfechas::where('fecfecha', $fechaActual)->first(['fecid']);
                $fecid = 0;
                if($fec){
                    $fecid = $fec->fecid;
                }else{
                    $fecn = new fecfechas;
                    $fecn->fecfecha = $fechaActual;
                    if($fecn->save()){
                        $fecid = $fecn->fecid;
                    }else{
                        $logs[] = "No se pudo agregar la fecha";
                    }
                }


                $pro = proproductos::where('prosku', $codMaterial)
                                    ->first([
                                        'proid',
                                        'catid',
                                        'marid'
                                    ]);

                if($pro){

                    if($nombreSucursal == "Santa Anita"){
                        $nombreSucursal = "Sta. Anita";
                    }else if($nombreSucursal == "Independecia"){
                        $nombreSucursal = "Independencia";
                    }else{
                        $nombreSucursal = substr($nombreSucursal, 0, strlen($nombreSucursal)-2);
                    }

                    $suc = sucsucursales::where('sucnombre', 'LIKE', "%".$nombreSucursal."%")
                                        ->first([
                                            'sucid'
                                        ]);

                    if($suc){
                        
                        $prs = prsproductossucursales::where('sucid', $suc->sucid)
                                                    ->where('proid', $pro->proid)
                                                    // ->where('catid', $pro->catid)
                                                    // ->where('marid', $pro->marid)
                                                    ->first(['prsid']);

                        $prsid = 0;

                        if(!$prs){
                            $prsn = new prsproductossucursales;
                            $prsn->sucid = $suc->sucid;
                            $prsn->proid = $pro->proid;
                            $prsn->catid = $pro->catid;
                            $prsn->marid = $pro->marid;
                            $prsn->fecid = $fecid;
                            $prsn->prsstock = $cantidadMaterial;
                            if($prsn->save()){
                                $prsid = $prsn->prsid;
                            }else{
                                $logs['prsproductossucursales'][] = "No se pudo agregar el registro de stock";
                            }

                        }else{
                            $prsid = $prs->prsid;
                            $prs->prsstock = $cantidadMaterial;
                            $prs->fecid    = $fecid;
                            $prs->update();
                        }

                        $rps = new rpsregistroproductossucursales;
                        $rps->prsid    = $prsid;
                        $rps->fecid    = $fecid;
                        $rps->rpsstock = $cantidadMaterial;
                        $rps->save();

                    }else{
                        $logs['sucsucursales'][] = "No existe la sucursal: ".$nombreSucursal;
                    }


                }else{
                    $logs['proproductos'][] = "No existe el producto: ".$codMaterial;
                }
            }else{
                $logs['globales'][] = "No existe registro linea: ".$i;
            }
            

        }
        
        dd($logs);
    }
}
