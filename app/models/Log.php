<?php
require_once './db/AccesoDatos.php';

class Log
{
    public $id;
    public $tipo;
    public $identity;
    public $nombreUsuario;
    public $accion;
    public $descripcion;
    public $fecha;
    public $hora;

    public function crearLog()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (tipo, identity, nombreUsuario, accion, fecha, hora, descripcion) VALUES (:tipo, :identity, :nombreUsuario, :accion, :fecha, :hora, :descripcion)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':identity', $this->identity, PDO::PARAM_STR);
        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':accion', $this->accion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', date('Y-m-d'));
        $consulta->bindValue(':hora', date('H:i:s'));
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function obtenerTarde()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, identity, nombreUsuario, accion, descripcion, fecha, hora FROM logs WHERE descripcion = '(Entregado tarde)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function obtenerATiempo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, identity, nombreUsuario, accion, descripcion, fecha, hora FROM logs WHERE descripcion = '(Entregado a tiempo)'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public function Mostrar()
    {
        echo "---- LOG ----"."\n";
        echo "Id: ".$this->id."\n";
        echo "Tipo: ".$this->tipo."\n";
        echo "Numero/Tipo: ".$this->identity."\n";
        echo "Nombre: ".$this->nombreUsuario."\n";
        echo "Accion: ".$this->accion."\n";
        echo "Descripcion: ".$this->accion."\n";
        echo "Fecha: ".$this->fecha."\n";
        echo "Hora: ".$this->hora."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }
}