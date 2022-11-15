<?php
    
    class Producto
    {
        public $id;
        public $nombre;
        public $tiempo;
        public $tipo;
        public $precio;
        public $stock;

        public function CrearProducto()
        {
            $acessoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $acessoDatos->prepararConsulta("INSERT INTO productos (nombre, tiempo, tipo, precio, stock) values (:nombre, :tiempo, :tipo, :precio, :stock)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $consulta->execute();
            return $acessoDatos->obtenerUltimoId();
        }

        public static function ObtenerProducto($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchObject('Producto');
        }

        public static function ObtenerProductos()
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("SELECT * FROM productos");
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");
        }

        public static function ModificarProducto($producto)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("UPDATE productos SET nombre = :nombre, tiempo = :tiempo, tipo = :tipo, precio = :precio, stock = :stock WHERE id = :id");
            $consulta->bindValues(':id', $producto->id, PDO::PARAM_INT);
            $consulta->bindValue(':nombre', $producto->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':tiempo', $producto->tiempo, PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $producto->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $producto->precio, PDO::PARAM_INT);
            $consulta->bindValue(':stock', $producto->stock, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }

        public static function EliminarProducto($id)
        {
            $accesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $accesoDatos->prepararConsulta("DELETE FROM productos WHERE id = :id");
            $consulta->bindValues(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->rowCount();
        }
    
    }


?>