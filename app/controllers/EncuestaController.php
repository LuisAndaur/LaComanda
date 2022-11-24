<?php
require_once './models/Encuesta.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigo = $parametros['codigo'];
        $experiencia = $parametros['experiencia'];
        $mesa = $parametros['mesa'];
        $restaurante = $parametros['restaurante'];
        $cocinero = $parametros['cocinero'];
        $mozo = $parametros['mozo'];

        $payload =  null;

        if(!isset($parametros) || !isset($experiencia) || !isset($mesa) || !isset($restaurante) || !isset($mozo) || !isset($cocinero))
        {
          $payload = json_encode(array("mensaje" => "faltan datos"));
          $response = $response->withStatus(400);
        }
        else if(Mesa::obtenerMesa($codigo) == null)
        {
            $payload = json_encode(array("mensaje" => "numero de mesa incorrecto"));
            $response = $response->withStatus(400);
        }
        if(strlen($experiencia) > 67)
        {
            $payload = json_encode(array("mensaje" => "la experiencia es muy larga"));
            $response = $response->withStatus(400);
        }
        else
        {
            $encuesta = new Encuesta();
            $encuesta->codigo = $codigo;
            $encuesta->experiencia = $experiencia;
            $encuesta->cocinero = $cocinero;
            $encuesta->mozo = $mozo;
            $encuesta->mesa = $mesa;
            $encuesta->restaurante = $restaurante;
            $encuesta->crearEncuesta();
            $payload = json_encode(array("mensaje" => "Encuesta guardada"));
            $response = $response->withStatus(201);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $encuesta = Encuesta::obtenerEncuesta($id);
        $payload = json_encode($encuesta);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function MejoresComentarios($request, $response, $args)
    {
        $lista = Encuesta::obtenerMejoresComentarios();
        $payload = json_encode(array("mejoresComentarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
}