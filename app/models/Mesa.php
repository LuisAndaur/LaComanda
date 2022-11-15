<?php

    class Mesa
    {
        public $id;
        public $numero_de_mesa;
        public $estado;

        public function CrearMesa()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO mesas (numero_de_mesa, estado) values (:numero_de_mesa, :estado)");
            $consulta->bindValue(':numero_de_mesa', $this->numero_de_mesa, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerMesa($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Mesa');
        }

        public static function ObtenerMesas()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM mesas");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
        }

        public static function ModificarMesa($mesa)
        {
            var_dump($mesa);
            $id = $mesa->id;
            $numero_de_mesa = $mesa->numero_de_mesa;
            $estado = $mesa->estado;

            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE mesas SET numero_de_mesa = :numero_de_mesa, estado = :estado WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':numero_de_mesa', $numero_de_mesa, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }

        public function ActualizarEstado()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
        }

        public static function EliminarMesa($numero_de_mesa)
        {
            $mesa = $numero_de_mesa;
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM mesas WHERE numero_de_mesa = :numero_de_mesa");
            $consulta->bindValue(':numero_de_mesa', $mesa, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    }

?>