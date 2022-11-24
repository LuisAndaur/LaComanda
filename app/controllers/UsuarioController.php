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

        LogController::CargarUno("usuarios",$usr->nombre,$usr->puesto,"Cargar datos","Datos de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario creado con eusrito"));

        $usr->Mostrar();

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por id
        $usr = $args['idUser'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        LogController::CargarUno("usuarios",$usuario->nombre,0,"Obtener datos","Datos de un usuario");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        LogController::CargarUno("usuarios",0,0,"Obtener datos","Datos de todos los usuarios");

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idUser = $parametros['idUser'];
        Usuario::modificarUsuario($idUser);

        LogController::CargarUno("usuarios",0,$idUser,"Modificar datos","Modificacion de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario modificado con eusrito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['idUser'];
        Usuario::borrarUsuario($usuarioId);

        LogController::CargarUno("usuarios",0,0,"Borrar datos","Baja de un usuario");

        $payload = json_encode(array("mensaje" => "Usuario borrado con eusrito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    // public function LogIn($request, $response, $args)
    // {
    //   $parametros = $request->getParsedBody();
  
    //   $usr= new Usuario();
    //   $usr->idUser = $parametros['idUser'];
    //   $usr->nombre = $parametros['nombre'];
    //   $usr->puesto = $parametros['puesto'];
    //   $usr->clave = $parametros['clave'];

    //   if(Usuario::ValidarUsuario($usr))
    //   {
    //     $datos = array('idUser' => $usr->idUser,'nombre' => $usr->nombre, 'puesto' => $usr->puesto, 'clave' => $usr->clave);
    //     $token = AuthJWT::CrearToken($datos);
    //     $payload = json_encode(array('jwt' => $token));

    //     LogController::CargarUno("usuarios",$usr->nombre,$usr->puesto,"Login","Login de un usuario");
    //   }
    //   else
    //   {
    //     $payload = json_encode(array('error' => 'El usuario no existe'));
    //   }
    //   $response->getBody()->write($payload);
    //   return $response->withHeader('Content-Type', 'application/json');
    // }
}
