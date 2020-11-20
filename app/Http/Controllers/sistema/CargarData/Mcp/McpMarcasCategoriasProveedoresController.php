<?php

namespace App\Http\Controllers\sistema\CargarData\Mcp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\mcpmarcascategoriasproveedores;
use App\prvproveedores;
use App\marmarcas;
use App\catcategorias;
use App\capcategoriasproveedores;
use App\mapmarcasproveedores;
use App\proproductos;
use App\fecfechas;
use App\prsproductossucursales;
use App\rpsregistroproductossucursales;

class McpMarcasCategoriasProveedoresController extends Controller
{
    public function McpCargarData()
    {
        $logs = [];

        // $fichero_subido = base_path().'/public/sistema/excels/mProveedoresCategoriasMarcasProductos.xlsx';
        $fichero_subido = base_path().'/public/sistema/excels/proveedoresCategoriasMarcasProductos/ProveedoresCategoriasMarcasProductos.xlsx';

        $objPHPExcel    = IOFactory::load($fichero_subido);
        $objPHPExcel->setActiveSheetIndex(0);
        $numRows        = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $ultimaColumna  = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();

        for ($i=2; $i <= $numRows ; $i++) {
            
            $categoria   = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            $marca       = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
            $proveedor   = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
            $productosku = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
            $producto    = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();

            if($categoria){
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

                $catid = 0;
                if($categoria == "PHIGIENICO"){
                    $catid = 1;
                }else if($categoria == "FACIALES"){
                    $catid = 6;
                }else if($categoria == "JABONES"){
                    $catid = 5;
                }else if($categoria == "MASCARILLAS"){
                    $catid = 7;
                }else if($categoria == "PAÑOS"){
                    $catid = 4;
                }else if($categoria == "PTOALLA"){
                    $catid = 2;
                }else if($categoria == "SERVILLETAS"){
                    $catid = 3;
                }

                $pro = proproductos::where('prosku', $productosku)
                                    ->first(['proid']);
                                    
                $proid = 0;
                if($pro){
                    $pro->marid = $marid;
                    $pro->update();

                    $proid = $pro->proid;
                }else{
                    $pron = new proproductos;
                    $pron->catid     = $catid;
                    $pron->marid     = $marid;
                    $pron->pronombre = $producto;
                    $pron->prosku    = $productosku;
                    if($pron->save()){
                        $proid = $pron->proid;
                    }else{
                        $logs[] = "No se pudo agregar el producto: ".$producto." con codigo: ".$productosku;
                    }

                }

                $cap = capcategoriasproveedores::where('prvid', $prvid)
                                        ->where('catid', $catid)
                                        ->first(['capid']);
                $capid = 0;
                if(!$cap){
                    $capn = new capcategoriasproveedores;
                    $capn->prvid = $prvid;
                    $capn->catid = $catid;
                    $capn->save();

                    $capid = $capn->capid;
                }else{
                    $capid = $cap->capid;
                }

                $map = mapmarcasproveedores::where('prvid', $prvid)
                                    ->where('marid', $marid)
                                    ->first(['mapid']);
                
                $mapid = 0;
                if(!$map){
                    $mapn = new mapmarcasproveedores;
                    $mapn->prvid = $prvid;
                    $mapn->marid = $marid;
                    $mapn->save();

                    $mapid = $mapn->mapid;
                }else{
                    $mapid = $map->mapid;
                }


                $mcp = mcpmarcascategoriasproveedores::where('capid', $capid)
                                                    ->where('catid', $catid)
                                                    ->where('mapid', $mapid)
                                                    ->where('marid', $marid)
                                                    ->where('prvid', $prvid)
                                                    ->first(['mcpid']);

                $mcpid = 0;
                if(!$mcp){
                    $mcpn = new mcpmarcascategoriasproveedores;
                    $mcpn->capid = $capid;
                    $mcpn->catid = $catid;
                    $mcpn->mapid = $mapid;
                    $mcpn->marid = $marid;
                    $mcpn->prvid = $prvid;
                    $mcpn->save();
                }else{
                    $logs[] = "El mcp ya existe: ".$mcp->mcpid;
                }


                // 
                $fechacreacion = new \DateTime(date("Y-m-d H:i:s", strtotime('2020-11-18')));
                $fechaactualizacion = new \DateTime(date("Y-m-d H:i:s", strtotime('2020-11-18')));

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

                $prs = prsproductossucursales::where('prvid', $prvid)
                                            ->where('proid', $proid)
                                            ->where('catid', $catid)
                                            ->where('marid', $marid)
                                            ->first(['prsid']);

                $prsid = 0;

                if(!$prs){
                    $prsn = new prsproductossucursales;
                    $prsn->prvid      = $prvid;
                    $prsn->proid      = $proid;
                    $prsn->catid      = $catid;
                    $prsn->marid      = $marid;
                    $prsn->fecid      = $fecid;
                    $prsn->prsstock   = 0;
                    $prsn->created_at = $fechacreacion;
                    $prsn->updated_at = $fechaactualizacion;
                    if($prsn->save()){
                        $prsid = $prsn->prsid;
                        $rps = new rpsregistroproductossucursales;
                        $rps->prsid    = $prsid;
                        $rps->fecid    = $fecid;
                        $rps->rpsstock = 0;
                        $rps->save();
                    }else{
                        $logs[] = "No se pudo agregar el prs";
                    }
                }
            }else{

            }
            



        }
        
        dd($logs);
    }
}
