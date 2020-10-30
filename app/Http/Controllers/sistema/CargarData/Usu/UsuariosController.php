<?php

namespace App\Http\Controllers\sistema\CargarData\Usu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\usuusuarios;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class UsuariosController extends Controller
{
    public function CargarDataUsuarios()
    {
        $logs = [];

        $fichero_subido = base_path().'/public/sistema/excels/mUsuarios.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            $id    = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $name  = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
            $pass  = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();

            $usu = usuusuarios::find($id);

            if(!$usu){
                $usun = new usuusuarios;
                $usun->usuid          = $id;
                $usun->tpuid          = 2;
                $usun->usuusuario     = $name;
                $usun->usucontrasenia = $pass;
                $usun->usutoken       = Str::random(60);
                if($usun->save()){

                }else{
                    $logs[] = "El usuario no se pudo agregar: ".$id;
                }
            }else{
                $logs[] = "El usuario: ".$id." ya existe";
            }
        }

        dd($logs);
    }
}
