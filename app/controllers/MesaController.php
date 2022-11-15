<?php


    class MesaController extends Mesa implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $mesa = Mesa::ObtenerMesa($id);

            if($mesa != null)
            {
                $response->getBody()->write(json_encode($mesa));
            }
            else
            {
                $response->getBody()->write("No se encontro la mesa");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $listaMesas = Mesa::ObtenerMesas();
            $response->getBody()->write(json_encode($listaMesas));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_mesa = $body['numero_de_mesa'];
            $estado = $body['estado'];
            if($numero_de_mesa != null && $estado != null)
            {
                $mesa = new Mesa();
                $mesa->numero_de_mesa = $numero_de_mesa;
                $mesa->estado = $estado;
                $mesa->CrearMesa();

                $response->getBody()->write("Mesa creada");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function BorrarUno($request, $response, $args)
        {
            
        }
        
        public function ModificarUno($request, $response, $args)
        {
           
        }
    }
    
?>