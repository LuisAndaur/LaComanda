<?php



class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $estado = $parametros['estado'];
        $rol = $parametros['rol'];
        

        if($usuario == null || $clave == null || $estado == null || $rol == null)
        {
          $response->getBody()->write("Error al recibir los parametros");
          return $response->withHeader('Content-Type', 'application/json');
        }

        if(Usuario::obtenerUsuario($usuario) != null)
        {
          $response->getBody()->write("Nombre de usuario ya existente");
          return $response->withHeader('Content-Type', 'application/json');
        }

        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->estado = $estado;
        $usr->rol = $rol;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        if($usuario != null)
        {
          $response->getBody()->write(json_encode($usuario));
        }
        else
        {
          $response->getBody()->write("Usuario no encontrado");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        if($lista != null)
        {
          $response->getBody()->write(json_encode($lista));
        }
        else{
          $response->getBody()->write(json_encode('No se encotraron usuarios'));
        }  

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        
    }

    public function BorrarUno($request, $response, $args)
    {
      
    }
}
