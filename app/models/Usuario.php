<?php
require_once './db/AccesoDatos.php';

class Usuario
{
    public $idUser;
    public $nombre;
    public $clave;
    public $puesto;
    public $estado;
    public $idPuesto;
    public $idEstado;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, clave, puesto, estado, idPuesto, idEstado) VALUES (:nombre, :apellido, :mail, :clave, :puesto, :estado, :idPuesto, :idEstado)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idPuesto', $this->idPuesto, PDO::PARAM_INT);
        $consulta->bindValue(':idEstado', $this->idEstado, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idUser, nombre, clave, puesto, estado, idPuesto, idEstado FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($idUser)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idUser, nombre, clave, puesto, estado, idPuesto, idEstado FROM usuarios WHERE idUser = :idUser");
        $consulta->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function obtenerUsuarioNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idUser, nombre, clave, puesto, estado, idPuesto, idEstado FROM usuarios WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public function modificarUsuario($idUser)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :nombre, clave = :clave, puesto = :puesto, estado = :estado, idPuesto = :idPuesto, idEstado = :idEstado WHERE idUser = :idUser");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idPuesto', $this->idPuesto, PDO::PARAM_INT);
        $consulta->bindValue(':idEstado', $this->idEstado, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($idUser)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE idUser = :idUser");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':idUser', $idUser, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public function Mostrar()
    {
        echo "---- USUARIO ----"."\n";
        echo "Nombre: ".$this->nombre."\n";
        echo "Puesto: ".$this->puesto."\n";
        echo "Estado: ".$this->estado."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }

    public function ValidarUsuario($usr)
    {
        $lista = Usuario::obtenerTodos();
        foreach($lista as $u)
        {
            if($u->idUser == $usr->idUser && $u->clave == $usr->clave)
            {
                $usr->nombre = $u->nombre;
                $usr->puesto = $u->puesto;
                $usr->estado = $u->estado;
                $usr->idPuesto = $u->idPuesto;
                $usr->idEstado = $u->idEstado;

                echo "-- BIENVENIDO! --". "\n".$u->nombre . "\n" . "Puesto: " . $u->puesto . "\n";
                return TRUE;
            }
        }
        return FALSE;
    }

    public static function obtenerNombre($usr)
    {
        $lista = Usuario::obtenerTodos();
        foreach($lista as $u)
        {
            if($u->idUser == $usr)
            {
                $nombre = $u->nombre;

                return $nombre;
            }
        }
        return "El nombre no existe";
    }

    public static function obtenerPuesto($usr)
    {
        $lista = Usuario::obtenerTodos();
        foreach($lista as $u)
        {
            if($u->idUser == $usr)
            {
                $puesto = $u->puesto;

                return $puesto;
            }
        }
        return "El puesto no existe";
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

    public function ValidarEstado($e)
    {
        switch($e)
        {
            case 'Disponible':
                return 6;
            case 'Suspendido':
                return 7;
            case 'Baja':
                return 8;
                break;
            default:
                throw new Exception ("Estado invalido");
        }
    }


}