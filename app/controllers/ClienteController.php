<?php

    class ClienteController extends Cliente implements IApiUsable
    {
        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $nombre = $body['nombre'];
            if($nombre != null)
            {
                $cliente = new Cliente();
                $cliente->nombre = $nombre;
                $cliente->CrearCliente();

                $response->getBody()->write("Cliente creado");
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $cliente = Cliente::obtenerCliente($id);
            if($cliente != null)
            {
              $payload = json_encode($cliente);
              $response->getBody()->write($payload);
            }
            else
            {
              $response->getBody()->write("Cliente no encontrado");
            }
            
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $lista = Cliente::obtenerTodos();
            if($lista != null)
            {
                $response->getBody()->write(json_encode($lista));
            }
            else{
                $response->getBody()->write(json_encode('No se encotraron clientes'));
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

?>