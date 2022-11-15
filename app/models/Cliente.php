<?php

    class Cliente
    {
        public $id;
        public $nombre;

        public function CrearCliente()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO clientes (nombre) VALUES (:nombre)');
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->execute();
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM clientes");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
        }

        public static function obtenerCliente($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM clientes WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Cliente');
        }

        public static function modificarCliente($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute();
            return $consulta->rowCount();
        }

        public function GetCSV()
        {
            return $this->id . ',' . $this->nombre;
        }


        public static function borrarCliente($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM clientes WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }


?>