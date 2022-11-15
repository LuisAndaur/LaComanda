<?php

    use Slim\Http\UploadedFile;

    class PedidoController extends Pedido implements IApiUsable
    {
        public function TraerUno($request, $response, $args)
        {
            $id = $args['id'];
            $pedido = Pedido::ObtenerPedido($id);

            if($pedido != null)
            {
                $response->getBody()->write(json_encode($pedido));
            }
            else
            {
                $response->getBody()->write("No se encontro el pedido");
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function TraerTodos($request, $response, $args)
        {
            $listaPedidos = Pedido::ObtenerPedidos();
            $response->getBody()->write(json_encode($listaPedidos));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function CargarUno($request, $response, $args)
        {
            $body = $request->getParsedBody();
            $numero_de_pedido = $body['numero_de_pedido'];
            $mesaID = $body['mesaID'];
            $clienteID = $body['clienteID'];
            
            if($mesaID != null && $clienteID != null)
            {
                $mesa = Mesa::ObtenerMesa($mesaID);
                if($mesa == null || $mesa->estado == 'cerrada' || $mesa->estado == 'con cliente pagando' || $mesa->estado == 'con cliente comiendo')
                {
                    $response->getBody()->write("Mesa inexistente u ocupada");
                    return $response->withHeader('Content-Type', 'application/json');
                }
                
                $cliente = Cliente::obtenerCliente($clienteID);
                if($cliente == null)
                {
                    $response->getBody()->write("Cliente inexistente");
                    return $response->withHeader('Content-Type', 'application/json');
                }

                $pedido = new Pedido();
                $pedido->numero_de_pedido = $numero_de_pedido;
                $pedido->fechaEntrada = date("Y-m-d-H:i:s");
                $pedido->mesaID = $mesaID;
                $pedido->clienteID = $clienteID;
                $pedido->usuarioID = 1;
                $pedido->estado = "en preparacion";
                $pedido->tiempoDeEntrega = 0;
                $pedido->fueCancelado = 0;

                $pedido->CrearPedido();

                $response->getBody()->write("Pedido creado");
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