<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/LogController.php';
require_once './controllers/FacturacionController.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $numero = Mesa::CrearNumero();
        $nombre = $parametros['nombre'];
        $estadoMesa = "Esperando";
        $cuenta = 0;
        
        $mesa = new Mesa();
        $mesa->numero = $numero;
        $mesa->estadoMesa = $estadoMesa;
        $mesa->cuenta = $cuenta;
        if(Mesa::ValidarUser($nombre))
        {
          $mesa->nombre = $nombre;
          $mesa->crearMesa();

          LogController::CargarUno("mesas",$mesa->numero,$mesa->nombre,"Cargar una mesa","Datos de una mesa");
  
          $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
          $mesa->Mostrar();
        }
        else
        {
          $payload = json_encode(array("mensaje" => "Error al crear la mesa"));
        }


        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MesaEstado($request, $response, $args)
    {
      $estado = Mesa::obtenerEstados();
      $payload = json_encode($estado);

      LogController::CargarUno("mesas",'estado',0,"Obtener datos","Estado de una mesa");

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos mesa por numero
        $idMesa = $args['id'];

        $unaMesa = Mesa::obtenerMesa($idMesa);
        $payload = json_encode($unaMesa);

        LogController::CargarUno("mesas",$unaMesa->numero,$unaMesa->nombre,"Listar datos","Datos de una mesa");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesa" => $lista));

        LogController::CargarUno("mesas",'Todos',count($lista),"Listar datos","Datos de todas las mesas");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $idMesa = $args['id'];
        $parametros = $request->getParsedBody(); 
        $nombre = $parametros['nombre'];
        $estado = $parametros['estado'];

        $mesa = Mesa::obtenerMesa($idMesa);

        $mesa->nombre = $nombre;
        $mesa->estadoMesa = $estado;

        Mesa::modificarMesa($mesa);

        LogController::CargarUno("mesas",$mesa->numero,$mesa->nombre,"Modificar datos","Modificacion de una mesa");

        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaId = $args['id'];
        $mesa = Mesa::obtenerMesa($mesaId);
        Mesa::borrarMesa($mesaId);

        LogController::CargarUno("mesas",$mesa->numero,$mesa->nombre,"Eliminar datos","Baja de una mesa");

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ActualizarEstado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $numero = $parametros['numero'];
        $estadoMesa = $parametros['estado'];
        $e = new Mesa();
        
        $m = Mesa::obtenerMesa($id);
        $e = Mesa::ValidarEstado($estadoMesa);
        if($estadoMesa != NULL && $id != NULL)
        { 
          if($e > 0 && $e < 5)
          {
              $m->actualizarEstado($numero,$estadoMesa, $id);
              $payload = json_encode(array("mensaje" => "Mesa actualizada"));
              LogController::CargarUno("mesas",$numero,$m->nombre,"Actualizar datos mesa",$estadoMesa);
          }
          else
          {
            $payload = json_encode(array("mensaje" => "No se pudo actualizar"));
          }
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }

      public static function CerrarMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $numero = $parametros['numero'];
        $estadoMesa = $parametros['estado'];
        $e = new Mesa();
        
        $m = Mesa::obtenerMesa($id);
        $e = Mesa::ValidarEstado($estadoMesa);
        if($estadoMesa != NULL && $id != NULL)
        { 
          if($e > 0 && $e < 5)
          {
              $m->MesaCerrada($numero,$estadoMesa, $id, 0);
              $payload = json_encode(array("mensaje" => "Mesa cerrada"));
              LogController::CargarUno("mesas",$numero,$m->nombre,"Actualizar datos mesa",$estadoMesa);
          }
          else
          {
            $payload = json_encode(array("mensaje" => "No se pudo actualizar"));
          }
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }

    public static function ActualizarMesa($estadoMesa, $idMesa)
    {
        $estadoMesa = $estadoMesa; 
        $idMesa = $idMesa;
        $e = new Mesa();
        
        $m = Mesa::obtenerMesa($idMesa);
        $e = Mesa::ValidarEstado($estadoMesa);
        if($estadoMesa != NULL && $idMesa != NULL)
        { 
          if($e > 0 && $e < 5)
          {
              $m->estadoMesa = $estadoMesa;
              $m->modificarMesaAccion($idMesa);
              echo 'Mesa actualizada';
              LogController::CargarUno("mesas",$idMesa,$estadoMesa,"Actualizar datos","Actualizacion de una mesa");
              return TRUE;
          }
          else
          {
              echo 'Error al actualizar';  
          }
        }
        return FALSE;
      }

      public function obtenerCuenta($request, $response, $args)
      {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $codigo = $parametros['codigo'];
  
        $mesa = Mesa::obtenerMesa($id);
        
        FacturacionController::CargarFactura($mesa->id, $mesa->numero,$mesa->cuenta);

        $payload = json_encode(array("mensaje" => "La cuenta de la MESA #".$id." #".$codigo. " => es $".$mesa->cuenta));

        LogController::CargarUno("mesas",$codigo,$mesa->nombre,"Obtener cuenta","Cuenta de una mesa");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      }
}
?>