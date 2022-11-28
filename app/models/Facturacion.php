<?php

class Facturacion{
    public $idFacturacion;
    public $idMesa;
    public $codigoMesa;
    public $fechaCreacion;
    public $importe;

    public static function crearFactura($facturacion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO facturacion (idMesa, codigoMesa, fechaCreacion, importe) VALUES (:idMesa, :codigoMesa, :fechaCreacion, :importe)");
        $consulta->bindValue(':idMesa', $facturacion->idMesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $facturacion->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaCreacion', date('Y-m-d'));
        $consulta->bindValue(':importe', $facturacion->importe, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
}




?>