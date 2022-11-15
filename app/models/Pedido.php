<?php

    class Pedido
    {
        public $id;
        public $numero_de_pedido;
        public $fechaEntrada;
        public $mesaID;
        public $clienteID;
        public $usuarioID;
        public $estado;
        public $tiempoEstimado;
        public $tiempoDeEntrega;
        public $fueCancelado;
        public $precioTotal;

        public function CrearPedido()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO pedidos (numero_de_pedido, fechaEntrada, mesaID, clienteID, usuarioID, estado) values (:numero_de_pedido, :fechaEntrada, :mesaID, :clienteID, :usuarioID, :estado)");
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_STR);
            $consulta->bindValue(':fechaEntrada', $this->fechaEntrada);
            $consulta->bindValue(':mesaID', $this->mesaID, PDO::PARAM_INT);
            $consulta->bindValue(':clienteID', $this->clienteID, PDO::PARAM_INT);
            $consulta->bindValue(':usuarioID', $this->usuarioID, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerPedido($numero_de_pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $numero_de_pedido, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->fetchObject('Pedido');
        }

        public static function ObtenerPedidos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM pedidos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
        }

        public function ActualizarTiempo()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET tiempoEstimado = :tiempoEstimado WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado);
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido);
            $consulta->execute();
        }

        public function ActualizarPrecio()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET precioTotal = :precioTotal WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':precioTotal', $this->precioTotal);
            $consulta->execute();
        }

        public static function ModificarPedido($pedido)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET producto = :producto, mesa = :mesa, estado = :estado WHERE id = :id");
            $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
            $consulta->bindValue(':producto', $pedido->producto, PDO::PARAM_INT);
            $consulta->bindValue(':mesa', $pedido->mesa, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $pedido->estado, PDO::PARAM_STR);
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function EliminarPedido($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }

        public function CambiarEstado()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->execute();
        }

        public function CerrarPedido()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE pedidos SET estado = :estado, tiempoDeEntrega = :tiempoDeEntrega WHERE numero_de_pedido = :numero_de_pedido");
            $consulta->bindValue(':numero_de_pedido', $this->numero_de_pedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', "listo", PDO::PARAM_STR);
            $consulta->bindValue(':tiempoDeEntrega', date('H:i:s'));
            $consulta->execute();
        }

    }


?>