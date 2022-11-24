<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './controllers/LogController.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $idMesa = $parametros['idMesa'];
      $tiempo = $parametros['tiempo'];
      $datosProducto = $parametros['datosProducto'];
      $tipoProducto = $parametros['tipoProducto'];
      $cantidad = $parametros['cantidad'];
      $foto = $_FILES['foto'];
      $codigo = Pedido::CrearCodigo();
      $fecha = new datetime("now");
      $estado = "Pendiente";
      $puesto = "Mozo";

      $auxMesa = Mesa::obtenerMesa($idMesa);
     
      $pd = new Pedido();
      $pd->codigo = $codigo;
      $pd->idMesa = $idMesa;
      $pd->tiempo = $tiempo;
      $pd->fecha = $fecha->format('Y-m-d');
      $pd->estado = $estado;
      if($estado == "Pendiente" || $estado == "En preparacion" || $estado == "Listo para servir")
      {
        $eMesa = 'Esperando';
        $e = MesaController::ActualizarMesa($eMesa, $idMesa);
      }
      else if($estado == 'Servido')
      {
        $eMesa = 'Comiendo';
        $e = MesaController::ActualizarMesa($eMesa, $idMesa);
      }
      else if($estado == "pagado")
      {
        $eMesa = "Cerrada";
        $e = MesaController::ActualizarMesa($eMesa, $idMesa);
      }
      $pd->cantidad = $cantidad;
      $pd->datosProducto = $datosProducto;
      $pd->tipoProducto = Pedido::ValidarTipo($tipoProducto);

      if($tipoProducto == 'Comida')
      {
        $usr = rand(7,8);
        $nombre = Usuario::obtenerNombre($usr);
        $pd->usuario = $nombre;
        $puesto = Usuario::obtenerPuesto($usr);
        $pd->puesto = $puesto;
      }
      else if($tipoProducto == 'Cerveza')
      {
        $usr = rand(9,10);
        $nombre = Usuario::obtenerNombre($usr);
        $pd->usuario = $nombre;
        $puesto = Usuario::obtenerPuesto($usr);
        $pd->puesto = $puesto;
      }
      else if($tipoProducto == 'Trago')
      {
        $usr = rand(11,12);
        $nombre = Usuario::obtenerNombre($usr);
        $pd->usuario = $nombre;
        $puesto = Usuario::obtenerPuesto($usr);
        $pd->puesto = $puesto;
      }
      else if($tipoProducto == 'Comida' || $tipoProducto == 'Cerveza' || $tipoProducto == 'Trago')
      {
        $usr = rand(4,6);
        $nombre = Usuario::obtenerNombre($usr);
        $pd->usuario = $nombre;
        $puesto = Usuario::obtenerPuesto($usr);
        $pd->puesto = $puesto;
      }
      else
      {
        $usr = rand(1,3);
        $nombre = Usuario::obtenerNombre($usr);
        $pd->usuario = $nombre;
        $puesto = Usuario::obtenerPuesto($usr);
        $pd->puesto = $puesto;
      }
        

      if($foto['size']>0)
      {
        $img = $pd->GuardarFoto($_FILES['foto']);
      }
      else
      {
        $img = "La foto no existe";
      }

      //Producto
      if(Pedido::ValidarProducto($datosProducto))
      {
        $pd->datosProducto = $datosProducto;
        $t = Producto::obtenerProducto($datosProducto);
        $pd->total = $t->precio * $pd->cantidad;
        $auxMesa->cuenta = $auxMesa->cuenta + $pd->total;
        Mesa::cargarCuentaMesa($idMesa,$auxMesa->cuenta);
      }

      $creacion = $pd->crearPedido();

      LogController::CargarUno("pedidos",$codigo,$nombre,"Cargar datos","Datos de un pedido");

      if($creacion > 0)
      {
        $c = " Codigo: ". $pd->codigo;

        $payload = json_encode(array("mensaje" => "Pedido creado con exito". $c));

        $pd->Mostrar();
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Error al crear el pedido"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function AgregarProducto($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $tipoProducto = $parametros['tipoProducto'];
      $cantidad = $parametros['cantidad'];
      $tiempo = $parametros['tiempo'];
      $fecha = new datetime("now");
      $estado = 'Pendiente';

      if($parametros != NULL){
        $pd = Pedido::obtenerPedido($codigo);
        $pd->cantidad = $cantidad;
        $pd->tiempo = $tiempo;
        $pd->estado = $estado;
        $pd->fecha = $fecha->format('Y-m-d');
        $pd->tipoProducto = Pedido::ValidarTipo($tipoProducto);

        if($tipoProducto == 'Comida')
        {
          $usr = rand(7,8);
          $nombre = Usuario::obtenerNombre($usr);
          $pd->usuario = $nombre;
          $puesto = Usuario::obtenerPuesto($usr);
          $pd->puesto = $puesto;
        }
        else if($tipoProducto == 'Cerveza')
        {
          $usr = rand(9,10);
          $nombre = Usuario::obtenerNombre($usr);
          $pd->usuario = $nombre;
          $puesto = Usuario::obtenerPuesto($usr);
          $pd->puesto = $puesto;
        }
        else if($tipoProducto == 'Trago')
        {
          $usr = rand(11,12);
          $nombre = Usuario::obtenerNombre($usr);
          $pd->usuario = $nombre;
          $puesto = Usuario::obtenerPuesto($usr);
          $pd->puesto = $puesto;
        }
        else if($tipoProducto == 'Comida' || $tipoProducto == 'Cerveza' || $tipoProducto == 'Trago')
        {
          $usr = rand(4,6);
          $nombre = Usuario::obtenerNombre($usr);
          $pd->usuario = $nombre;
          $puesto = Usuario::obtenerPuesto($usr);
          $pd->puesto = $puesto;
        }
        else
        {
          $usr = rand(1,3);
          $nombre = Usuario::obtenerNombre($usr);
          $pd->usuario = $nombre;
          $puesto = Usuario::obtenerPuesto($usr);
          $pd->puesto = $puesto;
        }
        
        $p = new Producto(); 
        $p->nombre = $parametros['producto'];
        if(Pedido::ValidarProducto($p))
        {
          $nombre = $p->nombre;
          $pd->datosProducto = $nombre;
          $t = Producto::obtenerProducto($nombre);
          $pd->total = $t->precio * $pd->cantidad;
        };

        $creacion = $pd->crearPedido();

        LogController::CargarUno("pedidos",$codigo,$nombre,"Cargar datos","Datos de un pedido");
        
          if($creacion > 0){
            $payload = json_encode(array("mensaje" => "Producto agregado con exito!"."Codigo de Pedido: ". $pd->codigo));
            $pd->Mostrar();
          } else {
            $payload = json_encode(array("Error" => "Faltan datos!"));
          }
      } else {
        $payload = json_encode(array("Error" => "El producto no se pudo agregar!"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function SacarFoto($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $foto = $_FILES['foto'];

      $pd = Pedido::obtenerPedido($codigo);
      
      if($foto['size']>0)
      {
        $img = $pd->GuardarFoto($_FILES['foto']);
        $payload = json_encode(array("mensaje" => "Foto guardada con exito!"));
        LogController::CargarUno("pedidos",$codigo,$pd->usuario,"Sacar Foto","Foto de un pedido");
      }
      else
      {
        $img = "La foto no existe";
      }

      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      // Buscamos Pedido por id
      $pd = $args['codigo'];
      $pedido = Pedido::obtenerPedido($pd);
      $payload = json_encode($pedido);

      LogController::CargarUno("pedidos",$pd,0,"Obtener datos","Datos de un pedido");

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendiente($request, $response, $args)
    {
      $tipo = $args['tipo'];

      if($tipo == "Cervecero"){

        $pedidos = Pedido::obtenerPedidoPendiente("Cervecero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cerveceros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Cocinero"){

        $pedidos = Pedido::obtenerPedidoPendiente("Cocinero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cocineros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Bartender"){

        $pedidos = Pedido::obtenerPedidoPendiente("Bartender");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los bartender"));
        $response->getBody()->write($payload);
       
      }else if ($tipo == "Socio"){

        $pedidos = Pedido::obtenerTodos();
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de todos los pedidos"));
        $response->getBody()->write($payload);
      }else if ($tipo == "Mozo"){
        $pedidos = Pedido::obtenerPedidoPendiente("Mozo");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de pedidos pendientes"));
        $response->getBody()->write($payload);
      }else{
        $payload = json_encode(array("mensaje" => "No hay pedidos pendientes o usted no tiene los accesos correspondientes"));
        $response->getBody()->write($payload);
        
      }
      LogController::CargarUno("pedidos",0,0,"Pendientes","Obtener pedidos pendientes");
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEnPreparacion($request, $response, $args)
    {
      $tipo = $args['tipo'];

      if($tipo == "Cervecero"){

        $pedidos = Pedido::obtenerPedidoenPreparacion("Cervecero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cerveceros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Cocinero"){

        $pedidos = Pedido::obtenerPedidoenPreparacion("Cocinero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cocineros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Bartender"){

        $pedidos = Pedido::obtenerPedidoenPreparacion("Bartender");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los bartender"));
        $response->getBody()->write($payload);
       
      }else if ($tipo == "Socio"){

        $pedidos = Pedido::obtenerTodos();
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de todos los pedidos"));
        $response->getBody()->write($payload);
      }else if ($tipo == "Mozo"){
        $pedidos = Pedido::obtenerPedidoenPreparacion("Mozo");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de pedidos En preparacion"));
        $response->getBody()->write($payload);
      }else{
        $payload = json_encode(array("mensaje" => "No hay pedidos En preparacion o usted no tiene los accesos correspondientes"));
        $response->getBody()->write($payload);
        
      }
      LogController::CargarUno("pedidos",0,0,"En preparacion","Obtener pedidos En preparacion");
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerListos($request, $response, $args)
    {
      $tipo = $args['tipo'];

      if($tipo == "Mozo")
      {
        $pedidos = Pedido::obtenerPedidoListo();
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de pedidos listos para servir"));
        $response->getBody()->write($payload);
      }else{
        $payload = json_encode(array("mensaje" => "No hay pedidos listos o usted no tiene los accesos correspondientes"));
        $response->getBody()->write($payload);
        
      }
      LogController::CargarUno("pedidos",0,0,"Listos","Obtenes pedidos listos");
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function VerDemora($request, $response, $args)
    {
      $demora = array();
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $numero = $parametros['numero'];     
      
      $pedido = Pedido::obtenerTiempo($codigo);
      $mesa = Mesa::obtenerMesa($numero);
      array_push($demora, $pedido, $mesa);

      $payload = json_encode($demora);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function PedidosDemora($request, $response, $args)
    {
      $pedido = Pedido::obtenerTiempoPedidos();
      $payload = json_encode($pedido);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorPuesto($request, $response, $args)
    {
      $tipo = $args['tipo'];

      if($tipo == "Cervecero"){

        $pedidos = Pedido::obtenerPedidoPuesto("Cervecero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cerveceros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Cocinero"){

        $pedidos = Pedido::obtenerPedidoPuesto("Cocinero");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los cocineros"));
        $response->getBody()->write($payload);
        
      }else if ($tipo == "Bartender"){

        $pedidos = Pedido::obtenerPedidoPuesto("Bartender");
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de los bartender"));
        $response->getBody()->write($payload);
       
      }else if ($tipo == "Socio"){

        $pedidos = Pedido::obtenerTodos();
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de todos los pedidos"));
        $response->getBody()->write($payload);
      }else if ($tipo == "Mozo"){

        $pedidos = Pedido::obtenerPedidoListo();
        $lista = Pedido::Listar($pedidos);
        $payload = json_encode(array("Listado de pedidos listos para servir"));
        $response->getBody()->write($payload);
        

      }else{
        $payload = json_encode(array("mensaje" => "Error, no tenes los accesos necesarios"));
        $response->getBody()->write($payload);
        
      }
      LogController::CargarUno("pedidos",0,0,"Traer por puesto","Datos de un pedido");
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Pedido::obtenerTodos();
      $payload = json_encode(array("listaPedido" => $lista));

      LogController::CargarUno("pedidos",0,0,"Obtener datos","Datos de todos los pedidos");

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $pedidoId = $parametros['codigo'];
      Pedido::modificarPedido($pedidoId);

      LogController::CargarUno("pedidos",$pedidoId,0,"Modificar datos","Modificacion de un pedido");

      $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $pedidoId = $parametros['codigo'];
      Pedido::borrarPedido($pedidoId);

      LogController::CargarUno("pedidos",$pedidoId,0,"Borrar datos","Baja de un pedido");

      $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ActualizarEstado($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $codigo = $parametros['codigo'];
      $estado = $parametros['estado'];
      $puesto = $parametros['puesto'];
      $tiempo = $parametros['tiempo'];

      LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos","Actualizacion de un pedido");

      if(Pedido::obtenerPedido($codigo) != null)
      {
          if($puesto == "Cervecero")
          {
            if(Pedido::actualizarPedido($codigo, $estado, "Cervecero", $tiempo))
            {
              $payload = json_encode(array("mensaje" => "El Cervecero actualizo el pedido"));
              if($estado == "Listo para servir" && $tiempo > 5)
              {
                $aviso = "Entregado tarde";
              }
              else
              {
                $aviso = "Entregado a tiempo";
              }
              LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos", "($aviso)");
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Error al actualizar"));
            }

          }
          else if($puesto == "Cocinero")
          {
            if(Pedido::actualizarPedido($codigo, $estado,"Cocinero", $tiempo))
            {
              $payload = json_encode(array("mensaje" => "El Cocinero actualizo el pedido"));
              if($estado == "Listo para servir" && $tiempo > 5)
              {
                $aviso = "Entregado tarde";
              }
              else
              {
                $aviso = "Entregado a tiempo";
              }
              LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos", "($aviso)");
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Error al actualizar"));
            }
          }
          else if ($puesto == "Bartender")
          {
            if(Pedido::actualizarPedido($codigo, $estado, "Bartender", $tiempo))
            {
              $payload = json_encode(array("mensaje" => "El Bartender actualizo el pedido"));
              if($estado == "Listo para servir" && $tiempo > 5)
              {
                $aviso = "Entregado tarde";
              }
              else
              {
                $aviso = "Entregado a tiempo";
              }
              LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos", "($aviso)");
            }
            else
            {
              $payload = json_encode(array("mensaje" => "error al actualizar"));
            }

          }
          else if ($puesto == "Mozo")
          {
            if(Pedido::actualizarPedido($codigo, $estado, "Mozo", $tiempo))
            {
              $payload = json_encode(array("mensaje" => "El Mozo actualizo el pedido"));
              if($estado == "Listo para servir" && $tiempo > 5)
              {
                $aviso = "Entregado tarde";
              }
              else
              {
                $aviso = "Entregado a tiempo";
              }
              LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos", "($aviso)");
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Error al actualizar el pedido"));
            }
          }
          else if ($puesto == "Socio")
          {
            if(Pedido::actualizarPedido($codigo, $estado, "Socio", $tiempo))
            {
              $payload = json_encode(array("mensaje" => "El Socio actualizo el pedido"));
              if($estado == "Listo para servir" && $tiempo > 5)
              {
                $aviso = "Entregado tarde";
              }
              else
              {
                $aviso = "Entregado a tiempo";
              }
              LogController::CargarUno("pedidos",$codigo,$estado,"Actualizar datos", "($aviso)");
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Error al actualizar el pedido"));
            }
          }
          else
          {
            $payload = json_encode(array("mensaje" => "No tenes los accesos necesarios"));
          }
          
          $response->getBody()->write($payload);
          return $response->withHeader('Content-Type', 'application/json');
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Error ID no encuentrado" ));
          
          $response->getBody()->write($payload);
          return $response->withHeader('Content-Type', 'application/json');
        }
    }
}