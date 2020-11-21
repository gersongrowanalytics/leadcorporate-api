<?php

namespace App\Http\Controllers\sistema\CargarData\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\mapmarcasproveedores;
use App\marmarcas;
use App\prvproveedores;

class MapMarcasProveedoresController extends Controller
{
    public function MapMarcasProveedores()
    {
        $logs = [];

        // $fichero_subido = base_path().'/public/sistema/excels/mProveedoresMarcas.xlsx';
        $fichero_subido = base_path().'/public/sistema/excels/marcasProveedores/proveedoresMarcas.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            
            $proveedor = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $marca     = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();

            $prv = prvproveedores::where('prvnombre', $proveedor)->first(['prvid']);
            $prvid = 0;
            if(!$prv){
                $prvn = new prvproveedores;
                $prvn->prvnombre    = $proveedor;
                $prvn->prvprincipal = false;
                $prvn->save();

                $prvid = $prvn->prvid;
            }else{
                $prvid = $prv->prvid;
            }

            $mar = marmarcas::where('marnombre', $marca)->first(['marid']);
            $marid = 0;
            if(!$mar){
                $marn = new marmarcas;
                $marn->marnombre = $marca;
                $marn->save();
                
                $marid = $marn->marid;
            }else{
                $marid = $mar->marid;
            }


            $map = mapmarcasproveedores::where('prvid', $prvid)
                                        ->where('marid', $marid)
                                        ->first(['mapid']);

            if(!$map){
                $mapn = new mapmarcasproveedores;
                $mapn->prvid = $prvid;
                $mapn->marid = $marid;
                $mapn->save();

            }else{
                $logs[] = "Ya existe este map: ".$map->mapid;
            }
            

        }
        
        dd($logs);
    }
}
