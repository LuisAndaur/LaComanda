<?php
require_once './db/AccesoDatos.php';

class Log
{
    public $id;
    public $tipo;
    public $idTipo;
    public $nombreUsuario;
    public $accion;
    public $descripcion;
    public $fecha;

    public function crearLog()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (tipo, idTipo, nombreUsuario, accion, descripcion, fecha) VALUES (:tipo, :idTipo, :nombreUsuario, :accion, :descripcion, :fecha)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':idTipo', $this->idTipo, PDO::PARAM_STR);
        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idTipo, nombreUsuario, accion, descripcion, fecha FROM logs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function obtenerTarde()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idTipo, nombreUsuario, accion, descripcion, fecha FROM logs WHERE descripcion = '(Entregado tarde)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function obtenerATiempo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, idTipo, nombreUsuario, accion, descripcion, fecha FROM logs WHERE descripcion = '(Entregado a tiempo)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public function Mostrar()
    {
        echo "---- LOG ----"."\n";
        echo "Id: ".$this->id."\n";
        echo "Tipo: ".$this->tipo."\n";
        echo "IdTipo: ".$this->idTipo."\n";
        echo "Usuario: ".$this->nombreUsuario."\n";
        echo "Accion: ".$this->accion."\n";
        echo "Descripcion: ".$this->accion."\n";
        echo "Fecha: ".$this->fecha."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }
}