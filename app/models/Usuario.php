<?php

    class Usuario
    {
        public $id;
        public $usuario;
        public $clave;
        public $rol;
        public $estado;
        public $fechaBaja;
        public $suspencion;

        public function crearUsuario()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol, estado) VALUES (:usuario, :clave, :rol, :estado)");
            //$claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
            $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $objAccesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
        }

        public static function obtenerUsuario($usuario)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario");
            $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
            $consulta->execute();

            return $consulta->fetchObject('Usuario');
        }


}