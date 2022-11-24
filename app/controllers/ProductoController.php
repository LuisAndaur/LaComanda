<?php
require_once './models/Producto.php';
require_once './controllers/LogController.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];

        // Creamos el Producto
        $produc = new Producto();
        $produc->nombre = $nombre;
        $produc->precio = $precio;
        $produc->tipo = Producto::ValidarTipo($tipo);
        $produc->perfilEmpleado = 0;

        if($tipo == 'Comida')
        {
          $produc->puesto = 'Cocinero';
          $produc->idPuesto = Producto::ValidarPuesto($produc->puesto);
          $produc->perfilEmpleado = rand(7,8);
        }
        else if($tipo == 'Cerveza')
        {
          $produc->puesto = 'Cervecero';
          $produc->idPuesto = Producto::ValidarPuesto($produc->puesto);
          $produc->perfilEmpleado = rand(9,10);
        }
        else if($tipo == 'Trago')
        {
          $produc->puesto = 'Bartender';
          $produc->idPuesto = Producto::ValidarPuesto($produc->puesto);
          $produc->perfilEmpleado = rand(11,12);
        }
        else if($tipo == 'Comida' || $tipo == 'Cerveza' || $tipo == 'Trago')
        {
          $produc->puesto = 'Mozo';
          $produc->idPuesto = Producto::ValidarPuesto($produc->puesto);
          $produc->perfilEmpleado = rand(4,6);
        }
        else
        {
          $produc->puesto = 'Socio';
          $produc->idPuesto = Producto::ValidarPuesto($produc->puesto);
          $produc->perfilEmpleado = rand(1,3);
        }

        $produc->crearProducto();

        LogController::CargarUno("productos",$produc->nombre,$produc->puesto,"Cargar datos","Datos de un producto");

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $produc->Mostrar();

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Producto por id
        $produc = $args['idProduc'];
        $producto = Producto::obtenerProducto($produc);
        $payload = json_encode($producto);

        LogController::CargarUno("productos",$producto->nombre,$produc,"Obtener datos","Datos de un producto");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerSector($request, $response, $args)
    {
      $produc = $args['puesto'];
      $producto = Producto::obtenerSectorProducto($produc);
      $payload = json_encode($producto);

      LogController::CargarUno("productos",$producto->nombre,$produc,"Obtener datos","Datos de un producto de un sector");

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        LogController::CargarUno("productos",0,0,"Obtener datos","Datos de todos los productos");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoId = $parametros['idProduc'];
        Producto::modificarProducto($productoId);

        LogController::CargarUno("productos",0,$productoId,"Modificar datos","Modificacion de un producto");

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoId = $parametros['idProduc'];
        Producto::borrarProducto($productoId);

        LogController::CargarUno("productos",0,$productoId,"Borrar datos","Baja de un producto");

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}