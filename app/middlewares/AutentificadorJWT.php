<?php


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
                    JsonWebToken::VerificarToken($token);
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
                $data = JsonWebToken::ObtenerData($token);
                if($data[1] == 'socio')
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
