<?php

namespace App\Http\Controllers\sistema\Suc\Mostrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\sucsucursales;
use App\paupasosusuarios;
use App\dsudatossubpasos;
use App\resregistrossucursales;
use App\fopfotospasos;
use App\pappasosproductos;
use App\prsproductossucursales;
use App\cpacategoriaspasos;
use App\prvproveedores;
use App\mpamarcaspasos;
use App\dcpdatoscategoriaspasos;
use App\fcpfotoscategoriaspasos;
use App\mcpmarcascategoriasproveedores;
use App\mecmecanicas;
use App\tpetiposexhibicion;
use App\arrarrendados;
use App\fppfotosproductospasos;
use App\mppmaterialespopspasos;
use App\mapmarcasproveedores;
use App\fmpfotosmaterialespops;
use App\tpctiposcomprobantes;
use App\copcomprobantespasos;
use App\caccantidadescomprobantes;

class SucSucursalesController extends Controller
{
    public function MostrarSucursales()
    {
        $respuesta      = false;
        $mensaje        = '';
        $datos          = [];
        $mensajeDetalle = '';
        $mensajedev     = null;

        $sucs = sucsucursales::get([
                                'sucid',
                                'sucnombre',
                                'sucdiaatencion',
                                'suchoraatencion',
                                'sucdiadespacho',
                                'suchoradespacho',
                                'suclatitud',
                                'suclongitud',
                                'sucdireccion',
                                'sucestado'
                            ]);

        if(sizeof($sucs) > 0){
            $respuesta = true;
            $datos = $sucs;

            $mecsT = mecmecanicas::get(['mecid', 'mecnombre']);

            $tpesT = tpetiposexhibicion::get(['tpeid', 'tpenombre']);

            $arrsT = arrarrendados::get(['arrid', 'arrnombre']);

            foreach($sucs as $posicionsuc => $suc){
                if($suc->sucestado == true){
                    $sucs[$posicionsuc]['paus'] = [];
                }else{
                    $res = resregistrossucursales::where('sucid', $suc->sucid)->where('resestado', false)->first(['resid']);

                    $paus = paupasosusuarios::where('resid', $res->resid)
                                            ->get(['pauid', 'pasid', 'pauestado']);

                    foreach($paus as $posicionpau => $pau){
                        $dsus = dsudatossubpasos::where('pauid', $pau->pauid)
                                                ->get([
                                                    'dsuid', 
                                                    'spaid', 
                                                    'dsuestado',
                                                    
                                                    'dsuordenado',
                                                    'dsulimpio',
                                                    'dsuplanogramacategoria',
                                                    'dsuexisteprogramacion',
                                                    'dsullegomercaderia',
                                                    'dsumercaderiatiempo',
                                                    'desupedidocompleto',
                                                    'dsucheckout'
                                                ]);
                        
                        foreach($dsus as $posicionDsu => $dsu){
                            if($dsu->spaid == 1 || $dsu->spaid == 2 || $dsu->spaid == 3 ){

                                $fops = fopfotospasos::where('dsuid', $dsu->dsuid)->get(['fopid', 'fopimagen']);
                                $dsus[$posicionDsu]['fotos'] = $fops;

                            }else if($dsu->spaid == 4){
                                $paps = pappasosproductos::join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->get([
                                                            'pappasosproductos.papid',
                                                            'pro.pronombre',
                                                            'pappasosproductos.papcantidad',
                                                            'pappasosproductos.paprecepcion'
                                                        ]);
                                if(sizeof($paps) > 0){
                                    $dsus[$posicionDsu]['paps'] = $paps;
                                }else{
                                    $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                    ->where('sucid', $suc->sucid)
                                                                    ->get([
                                                                        'prsproductossucursales.prsid',
                                                                        'pro.proid',
                                                                        'pro.pronombre',
                                                                    ]);

                                    foreach($prss as $posicionPrss => $prs){
                                        $prss[$posicionPrss]['papid'] = 0;
                                        $prss[$posicionPrss]['papcantidad'] = 0;
                                        $prss[$posicionPrss]['paprecepcion'] = 0;
                                    }

                                    $dsus[$posicionDsu]['paps'] = $prss;
                                }
                            }else if($dsu->spaid == 5){

                                $prvsprincipales  = prvproveedores::where('prvprincipal', 1)
                                                                ->get([
                                                                    'prvid',
                                                                    'prvnombre'
                                                                ]);

                                $prvscompetencias = prvproveedores::where('prvprincipal', 0)
                                                                ->get([
                                                                    'prvid',
                                                                    'prvnombre'
                                                                ]);

                                foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvsprincipal->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);

                                    foreach($cpas as $posicionCpas => $cpa){

                                        $mpas = mpamarcaspasos::join('marmarcas as mar', 'mar.marid', 'mpamarcaspasos.marid')
                                                                ->where('mpamarcaspasos.prvid', $prvsprincipal->prvid)
                                                                ->where('mpamarcaspasos.dsuid', $dsu->dsuid)
                                                                ->where('mpamarcaspasos.catid', $cpa->catid)
                                                                ->get([
                                                                    'mpamarcaspasos.mpaid',
                                                                    'mpamarcaspasos.mapid',
                                                                    'mpamarcaspasos.capid',
                                                                    'mar.marid',
                                                                    'mar.marnombre',
                                                                    'mpamarcaspasos.mpaestado'
                                                                ]);
                                        
                                        foreach($mpas as $posicionMpa => $mpa){

                                            $dcps = dcpdatoscategoriaspasos::where('mpaid', $mpa->mpaid)
                                                                            ->get();

                                            foreach($dcps as $posicionDcp => $dcp){
                                                $fcps = fcpfotoscategoriaspasos::where('dcpid', $dcp->dcpid)
                                                                                ->get([
                                                                                    'fcpid',
                                                                                    'dcpid',
                                                                                    'fcpimagen'
                                                                                ]);

                                                $dcps[$posicionDcp]['fcps'] = $fcps;
                                            }
                                            
                                            $mpas[$posicionMpa]['dcps'] = $dcps;
                                        }

                                        $cpas[$posicionCpas]['mpas'] = $mpas;

                                    }
                                    $prvsprincipales[$contPrvPrincipal]['cpas'] = $cpas;
                                    
                                }

                                foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvscompetencia->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);


                                    foreach($cpas as $posicionCpas => $cpa){

                                        $mpas = mpamarcaspasos::join('marmarcas as mar', 'mar.marid', 'mpamarcaspasos.marid')
                                                                ->where('mpamarcaspasos.prvid', $prvscompetencia->prvid)
                                                                ->where('mpamarcaspasos.dsuid', $dsu->dsuid)
                                                                ->where('mpamarcaspasos.catid', $cpa->catid)
                                                                ->get([
                                                                    'mpamarcaspasos.mpaid',
                                                                    'mpamarcaspasos.mapid',
                                                                    'mpamarcaspasos.capid',
                                                                    'mar.marid',
                                                                    'mar.marnombre',
                                                                    'mpamarcaspasos.mpaestado'
                                                                ]);
                                        
                                        foreach($mpas as $posicionMpa => $mpa){

                                            $dcps = dcpdatoscategoriaspasos::where('mpaid', $mpa->mpaid)
                                                                            ->get();

                                            foreach($dcps as $posicionDcp => $dcp){
                                                $fcps = fcpfotoscategoriaspasos::where('dcpid', $dcp->dcpid)
                                                                                ->get([
                                                                                    'fcpid',
                                                                                    'dcpid',
                                                                                    'fcpimagen'
                                                                                ]);

                                                $dcps[$posicionDcp]['fcps'] = $fcps;
                                            }

                                            $mpas[$posicionMpa]['dcps'] = $dcps;
                                        }

                                        $cpas[$posicionCpas]['mpas'] = $mpas;

                                    }

                                    $prvscompetencias[$contPrvCompetencia]['cpas'] = $cpas;
                                }

                                $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;



                            }else if($dsu->spaid == 6){

                                $paps = pappasosproductos::join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->get([
                                                            'pappasosproductos.papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'pro.prosku',
                                                            'pappasosproductos.papcantidad',
                                                            'pappasosproductos.papquiebre',
                                                            'prs.prsstock'
                                                        ]);
                                if(sizeof($paps) > 0){
                                    $dsus[$posicionDsu]['paps'] = $paps;
                                }else{
                                    $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                    ->where('sucid', $suc->sucid)
                                                                    ->where('prsestado', 1)
                                                                    ->get([
                                                                        'prsproductossucursales.prsid',
                                                                        'pro.proid',
                                                                        'pro.pronombre',
                                                                        'pro.prosku',
                                                                        'prsproductossucursales.prsstock'
                                                                    ]);

                                    foreach($prss as $posicionPrss => $prs){
                                        $prss[$posicionPrss]['papid'] = 0;
                                        $prss[$posicionPrss]['papcantidad'] = 0;
                                        $prss[$posicionPrss]['papquiebre'] = false;
                                    }

                                    $dsus[$posicionDsu]['paps'] = $prss;
                                }
                            }else if($dsu->spaid == 7){
                                
                                $mecs = $mecsT;
                                $prvsprincipales = prvproveedores::where('prvprincipal', 1)
                                                                    ->get([
                                                                        'prvid',
                                                                        'prvnombre'
                                                                    ]);;
                                $prvscompetencias = prvproveedores::where('prvprincipal', 0)
                                                                    ->get([
                                                                        'prvid',
                                                                        'prvnombre'
                                                                    ]);;
                                
                                foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvsprincipal->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);

                                    foreach($cpas as $posicionCpas => $cpa){
                                        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvsprincipal->prvid )
                                                                                ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                                                ->get([
                                                                                    'mcpid',
                                                                                    'mar.marid',
                                                                                    'marnombre'
                                                                                ]);

                                        $paps = pappasosproductos::leftjoin('mecmecanicas as mec', 'mec.mecid', 'pappasosproductos.mecid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->where('prs.prvid', $prvsprincipal->prvid)
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->where('pro.catid', $cpa->catid)
                                                        ->get([
                                                            'papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'papean',
                                                            'papprecioregular',
                                                            'papmarca',
                                                            'pappromocion',
                                                            'papdescripcion',
                                                            'pappreciopromocion',
                                                            'papfechainicio',
                                                            'papfechafin',
                                                            'pappasosproductos.mecid',
                                                            'mec.mecnombre',
                                                            'papestatus',
                                                        ]);

                                        if(sizeof($paps) > 0){

                                            foreach($paps as $posicionPap => $pap){
                                                $paps[$posicionPap]['mcps'] = $mcps;
                                                $paps[$posicionPap]['mecs'] = $mecs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $paps;

                                        }else{
                                            $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                            ->where('prvid', $prvsprincipal->prvid)
                                                                            ->get([
                                                                                'prsproductossucursales.prsid',
                                                                                'pro.proid',
                                                                                'pro.pronombre',
                                                                            ]);

                                            foreach($prss as $posicionPrss => $prs){
                                                $prss[$posicionPrss]['papid']               = 0;
                                                $prss[$posicionPrss]['papean']              = "";
                                                $prss[$posicionPrss]['papprecioregular']    = "";
                                                $prss[$posicionPrss]['papmarca']            = "";
                                                $prss[$posicionPrss]['pappromocion']        = 1;
                                                $prss[$posicionPrss]['papdescripcion']      = "";
                                                $prss[$posicionPrss]['pappreciopromocion']  = "";
                                                $prss[$posicionPrss]['papfechainicio']      = "";
                                                $prss[$posicionPrss]['papfechafin']         = "";
                                                $prss[$posicionPrss]['mecid']               = 0;
                                                $prss[$posicionPrss]['mecnombre']           = "";

                                                $prss[$posicionPrss]['mcps'] = $mcps;
                                                $prss[$posicionPrss]['mecs'] = $mecs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $prss;
                                        }
                                    }
                                    $prvsprincipales[$contPrvPrincipal]['cpas'] = $cpas;
                                }

                                foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvscompetencia->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);

                                    foreach($cpas as $posicionCpas => $cpa){
                                        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvscompetencia->prvid )
                                                                                ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                                                ->get([
                                                                                    'mcpid',
                                                                                    'mar.marid',
                                                                                    'marnombre'
                                                                                ]);

                                        $paps = pappasosproductos::leftjoin('mecmecanicas as mec', 'mec.mecid', 'pappasosproductos.mecid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->where('pro.catid', $cpa->catid)
                                                        ->where('prs.prvid', $prvscompetencia->prvid)
                                                        ->get([
                                                            'papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'papean',
                                                            'papprecioregular',
                                                            'papmarca',
                                                            'pappromocion',
                                                            'papdescripcion',
                                                            'pappreciopromocion',
                                                            'papfechainicio',
                                                            'papfechafin',
                                                            'pappasosproductos.mecid',
                                                            'mec.mecnombre',
                                                            'papestatus'
                                                        ]);

                                        if(sizeof($paps) > 0){

                                            foreach($paps as $posicionPap => $pap){
                                                $paps[$posicionPap]['mcps'] = $mcps;
                                                $paps[$posicionPap]['mecs'] = $mecs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $paps;

                                        }else{
                                            $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                            ->where('prvid', $prvscompetencia->prvid)
                                                                            ->get([
                                                                                'prsproductossucursales.prsid',
                                                                                'pro.proid',
                                                                                'pro.pronombre',
                                                                            ]);

                                            foreach($prss as $posicionPrss => $prs){
                                                $prss[$posicionPrss]['papid']               = 0;
                                                $prss[$posicionPrss]['papean']              = "";
                                                $prss[$posicionPrss]['papprecioregular']    = "";
                                                $prss[$posicionPrss]['papmarca']            = "";
                                                $prss[$posicionPrss]['pappromocion']        = 1;
                                                $prss[$posicionPrss]['papdescripcion']      = "";
                                                $prss[$posicionPrss]['pappreciopromocion']  = "";
                                                $prss[$posicionPrss]['papfechainicio']      = "";
                                                $prss[$posicionPrss]['papfechafin']         = "";
                                                $prss[$posicionPrss]['mecid']               = 0;
                                                $prss[$posicionPrss]['mecnombre']           = "";

                                                $prss[$posicionPrss]['mcps'] = $mcps;
                                                $prss[$posicionPrss]['mecs'] = $mecs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $prss;
                                        }
                                    }
                                    $prvscompetencias[$contPrvCompetencia]['cpas'] = $cpas;
                                }

                                $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;

                            }else if($dsu->spaid == 8){
                                $tpes = $tpesT;
                                $arrs = $arrsT;

                                $prvsprincipales = prvproveedores::where('prvprincipal', 1)
                                                                    ->get([
                                                                        'prvid',
                                                                        'prvnombre'
                                                                    ]);
                                                                    
                                $prvscompetencias = prvproveedores::where('prvprincipal', 0)
                                                                    ->get([
                                                                        'prvid',
                                                                        'prvnombre'
                                                                    ]);

                                foreach($prvsprincipales as $contPrvPrincipal => $prvsprincipal){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvsprincipal->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);

                                    foreach($cpas as $posicionCpas => $cpa){
                                        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvsprincipal->prvid )
                                                                                ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                                                ->get([
                                                                                    'mcpid',
                                                                                    'mar.marid',
                                                                                    'marnombre'
                                                                                ]);

                                        $paps = pappasosproductos::leftjoin('tpetiposexhibicion as tpe', 'tpe.tpeid', 'pappasosproductos.tpeid')
                                                        ->leftjoin('arrarrendados as arr', 'arr.arrid', 'pappasosproductos.arrid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->where('prs.prvid', $prvsprincipal->prvid)
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->where('pro.catid', $cpa->catid)
                                                        ->get([
                                                            'papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'tpe.tpeid',
                                                            'tpe.tpenombre',
                                                            'papmarca',
                                                            'papdescripcion',
                                                            'papestatus',
                                                            'papfechainicio',
                                                            'papfechafin',
                                                            'arr.arrid',
                                                            'arr.arrnombre',
                                                            'papestado',
                                                            
                                                        ]);

                                        if(sizeof($paps) > 0){

                                            foreach($paps as $posicionPap => $pap){
                                                $paps[$posicionPap]['mcps'] = $mcps;
                                                $paps[$posicionPap]['tpes'] = $tpes;
                                                $paps[$posicionPap]['arrs'] = $arrs;


                                                $fpps = fppfotosproductospasos::where('papid', $pap->papid)
                                                                            ->get([
                                                                                'fppid',
                                                                                'fppimagen'
                                                                            ]);

                                                $paps[$posicionPap]['fpps'] = $fpps;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $paps;

                                        }else{
                                            $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                            ->where('prvid', $prvsprincipal->prvid)
                                                                            ->get([
                                                                                'prsproductossucursales.prsid',
                                                                                'pro.proid',
                                                                                'pro.pronombre',
                                                                            ]);

                                            foreach($prss as $posicionPrss => $prs){
                                                $prss[$posicionPrss]['papid']          = 0;
                                                $prss[$posicionPrss]['proid']          = $prs->proid;
                                                $prss[$posicionPrss]['pronombre']      = $prs->pronombre;
                                                $prss[$posicionPrss]['tpeid']          = 0;
                                                $prss[$posicionPrss]['tpenombre']      = "";
                                                $prss[$posicionPrss]['papmarca']       = "";
                                                $prss[$posicionPrss]['papdescripcion'] = "";
                                                $prss[$posicionPrss]['papestatus']     = true;
                                                $prss[$posicionPrss]['papfechainicio'] = "";
                                                $prss[$posicionPrss]['papfechafin']    = "";
                                                $prss[$posicionPrss]['arrid']          = 0;
                                                $prss[$posicionPrss]['arrnombre']      = "";
                                                $prss[$posicionPrss]['papestado']      = false;

                                                $prss[$posicionPrss]['fpps'] = [];
                                                
                                                $prss[$posicionPrss]['mcps'] = $mcps;
                                                $prss[$posicionPrss]['tpes'] = $tpes;
                                                $prss[$posicionPrss]['arrs'] = $arrs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $prss;
                                        }
                                    }
                                    $prvsprincipales[$contPrvPrincipal]['cpas'] = $cpas;
                                }

                                foreach($prvscompetencias as $contPrvCompetencia => $prvscompetencia){
                                    $cpas = cpacategoriaspasos::join('catcategorias as cat', 'cat.catid', 'cpacategoriaspasos.catid')
                                                                ->where('cpacategoriaspasos.prvid', $prvscompetencia->prvid)
                                                                ->where('cpacategoriaspasos.dsuid', $dsu->dsuid)
                                                                ->get([
                                                                    'cpacategoriaspasos.cpaid',
                                                                    'cpacategoriaspasos.catid',
                                                                    'cpacategoriaspasos.cpaestado',
                                                                    'cat.catnombre',
                                                                    'cat.catimagen'
                                                                ]);

                                    foreach($cpas as $posicionCpas => $cpa){
                                        $mcps = mcpmarcascategoriasproveedores::join('marmarcas as mar', 'mar.marid', 'mcpmarcascategoriasproveedores.marid')
                                                                                ->where('mcpmarcascategoriasproveedores.prvid', $prvscompetencia->prvid )
                                                                                ->where('mcpmarcascategoriasproveedores.catid', $cpa->catid)
                                                                                ->get([
                                                                                    'mcpid',
                                                                                    'mar.marid',
                                                                                    'marnombre'
                                                                                ]);

                                        $paps = pappasosproductos::leftjoin('tpetiposexhibicion as tpe', 'tpe.tpeid', 'pappasosproductos.tpeid')
                                                        ->leftjoin('arrarrendados as arr', 'arr.arrid', 'pappasosproductos.arrid')
                                                        ->join('proproductos as pro', 'pro.proid', 'pappasosproductos.proid')
                                                        ->join('prsproductossucursales as prs', 'prs.prsid', 'pappasosproductos.prsid')
                                                        ->where('prs.prvid', $prvscompetencia->prvid)
                                                        ->where('pappasosproductos.dsuid', $dsu->dsuid)
                                                        ->where('pro.catid', $cpa->catid)
                                                        ->get([
                                                            'papid',
                                                            'pro.proid',
                                                            'pro.pronombre',
                                                            'tpe.tpeid',
                                                            'tpe.tpenombre',
                                                            'papmarca',
                                                            'papdescripcion',
                                                            'papestatus',
                                                            'papfechainicio',
                                                            'papfechafin',
                                                            'arr.arrid',
                                                            'arr.arrnombre',
                                                            'papestado',
                                                            
                                                        ]);

                                        if(sizeof($paps) > 0){

                                            foreach($paps as $posicionPap => $pap){
                                                $paps[$posicionPap]['mcps'] = $mcps;
                                                $paps[$posicionPap]['tpes'] = $tpes;
                                                $paps[$posicionPap]['arrs'] = $arrs;


                                                $fpps = fppfotosproductospasos::where('papid', $pap->papid)
                                                                            ->get([
                                                                                'fppid',
                                                                                'fppimagen'
                                                                            ]);

                                                $paps[$posicionPap]['fpps'] = $fpps;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $paps;

                                        }else{
                                            $prss = prsproductossucursales::join('proproductos as pro', 'pro.proid', 'prsproductossucursales.proid')
                                                                            ->where('prvid', $prvsprincipal->prvid)
                                                                            ->get([
                                                                                'prsproductossucursales.prsid',
                                                                                'pro.proid',
                                                                                'pro.pronombre',
                                                                            ]);

                                            foreach($prss as $posicionPrss => $prs){
                                                $prss[$posicionPrss]['papid']          = 0;
                                                $prss[$posicionPrss]['proid']          = $prs->proid;
                                                $prss[$posicionPrss]['pronombre']      = $prs->pronombre;
                                                $prss[$posicionPrss]['tpeid']          = 0;
                                                $prss[$posicionPrss]['tpenombre']      = "";
                                                $prss[$posicionPrss]['papmarca']       = "";
                                                $prss[$posicionPrss]['papdescripcion'] = "";
                                                $prss[$posicionPrss]['papestatus']     = true;
                                                $prss[$posicionPrss]['papfechainicio'] = "";
                                                $prss[$posicionPrss]['papfechafin']    = "";
                                                $prss[$posicionPrss]['arrid']          = 0;
                                                $prss[$posicionPrss]['arrnombre']      = "";
                                                $prss[$posicionPrss]['papestado']      = false;

                                                $prss[$posicionPrss]['fpps'] = [];
                                                
                                                $prss[$posicionPrss]['mcps'] = $mcps;
                                                $prss[$posicionPrss]['tpes'] = $tpes;
                                                $prss[$posicionPrss]['arrs'] = $arrs;
                                            }

                                            $cpas[$posicionCpas]['paps'] = $prss;
                                        }
                                    }
                                    $prvscompetencias[$contPrvCompetencia]['cpas'] = $cpas;
                                }



                                $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipales;
                                $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetencias;
                            }else if($dsu->spaid == 9){
                                $fops = fopfotospasos::where('dsuid', $dsu->dsuid)->get(['fopid', 'fopimagen']);
                                $dsus[$posicionDsu]['fotos'] = $fops;

                                $prvsprincipalesSpa9  = prvproveedores::where('prvprincipal', 1)
                                                                        ->get([
                                                                            'prvid',
                                                                            'prvnombre'
                                                                        ]);

                                $prvscompetenciasSpa9 = prvproveedores::where('prvprincipal', 0)
                                                                        ->get([
                                                                            'prvid',
                                                                            'prvnombre'
                                                                        ]);

                                foreach($prvsprincipalesSpa9 as $contadorPrvPrincipal => $prvsprincipal){
                                    
                                    $maps = mapmarcasproveedores::join('marmarcas as mar', 'mar.marid', 'mapmarcasproveedores.marid')
                                                                ->where('prvid', $prvsprincipal->prvid)
                                                                ->get([
                                                                    'mapid',
                                                                    'mar.marid',
                                                                    'mar.marnombre'
                                                                ]);


                                    $mpps = mppmaterialespopspasos::join('mtpmaterialespops as mtp', 'mtp.mtpid', 'mppmaterialespopspasos.mtpid')
                                                            ->where('dsuid', $dsu->dsuid)
                                                            ->where('prvid', $prvsprincipal->prvid)
                                                            ->get([
                                                                'mppid',
                                                                'mtp.mtpid',
                                                                'mtpnombre',
                                                                'mtpimagen',
                                                                'mppmarca',
                                                                'mppproducto',
                                                                'mppdescripcion'
                                                            ]);

                                    foreach($mpps as $posicionMpp => $mpp){
                                        $fmps = fmpfotosmaterialespops::where('mppid', $mpp->mppid)
                                                                        ->get(['fmpid', 'fmpimagen']);

                                        $mpps[$posicionMpp]['fmps'] = $fmps;
                                        $mpps[$posicionMpp]['maps'] = $maps;
                                    }
                                    $prvsprincipalesSpa9[$contadorPrvPrincipal]['mpps'] = $mpps;
                                }

                                foreach($prvscompetenciasSpa9 as $contadorPrvCompetencia => $prvcompetencia){
                                    
                                    $maps = mapmarcasproveedores::join('marmarcas as mar', 'mar.marid', 'mapmarcasproveedores.marid')
                                                                ->where('prvid', $prvcompetencia->prvid)
                                                                ->get([
                                                                    'mapid',
                                                                    'mar.marid',
                                                                    'mar.marnombre'
                                                                ]);


                                    $mpps = mppmaterialespopspasos::join('mtpmaterialespops as mtp', 'mtp.mtpid', 'mppmaterialespopspasos.mtpid')
                                                            ->where('dsuid', $dsu->dsuid)
                                                            ->where('prvid', $prvcompetencia->prvid)
                                                            ->get([
                                                                'mppid',
                                                                'mtp.mtpid',
                                                                'mtpnombre',
                                                                'mtpimagen',
                                                                'mppmarca',
                                                                'mppproducto',
                                                                'mppdescripcion'
                                                            ]);

                                    foreach($mpps as $posicionMpp => $mpp){
                                        $fmps = fmpfotosmaterialespops::where('mppid', $mpp->mppid)
                                                                        ->get(['fmpid', 'fmpimagen']);

                                        $mpps[$posicionMpp]['fmps'] = $fmps;
                                        $mpps[$posicionMpp]['maps'] = $maps;
                                    }

                                    $prvscompetenciasSpa9[$contadorPrvCompetencia]['mpps'] = $mpps;

                                }

                                $dsus[$posicionDsu]['prvsprincipales'] = $prvsprincipalesSpa9;
                                $dsus[$posicionDsu]['prvscompetencia'] = $prvscompetenciasSpa9;

                            }else if($dsu->spaid == 10){

                                $tpcs = tpctiposcomprobantes::where('tpcnombre', '!=', 'Otros')->get(['tpcid', 'tpcnombre']);

                                foreach($tpcs as $posicionTpc => $tpc){
                                    $cops = copcomprobantespasos::where('dsuid', $dsu->dsuid)
                                                                ->where('tpcid', $tpc->tpcid)
                                                                ->get([
                                                                    'copid',
                                                                    'copnumero'
                                                                ]);

                                    $tpcs[$posicionTpc]['cops'] = $cops;
                                }

                                $dsus[$posicionDsu]['tpcs'] = $tpcs;

                                $tpcsOtros = tpctiposcomprobantes::where('tpcnombre', 'Otros')->first(['tpcid', 'tpcnombre']);

                                if($tpcsOtros){
                                    $copsOtros = copcomprobantespasos::where('dsuid', $dsu->dsuid)
                                                            ->where('tpcid', $tpcsOtros->tpcid)
                                                            ->get([
                                                                'copid',
                                                                'copnumero'
                                                            ]);
                                    foreach($copsOtros as $posicionCopO => $copO){

                                        $cacs = caccantidadescomprobantes::where('copid', $copO->copid)->get(['cacid', 'caccodigoean', 'caccantidad']);

                                        $copsOtros[$posicionCopO]['cacs'] = $cacs;

                                    }

                                    $tpcsOtros->cops = $copsOtros;
                                }
                                
                                $dsus[$posicionDsu]['tpcsOtros'] = $tpcsOtros;
                            }
                        }

                        $paus[$posicionpau]['dsus'] = $dsus;
                        

                    }

                    $sucs[$posicionsuc]['paus'] = $paus;
                }
            }

            
        }else{
            $respuesta = false;
            $mensaje = "Lo sentimos, no se encontraron tiendas registradas";
        }

        return response()->json([
            'respuesta'      => $respuesta,
            'mensaje'        => $mensaje,
            'datos'          => $datos,
            'mensajeDetalle' => $mensajeDetalle,
            'mensajedev'     => $mensajedev
        ]);

    }
}
