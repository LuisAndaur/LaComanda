<?php
require_once './db/AccesoDatos.php';

class Producto{
    public $idProduc;
    public $nombre;
    public $precio;
    public $tipo;
    public $perfilEmpleado;
    public $idPuesto;
    public $puesto;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, tipo, perfilEmpleado, idPuesto, puesto) VALUES (:nombre, :precio, :tipo, :perfilEmpleado, :idPuesto, :puesto)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':perfilEmpleado', $this->perfilEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':idPuesto', $this->idPuesto, PDO::PARAM_INT);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProduc, nombre, precio, tipo, perfilEmpleado, idPuesto, puesto FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProduc, nombre, precio, tipo, perfilEmpleado, idPuesto, puesto FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductoId($idProduc)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProduc, nombre, precio, tipo, perfilEmpleado, idPuesto, puesto FROM productos WHERE idProduc = :idProduc");
        $consulta->bindValue(':idProduc', $idProduc, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerSectorProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idProduc, nombre, precio, tipo, perfilEmpleado, idPuesto, puesto FROM productos WHERE puesto = :puesto");
        $consulta->bindValue(':puesto', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public function modificarProducto($idProduc)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET nombre = :nombre, precio = :precio, tipo = :tipo, perfilEmpleado = :perfilEmpleado,, idPuesto = :idPuesto, puesto = :puesto WHERE idProduc = :idProduc");
        $consulta->bindValue(':idProduc', $idProduc, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':perfilEmpleado', $this->perfilEmpleado, PDO::PARAM_STR);
        $consulta->bindValue(':idPuesto', $this->idPuesto, PDO::PARAM_INT);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarProducto($idProduc)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM productos WHERE idProduc = :idProduc");
        $consulta->bindValue(':idProduc', $idProduc, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function Mostrar()
    {
        echo "---- PRODUCTO ----"."\n";
        echo "Nombre: ".$this->nombre."\n";
        echo "Tipo: ".$this->tipo."\n";
        echo "Precio: ".$this->precio."\n";
        echo "Puesto: ".$this->puesto."\n";
        echo "Usuario: ".$this->perfilEmpleado."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }

    public function ValidarPuesto($p)
    {
        switch($p)
        {
            case 'Bartender':
                return 1;
            case 'Cervecero':
                return 2;
            case 'Cocinero':
                return 3;
            case 'Mozo':
                return 4;
            case 'Socio':
                return 5;
                break;
            default:
                throw new Exception ("Perfil invalido");
        }
    }

    public function ValidarTipo($t)
    {
        switch($t)
        {
            case 'Comida':
                return 'Comida';
            case 'Cerveza':
                return 'Cerveza';
            case 'Trago':
                return 'Trago';
            default:
                throw new Exception ("Tipo de producto invalido");
        }
    }
}

?>