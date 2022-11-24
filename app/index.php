<?php

  error_reporting(-1);
  ini_set('display_errors', 1);

  use Psr\Http\Message\ResponseInterface as Response;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface;
  use Slim\Factory\AppFactory;
  use Slim\Routing\RouteCollectorProxy;
  use Slim\Routing\RouteContext;

  require __DIR__ . '/../vendor/autoload.php';
  require_once './interfaces/IApiUsable.php';
  require_once './db/AccesoDatos.php';
  require_once './middlewares/AutentificadorJWT.php';
  require_once './middlewares/AuthJWT.php';

  require_once './models/Mesa.php';
  require_once './models/Pedido.php';
  require_once './models/Producto.php';
  require_once './models/Usuario.php';

  require_once './controllers/LoginController.php';
  require_once './controllers/MesaController.php';
  require_once './controllers/UsuarioController.php';
  require_once './controllers/ProductoController.php';
  require_once './controllers/PedidoController.php';
  require_once './controllers/EncuestaController.php';
  require_once './controllers/HorariosController.php';

  // Load ENV
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  // Instantiate App
  $app = AppFactory::create();

  // Add error middleware
  $app->addErrorMiddleware(true, true, true);

  $app->post('/login', \LoginController::class . ':login');

  $app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{id}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('/{id}', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  })->add(\AutentificadorJWT::class . ':verificarToken')->add(\AutentificadorJWT::class . ':verificarRolSocio');

  $app->get('/horariosLogin', \HorariosController::class . ':TraerTodos')->add(\AutentificadorJWT::class . ':verificarToken')->add(\AutentificadorJWT::class . ':verificarRolSocio');

  $app->group('/mesas', function(RouteCollectorProxy $group){
    $group->get('[/]', \MesaController::class. ':TraerTodos');
    $group->get('/estadoMesas', \MesaController::class . ':MesaEstado')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->get('/{numero}', \MesaController::class. ':TraerUno');
    $group->post('[/]', \MesaController::class. ':CargarUno')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->put('/actualizarMesa', \MesaController::class. ':ActualizarMesa')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('/actualizarEstado', \MesaController::class. ':ActualizarEstado')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('/obtenerCuenta', \MesaController::class . ':obtenerCuenta')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('/cerrarMesa', \MesaController::class. ':ActualizarEstado')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->delete('/borrar', \MesaController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  })->add(\AutentificadorJWT::class . ':verificarToken');

  $app->group('/producto', function(RouteCollectorProxy $group){
    $group->get('[/]', \ProductoController::class. ':TraerTodos');
    $group->get('/{id}', \ProductoController::class. ':TraerUno');
    $group->post('[/]', \ProductoController::class. ':CargarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->put('/modificar', \ProductoController::class . ':ModificarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->delete('/borrar', \ProductoController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
  });

  $app->group('/pedidos', function(RouteCollectorProxy $group){
    $group->get('[/]', \PedidoController::class. ':TraerTodos')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->get('/traerListos/{tipo}', \PedidoController::class . ':TraerListos')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->get('/pedidosDemora', \PedidoController::class . ':PedidosDemora')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->get('/traerPorPuesto/{tipo}', \PedidoController::class . ':TraerPorPuesto')->add(\AutentificadorJWT::class . ':verificarToken');
    $group->get('/traerPendiente/{tipo}', \PedidoController::class . ':TraerPendiente')->add(\AutentificadorJWT::class . ':verificarToken');
    $group->get('/traerEnPreparacion/{tipo}', \PedidoController::class . ':TraerEnPreparacion')->add(\AutentificadorJWT::class . ':verificarToken');
    $group->get('/{codigo}', \PedidoController::class. ':TraerUno')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('[/]', \PedidoController::class. ':CargarUno')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->delete('/borrar', \PedidoController::class . ':BorrarUno')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->post('/actualizar', \PedidoController::class . ':ActualizarEstado')->add(\AutentificadorJWT::class . ':verificarToken');
    $group->post('/actualizarMozo', \PedidoController::class . ':ActualizarEstadoMozo');
    $group->post('/foto', \PedidoController::class . ':SacarFoto')->add(\AutentificadorJWT::class . ':verificarRolMozo');
    $group->post('/demora', \PedidoController::class . ':VerDemora');
    //$group->post('/agregar', \PedidoController::class . ':AgregarProducto')->add(\AutentificadorJWT::class . ':verificarRolMozo');
  });

  $app->group('/encuesta', function (RouteCollectorProxy $group)
  {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos');
    $group->get('/mejoresComentarios', \EncuestaController::class . ':MejoresComentarios')->add(\AutentificadorJWT::class . ':verificarRolSocio');
    $group->get('/{id}', \EncuestaController::class . ':TraerUno');
    $group->post('[/]', \EncuestaController::class . ':CargarUno');
  });

  $app->run();

?>
