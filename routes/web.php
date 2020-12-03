<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/cargar/tiendas', 'sistema\CargarData\Suc\SucursalesController@CargarDataSucursales');
$router->get('/cargar/usuarios', 'sistema\CargarData\Usu\UsuariosController@CargarDataUsuarios');
$router->get('/cargar/usuariostiendas', 'sistema\CargarData\Uss\UssSucursalesUsuariosController@CargarDataUss');
$router->get('/cargar/productos', 'sistema\CargarData\Pro\ProductosController@CargarDataProductosMoises');
$router->get('/cargar/stock', 'sistema\CargarData\Pro\StockProductosController@CargarDataStockProducto');
$router->get('/cargar/stockUnirversal', 'sistema\CargarData\Pro\StockProductosController@CargarDataStockProductosUniversal');
$router->get('/cargar/marcasproveedores', 'sistema\CargarData\Map\MapMarcasProveedoresController@MapMarcasProveedores');
$router->get('/cargar/mcp', 'sistema\CargarData\Mcp\McpMarcasCategoriasProveedoresController@McpCargarData');



$router->post('/api/login', 'sistema\Login\LoginController@login');
$router->post('/api/sucursales', 'sistema\Suc\Mostrar\SucSucursalesController@MostrarSucursales');
$router->post('/api/agregar/registroSucursal', 'sistema\Res\Agregar\ResRegistrosSucursalesController@AgregarRes');
$router->post('/api/editar/ingresoTienda', 'sistema\Dsu\Editar\DsuDatosSubPasosController@EditarDsu');

$router->post('/api/editar/ordenGondola/estadoInicial', 'sistema\Dsu\Editar\DsuPasDos\SpaUno\SpaEstadoInicialController@EditarSpaEstadoInicial');
$router->post('/api/editar/ordenGondola/estadoFinal', 'sistema\Dsu\Editar\DsuPasDos\SpaDos\SpaEstadoFinalController@EditarSpaEstadoFinal');

$router->post('/api/editar/ordenGondola/solicitarFaltanteAltillo', 'sistema\Dsu\Editar\DsuPasDos\SpaTres\SpaSolicitarFaltanteAltilloController@EditarSpaSolicitarFaltanteAltillo');
$router->post('/api/editar/ordenGondola/medidasGondola/cpasCrear', 'sistema\Cpa\Crear\CpaCategoriasPasosController@CrearCategoriasPaso');

$router->post('/api/editar/ordenGondola/medidasGondola/dcpCrear', 'sistema\Dsu\Crear\DsuPasDos\SpaCuatro\SpaMedidaGondolasController@CrearDatosCategoriasPasos');
$router->post('/api/editar/ordenGondola/medidasGondola', 'sistema\Dsu\Editar\DsuPasDos\SpaCuatro\SpaMedidaGondolaController@EditarSpaMedidaGondola');



$router->post('/api/editar/registroInformacion/validarStock', 'sistema\Dsu\Editar\DsuPasTres\SpaUno\SpaValidarStockController@EditarSpaValidarStock');
$router->post('/api/editar/registroInformacion/ldp', 'sistema\Dsu\Editar\DsuPasTres\SpaDos\SpaLdpController@EditarSpaLdp');
$router->post('/api/crear/cpa/cpaparappaps', 'sistema\Cpa\Crear\CpaCrearPapsController@CrearCategoriasPasoParaPaps');
$router->post('/api/crear/cpa/cpaparappapsdelde', 'sistema\Cpa\Crear\CpaCrearPapsParaLdeController@CrearCpaParaPapsDeLde');
$router->post('/api/editar/registroInformacion/lde', 'sistema\Dsu\Editar\DsuPasTres\SpaTres\SpaLdeController@EditarSpaLde');


$router->post('/api/crear/exhibicion/mpp', 'sistema\Dsu\Crear\DsuPasCuatro\SpaDefault\CrearMppsController@CrearMppsParaExhibicion');
$router->post('/api/editar/exhibicion/fops', 'sistema\Dsu\Editar\DsuPasCuatro\SpaDefault\EditarFopsExhibicionController@EditarFopsExhibicion');
$router->post('/api/editar/exhibicion/mpp', 'sistema\Dsu\Editar\DsuPasCuatro\SpaDefault\EditarMaterialPopController@EditarMaterialPop');

$router->post('/api/editar/entregaMercaderia', 'sistema\Dsu\Editar\DsuPasNueve\SpaDefault\EditarEntregaMercaderiaController@EditarEntregaMercaderia');

$router->post('/api/editar/salidaTienda', 'sistema\Dsu\Editar\DsuPasSeis\SpaDefault\EditarSalidaTiendaController@EditarSalidaTienda');

$router->post('/api/cerrarRegistrosTiendas', 'sistema\Res\Editar\CerrarRegistrosTiendasController@CerrarRegistroTiendas');

$router->get('/api/prueba/dan', 'sistema\pruebaController@ProveedoresSkus');
$router->get('/api/prueba/eliminarduplicados', 'sistema\pruebaController@EliminarDuplicadosStockSucursal');
$router->get('/api/prueba/eliminarduplicados/proveedores', 'sistema\pruebaController@EliminarDuplicadosStockProveedor');
$router->get('/api/prueba/encontrarduplicados', 'sistema\pruebaController@EncontrarProductosDuplicados');



$router->get('/api/prueba/eliminarDuplicadosPaps', 'sistema\pruebaController@EliminarDuplicadosPaps');