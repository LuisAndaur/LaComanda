<?php
require_once './models/Log.php';

class LogController extends Log
{
    public static function CargarUno($tipo, $idTipo, $nombreUsr, $accion, $descripcion)
    {
      $fecha = date('Y-m-d');
     
      //Log
      $log = new Log();
      $log->tipo = $tipo;
      $log->idTipo = $idTipo;
      $log->nombreUsuario = $nombreUsr;
      $log->accion = $accion;
      $log->descripcion = $descripcion;
      $log->fecha = $fecha;

      //Creacion
      $creacion = $log->crearLog();

      if($creacion > 0)
      {
        $retorno = json_encode(array("mensaje" => "Log creado con exito"));
      }
      else
      {
        $retorno = json_encode(array("mensaje" => "Error al crear el log"));
      }

      return $retorno;
    }

    public function TraerTarde($request, $response, $args)
    {
        $tarde = Log::obtenerTarde();
        $payload = Log::Listar($tarde);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerATiempo($request, $response, $args)
    {
        $at = Log::obtenerATiempo();
        $payload = Log::Listar($at);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}