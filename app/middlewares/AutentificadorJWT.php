<?php

require_once 'AuthJWT.php';
use Psr7Middlewares\Middleware\Expires;
use Slim\Psr7\Response;

    class AutentificadorJWT
    {

        public static function verificarToken($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                if($peticionHeader != null)
                {
                    $token = trim(explode("Bearer", $peticionHeader)[1]);
                    AuthJWT::VerificarToken($token);
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No esta logueado"));
                    $response = $response->withStatus(401);
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }


        public static function verificarRolSocio($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = AuthJWT::ObtenerData($token);
                if($data[1] == 'Socio')
                {
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolMozo($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = AuthJWT::ObtenerData($token);
                if($data[1] == 'Socio' || $data[1] == 'Mozo')
                {                      
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                var_dump($e);
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }


        public static function verificarRolBartender($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = AuthJWT::ObtenerData($token);
                if($data[1] == 'Socio' || $data[1] == 'Bartender')
                {
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }

            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolCervecero($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = AuthJWT::ObtenerData($token);
                if($data[1] == 'Socio' || $data[1] == 'Cervecero')
                {
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public static function verificarRolCocinero($request, $handler)
        {
            $peticionHeader = $request->getHeaderLine("Authorization");
            $response = new Response();
            try
            {
                $token = trim(explode("Bearer", $peticionHeader)[1]);
                $data = AuthJWT::ObtenerData($token);
                if($data[1] == 'Socio' || $data[1] == 'Cocinero')
                {
                    $response = $handler->handle($request);
                }
                else
                {
                    $response->getBody()->write(json_encode("No autorizado"));
                    $response = $response->withStatus(401);
                }
            }
            catch(Exception $e)
            {
                $response->getBody()->write(json_encode("Error token invalido"));
                $response = $response->withStatus(401);
            }
            return $response->withHeader('Content-Type', 'application/json');
        }


        
    }
