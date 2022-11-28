<?php
require_once 'LogController.php';
require_once './models/Mesa.php';
require_once './models/Facturacion.php';
require_once './interfaces/IApiUsable.php';

    class FacturacionController
    {
        public static function CargarFactura($idmesa, $codigo, $importe)
        {
            try {
                if ($idmesa!=null && $importe!=null) {
                    $mesa = Mesa::obtenerMesa($idmesa);
                    if ($mesa) {
                        if ($mesa->estadoMesa == "Pagando") {
                            $facturacion = new Facturacion();
                            $facturacion->idMesa = $idmesa;
                            $facturacion->importe = $importe;
                            $facturacion->codigoMesa = $codigo;

                            LogController::CargarUno("facturacion",$facturacion->idMesa,$mesa->nombre,"Mesa pagando",$facturacion->importe);

                            Facturacion::crearFactura($facturacion);
                            $payload =  json_encode(array("mensaje"=> "Factura generada con exito"));
                        } 
                        else {
                            $payload =  json_encode(array("mensaje"=> "Debe modificar el estado de la Mesa a Pagando"));
                        }
                    } 
                    else {
                        $payload =  json_encode(array("mensaje"=> "Mesa no econtrada"));
                    }
                }
                else
                {
                    $payload =  json_encode(array("mensaje"=> "No se recibieron algunos de los parametros necesarios para el alta de la Factura, idmesa, importe"));
                }
            } catch (Exception $ex) {
                $payload =  json_encode(array("mensaje"=> $ex->getMessage()));
            }

            return $payload;
        }
    }
?>