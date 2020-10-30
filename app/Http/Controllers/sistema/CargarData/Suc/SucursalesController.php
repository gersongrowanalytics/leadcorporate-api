<?php

namespace App\Http\Controllers\sistema\CargarData\Suc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\sucsucursales;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SucursalesController extends Controller
{
    public function CargarDataSucursales()
    {
        $logs = [];

        $fichero_subido = base_path().'/public/sistema/excels/mTiendas.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows; $i++) {
            
            $id           = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $nombre       = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
            $latitud      = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
            $longitud     = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
            $diaatencion  = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
            $horaatencion = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
            $diadespacho  = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
            $horadespacho = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
            $direccion    = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
            $detalle      = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();

            $suc = sucsucursales::find($id);
            if(!$suc){
                $sucn = new sucsucursales;
                $sucn->sucid           = $id;
                $sucn->sucnombre       = $nombre;
                $sucn->sucdiaatencion  = $diaatencion;
                $sucn->suchoraatencion = $horaatencion;
                $sucn->sucdiadespacho  = $diadespacho;
                $sucn->suchoradespacho = $horadespacho;
                $sucn->suclatitud      = $latitud;
                $sucn->suclongitud     = $longitud;
                $sucn->sucdireccion    = $direccion;
                if($sucn->save()){

                }else{
                    $logs[] = "No se pudo agregar la sucursal: ".$sucid;
                }
                
            }else{
                $logs[] = "La sucursal: ".$sucid." ya existe";
            }
            

        }
        
        dd($logs);
    }
}
