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
  require_once './models/JsonWebToken.php';
  require_once './middlewares/AutentificadorJWT.php';

  require_once './models/Cliente.php';
  require_once './models/Mesa.php';
  require_once './models/Pedido.php';
  require_once './models/Producto.php';
  require_once './models/Usuario.php';


  require_once './controllers/ClienteController.php';
  require_once './controllers/MesaController.php';
  require_once './controllers/PedidoController.php';
  require_once './controllers/ProductoController.php';
  require_once './controllers/UsuarioController.php';


  // Load ENV
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  // Instantiate App
  $app = AppFactory::create();

  // Add error middleware
  $app->addErrorMiddleware(true, true, true);

  $app->group('/cliente', function(RouteCollectorProxy $group){
    $group->post('[/]', \ClienteController::class . ':CargarUno');
    $group->get('[/]', \ClienteController::class . ':TraerTodos');
    $group->get('/{id}', \ClienteController::class . ':TraerUno');
  });

  $app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
  });

  $app->group('/mesa', function(RouteCollectorProxy $group){
    $group->get('[/]', \MesaController::class. ':TraerTodos');
    $group->get('/{id}', \MesaController::class. ':TraerUno');
    $group->post('[/]', \MesaController::class. ':CargarUno');
  });

  $app->group('/producto', function(RouteCollectorProxy $group){
    $group->get('[/]', \ProductoController::class. ':TraerTodos');
    $group->get('/{id}', \ProductoController::class. ':TraerUno');
    $group->post('[/]', \ProductoController::class. ':CargarUno');
  });

  $app->group('/pedido', function(RouteCollectorProxy $group){
    $group->get('[/]', \PedidoController::class. ':TraerTodos');
    $group->get('/{pedido}', \PedidoController::class. ':TraerUno');
    $group->post('[/]', \PedidoController::class. ':CargarUno');
  });

  $app->run();

?>
