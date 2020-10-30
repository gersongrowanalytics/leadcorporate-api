<?php

namespace App\Http\Controllers\sistema\CargarData\Pro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\proproductos;
use App\catcategorias;

class ProductosController extends Controller
{
    public function CargarDataProductosMoises()
    {
        $logs = [];

        $fichero_subido = base_path().'/public/sistema/excels/mProductos.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            
            $id          = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $categoriaid = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
            $codigo      = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
            $nombre      = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();


            $pro = proproductos::find($id);
            if(!$pro){
                
                $cat = catcategorias::find($categoriaid);

                if($cat){
                    $pron = new proproductos;
                    $pron->proid     = $id;
                    $pron->catid     = $categoriaid;
                    $pron->marid     = null;
                    $pron->pronombre = $nombre;
                    $pron->prosku    = $codigo;
                    if($pron->save()){

                    }else{
                        $logs[] = "No se pudo agregar el producto: ".$id." con codigo: ".$codigo;
                    }

                }else{
                    $logs[] = "No se encontro la categoria con id: ".$categoriaid;
                }

            }else{
                $logs[] = "El producto: ".$id." con codigo: ".$codigo." ya existe";
            }

        }
        
        dd($logs);
    }
}
