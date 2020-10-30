<?php

namespace App\Http\Controllers\sistema\CargarData\Uss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\sucsucursales;
use App\usuusuarios;
use App\ussusuariossucursales;

class UssSucursalesUsuariosController extends Controller
{
    public function CargarDataUss()
    {
        $logs = [];

        $fichero_subido = base_path().'/public/sistema/excels/mUsuariosSucursales.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows; $i++) {
            $store_id = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $user_id  = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();

            $usu = usuusuarios::find($user_id);
            if($usu){

                $suc = sucsucursales::find($store_id);

                if($suc){

                    $uss = ussusuariossucursales::where('usuid', $user_id)
                                                ->where('sucid', $store_id)
                                                ->first();

                    if(!$uss){
                        $ussn = new ussusuariossucursales;
                        $ussn->usuid = $user_id;
                        $ussn->sucid = $store_id;
                        if($ussn->save()){

                        }else{
                            $logs[] = "No se pudo agregar el usuario: ".$user_id." a la sucursal: ".$store_id;
                        }
                    }else{
                        $logs[] = "El usuario: ".$user_id." ya se encuentra asignado a la sucursal: ".$store_id;
                    }
                }else{
                    $logs[] = "La sucursal: ".$store_id." no existe !";
                }
            }else{
                $logs[] = "El usuario: ".$user_id." no existe !";
            }
        }

        dd($logs);
    }
}
