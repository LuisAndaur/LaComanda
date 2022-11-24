<?php
require_once './models/Horarios.php';

    class HorariosController extends Horarios implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $idUsuario = $body['idUsuario'];


            if($idUsuario != null )
            {
                $registro = new Horarios();
                $registro->idUsuario = $idUsuario;


                $registro->CrearHorarios();
                
                $response->getBody()->write("Registro de horarios de ingresos creado");
                $response->getBody()->write(json_encode($registro));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $registro = Horarios::obtenerHorarios($id);
            if($registro != null)
            {
              $payload = json_encode($registro);
              $response->getBody()->write($payload);
            }
            else
            {
              $response->getBody()->write("Registro de horarios de ingresos no encontrado");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Horarios::obtenerTodos();
            $payload = json_encode(array("listaRegistro" => $lista));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function ModificarUno($request, $response, $args)
        {

        }

        public function BorrarUno($request, $response, $args)
        {
            $id = $args['id'];
            if(Horarios::borrarHorarios($id))
            {
                $payload = json_encode(array("mensaje" => "Registro de horarios de ingresos borrado con exito"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Registro de horarios de ingresos no encontrado"));
            }   

            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

       
    }
?>