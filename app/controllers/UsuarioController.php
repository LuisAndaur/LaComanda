<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './controllers/LogController.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $puesto = $parametros['puesto'];
        $estado = $parametros['estado'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->clave = $clave;
        $usr->puesto = $puesto;
        $usr->estado = $estado;
        $usr->idPuesto = Usuario::ValidarPuesto($usr->puesto);
        $usr->idEstado = Usuario::ValidarEstado($usr->estado);

        $usr->crearUsuario();

        LogController::CargarUno("usuarios",$usr->puesto,$usr->nombre,"Crear nuevo usuario","Datos de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));

        $usr->Mostrar();

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por id
        $usr = $args['id'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        LogController::CargarUno("usuarios",$usuario->puesto,$usuario->nombre,"Listar un usuario","Datos de un usuario");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        LogController::CargarUno("usuarios","Todos",count($lista),"Listar todos los usuarios","Datos de todos los usuarios");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $idUser = $args['id'];

        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $puesto = $parametros['puesto'];
        $estado = $parametros['estado'];

        $usuario = Usuario::obtenerUsuario($idUser);
        $usuario->nombre = $nombre;
        $usuario->clave = $clave;
        $usuario->puesto = $puesto;
        $usuario->estado = $estado;
        $usuario->idPuesto = Usuario::ValidarPuesto($usuario->puesto);
        $usuario->idEstado = Usuario::ValidarEstado($usuario->estado);
        
        Usuario::modificarUsuario($usuario);

        LogController::CargarUno("usuarios",$usuario->puesto,$usuario->nombre,"Modificar datos","Modificacion de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $usuarioId = $args['id'];
        $usuario = Usuario::obtenerUsuario($usuarioId);
        Usuario::borrarUsuario($usuarioId);

        LogController::CargarUno("usuarios",$usuario->puesto,$usuario->nombre,"Borrar datos","Baja de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario borrado con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
