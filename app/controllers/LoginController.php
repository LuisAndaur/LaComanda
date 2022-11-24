<?php
require_once './models/Horarios.php';

    class LoginController
    {
        public static function login($request, $response, $args)
        {
            $body = $request->getParsedBody();
            
            $nombre = $body['usuario'];
            $clave = $body['clave'];
            if($nombre == null || $clave == null)
            {
                $response->getBody()->write("Parametros incorrectos");
                $response = $response->withStatus(401);
                return $response;
            }
            
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE nombre = :nombre AND clave = :clave");
            $consulta->bindValue(':nombre', $nombre,PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave,PDO::PARAM_STR);
            $consulta->execute();
            $usuario = $consulta->fetchObject('Usuario');
            
            if($usuario != null)
            {
                $data = array();
                array_push($data, $usuario->nombre);
                array_push($data, $usuario->puesto);

                $registro = new Horarios();
                $registro->idUsuario  = $usuario->idUser;
                $registro->nombre  = $usuario->nombre;
                $registro->rol  = $usuario->puesto;
                $registro->CrearHorarios();

                $token = AuthJWT::CrearToken($data);
                //$response->getBody()->write("Logueado: ");
                $response->getBody()->write(json_encode($token));
                
                return $response;
            }

            $response->getBody()->write("No se pudo logear");
            $response = $response->withStatus(401);
            return $response;
        }
    }

?>