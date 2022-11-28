<?php
require_once './db/AccesoDatos.php';
require_once './models/Usuario.php';

class Mesa{
    public $id;
    public $numero;
    public $estadoMesa;
    public $cuenta;
    public $nombre;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (numero, estadoMesa, nombre, cuenta) VALUES (:numero, :estadoMesa, :nombre, :cuenta)");
        $consulta->bindValue(':numero', $this->numero, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':cuenta', $this->cuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, numero, estadoMesa, nombre, cuenta FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesaNumero($numero)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, numero, estadoMesa, nombre, cuenta FROM mesas WHERE numero = :numero");
        $consulta->bindValue(':numero', $numero, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerEstados()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT numero as numero, estadoMesa FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function actualizarEstado($numero, $estadoMesa, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa WHERE numero = :numero AND id = :id");
        $consulta->bindValue(':numero', $numero, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa',$estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':id',$id, PDO::PARAM_INT);
        
        return $consulta->execute(); 
    }

    public static function MesaCerrada($numero, $estadoMesa, $id, $cuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa, cuenta = :cuenta WHERE numero = :numero AND id = :id");
        $consulta->bindValue(':numero', $numero, PDO::PARAM_STR);
        $consulta->bindValue(':estadoMesa',$estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':id',$id, PDO::PARAM_INT);
        $consulta->bindValue(':cuenta',$cuenta, PDO::PARAM_INT);
        
        return $consulta->execute(); 
    }

    public function modificarMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa, nombre = :nombre WHERE id = :id");
        $consulta->bindValue(':id', $mesa->id, PDO::PARAM_INT);
        $consulta->bindValue(':estadoMesa', $mesa->estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $mesa->nombre, PDO::PARAM_STR);
        //$consulta->bindValue(':cuenta', $$mesa->cuenta, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function modificarMesaAccion($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa, nombre = :nombre, cuenta = :cuenta WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estadoMesa', $this->estadoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':cuenta', $this->cuenta, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerConsumosMesa($codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT SUM(pedidos.total) FROM pedidos, productos WHERE pedidos.codigo = :codigo AND pedidos.datosProducto = productos.nombre");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public static function cargarCuentaMesa($id, $cuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET cuenta = $cuenta WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoMesa = :estadoMesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estadoMesa', "Cerrada", PDO::PARAM_STR);
        $consulta->execute();
    }

    public function Mostrar()
    {
        echo "---- MESA ----"."\n\n";
        echo "Numero: ".$this->numero."\n\n";
        echo "Estado: ".$this->estadoMesa."\n\n";
        echo "Usuario: ".$this->nombre."\n\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }

    public static function ValidarEstado($e)
    {
        switch($e)
        {
            case 'Cerrada':
                return 1;
            case 'Esperando':
                return 2;
            case 'Comiendo':
                return 3;
            case 'Pagando':
                return 4;
                break;
            default:
                throw new Exception ("Estado invalido");
        }
    }

    public static function ValidarUser($nombre)
    {
        $lista = Usuario::obtenerTodos();

        foreach ($lista as $u)
        {
            if($u->nombre == $nombre)
            {
                if($u->idPuesto == 4 || $u->idPuesto == 5)
                {
                    return TRUE;
                }
            }
        }
        echo "El usuario debe ser un Mozo o Socio" . "\n";
        return FALSE;
    }

    public static function CrearNumero()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyz';
        $numero = substr(str_shuffle($caracteres), 0, 5);
        return $numero;
    }
}


?>