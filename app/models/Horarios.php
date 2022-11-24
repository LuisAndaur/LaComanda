<?php

    class Horarios
    {
        public $idUsuario;
        public $nombre;
        public $rol;
        public $diaIngreso;
        public $horaIngreso;

        public function CrearHorarios()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta('INSERT INTO horarios (idUsuario, nombre, rol, diaIngreso, horaIngreso) VALUES (:idUsuario, :nombre, :rol, :diaIngreso, :horaIngreso)');
            $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
            $consulta->bindValue(':diaIngreso', date('Y-m-d'));
            $consulta->bindValue(':horaIngreso', date('H:i:s'));
            $consulta->execute();
            return $accesoDatos->obtenerUltimoId();
        }

        public static function obtenerTodos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM horarios");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Horarios');
        }

        public static function obtenerHorarios($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM horarios WHERE idUsuario = :idUsuario");
            $consulta->bindValue(':idUsuario', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Horarios');
        }

        public static function modificarHorarios($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta();
            $consulta->bindValue();
            $consulta->execute(); 
            return $consulta->rowCount();
        }

        public function toString()
        {  
            return $this->idUsuario . ', ' . $this->diaIngreso . ', '. $this->horaIngreso;
        }


        public static function borrarHorarios($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM horarios WHERE idUsuario = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>