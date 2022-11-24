<?php
require_once './models/Log.php';
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';

class EstadisticasController
{
    public function Estadisticas($request, $response, $args)
    {
      $tipo = $args['tipo'];

      switch($tipo)
        {
            case 'pedidos':
                $contadorTotalMes = 0;
                $contadorEntregadoTardeMes = 0;
                $contadorServidoTardeMes = 0;
                $fechaMes = date("Y-m");
                $lista = Pedido::obtenerTodos();
                $pedidos = json_decode(json_encode(array("listaCompleta" => $lista)));
                foreach($pedidos->listaCompleta as $ped)
                {
                    if(str_contains($ped->fecha,$fechaMes) && $ped->estado == "pagado")
                    {
                        $contadorTotalMes++;
                    }
                }
                $lista = Log::obtenerTodos();
                $logs = json_decode(json_encode(array("listaCompleta" => $lista)));
                foreach($logs->listaCompleta as $log)
                {
                    $bool = str_contains($log->descripcion,"Entregado tarde");
                    if($bool != false && str_contains($log->fech_hora,$fechaMes))
                    {
                        $contadorEntregadoTardeMes++;
                    }
                    $bool2 = str_contains($log->descripcion,"Servido tarde");
                    if($bool2 != false && str_contains($log->fecha,$fechaMes))
                    {
                        $contadorServidoTardeMes++;
                    }
                }
                echo "Pedidos del Mes $fechaMes: \n";
                echo "-Cantidad de Pedidos completados este mes: " . $contadorTotalMes . "\n" . "-Cantidad de Pedidos preparados tarde este mes: " . $contadorEntregadoTardeMes . "\n" . "-Cantidad de pedidos servidos tarde este mes: " . $contadorServidoTardeMes;
            break;
            case 'mesas':
                $noHayMesas = 0;
                $todasLasMesasMes = [];
                $fechaMes = date("Y-m");
                $lista = Pedido::obtenerTodos();
                $pedidos = json_decode(json_encode(array("listaCompleta" => $lista)));
                foreach($pedidos->listaCompleta as $ped)
                {
                    $mesaMesExists = str_contains($ped->fecha,$fechaMes);
                    if($mesaMesExists != false)
                    {
                        if(!array_key_exists($ped->idMesa,$todasLasMesasMes))
                        {
                            $todasLasMesasMes[$ped->idMesa] = 1;
                            $noHayMesas++;
                        }
                        else
                        {
                            $todasLasMesasMes[$ped->idMesa]++;
                            $noHayMesas++;
                        }
                    }
                    
                }
                if($noHayMesas > 0)
                {
                    echo "\n -Mesas por Mes: \n";
                    ksort($todasLasMesasMes);
                    foreach($todasLasMesasMes as $mesa => $cantMesa)
                    {
                        echo 'La Mesa ' . $mesa .' se uso ' . $cantMesa . " veces este mes $fechaMes \n";
                    }
                }
                else
                {
                    echo "Este mes $fechaMes no se uso ninguna mesa";
                }
            break;
            case 'usuarios':
                $fechaMes = date("Y-m");
                $noHayUsuarios = 0;
                $usuariosMes = [];
                $lista = Pedido::obtenerTodos();
                $pedidos = json_decode(json_encode(array("listaCompleta" => $lista)));
                foreach($pedidos->listaCompleta as $ped)
                {
                    $bool = str_contains($ped->estado,"pagado");
                    if($bool != false && str_contains($ped->fecha,$fechaMes))
                    {
                        if(!array_key_exists($ped->idUser,$usuariosMes))
                        {
                            $usuariosMes[$ped->idUser] = 1;
                            $noHayUsuarios++;
                        }else
                        {
                            
                            $usuariosMes[$ped->idUser]++;
                            $noHayUsuarios++;
                        }  
                    }  
                }

                if($noHayUsuarios > 0)
                {
                    echo " -Usuarios por Mes: \n";
                    ksort($usuariosMes);
                    foreach($usuariosMes as $usuario => $cantusuario)
                    {
                        echo 'El usuario ' . $usuario .' trabajo en el local ' . $cantusuario . " veces este mes $fechaMes \n";
                    }
                }
                else
                {
                    echo "Este mes $fechaMes no trabajo ningun usuario";
                }
            break;
        }
        $payload = "todo ok";
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}