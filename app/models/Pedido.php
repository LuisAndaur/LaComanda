<?php
require_once './db/AccesoDatos.php';

class Pedido{
    public $idPedido;
    public $codigo;
    public $idMesa;
    public $usuario;
    public $estado;
    public $total;
    public $fecha;
    public $foto;
    public $tiempo;
    public $puesto;
    public $datosProducto; //Nombre del Producto
    public $tipoProducto; //Tipo de Producto
    public $cantidad; //Cantidad del Producto

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto) VALUES (:codigo, :idMesa, :datosProducto, :tipoProducto, :usuario, :estado, :total, :cantidad, :tiempo, :puesto, :fecha, :foto)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':datosProducto', $this->datosProducto, PDO::PARAM_STR);
        $consulta->bindValue(':tipoProducto', $this->tipoProducto, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':total', $this->total, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPedidoPendiente($puesto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos WHERE pedidos.estado = 'Pendiente' AND puesto = :puesto");
        $consulta->bindValue(':puesto', $puesto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoenPreparacion($puesto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos WHERE pedidos.estado = 'En preparacion' AND puesto = :puesto");
        $consulta->bindValue(':puesto', $puesto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoListo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos WHERE estado = 'Listo para servir'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    
    public static function obtenerTiempo($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT codigo as numPedido, tiempo FROM pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerTiempoPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT codigo as numPedido, tiempo FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPedidoPuesto($puesto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idPedido, codigo, idMesa, datosProducto, tipoProducto, usuario, estado, total, cantidad, tiempo, puesto, fecha, foto FROM pedidos WHERE puesto = :puesto");
        $consulta->bindValue(':puesto', $puesto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, datosProducto = :datosProducto, tipoProducto = :tipoProducto, usuario = :usuario, estado = :estado, total = :total, cantidad = :cantidad, tiempo = :tiempo, puesto = :puesto, fecha = :fecha, foto = :foto WHERE codigo = :codigo");
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':datosProducto', $this->datosProducto, PDO::PARAM_STR);
        $consulta->bindValue(':tipoProducto', $this->tipoProducto, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':total', $this->total, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();
    }

    
    public static function actualizarPedido($codigo,$estado,$puesto,$tiempo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, tiempo = :tiempo WHERE codigo = :codigo AND puesto = :puesto");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado',$estado, PDO::PARAM_STR);
        $consulta->bindValue(':puesto',$puesto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo',$tiempo, PDO::PARAM_STR);
        
        return $consulta->execute(); 
    }

    public static function borrarPedido($idPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_STR);
        $consulta->execute();
    }

    public function Mostrar()
    {
        echo "---- PEDIDO ----"."\n";
        echo "Codigo: ".$this->codigo."\n";
        echo "Mesa: ".$this->idMesa."\n";
        echo "Estado: ".$this->estado."\n";
        echo "Usuario: ".$this->usuario."\n";
        echo "Producto: ".$this->datosProducto."\n";
        echo "Tiempo estimado de espera: ".$this->tiempo."\n";
        echo "Total: ".$this->total."\n";
    }

    public static function Listar($lista)
    {
        foreach ($lista as $obj)
        {
            $obj->Mostrar();
        }
    }

    public function ValidarProducto($nombre)
    {
        $lista = Producto::obtenerTodos();
        if($lista != null)
        {
            foreach ($lista as $p)
            {
                if($p->nombre == $nombre)
                {
                    return TRUE;
                }
            }
            return TRUE;
        }
        echo "El producto no se encuentra disponible\n";
        return FALSE;
    }

    public function ValidarEstado($e)
    {
        switch($e)
        {
            case 'Pendiente':
                return 'Pendiente';
            case 'En preparacion':
                return 'En preparacion';
            case 'Listo para servir':
                return 'Listo para servir';
            case 'Servido':
                return 'Servido';
            case 'Pagado':
                return 'pagado';
            default:
                throw new Exception ("Tipo de producto invalido");
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

    public static function ValidarPuesto($p)
    {
        switch($p)
        {
            case 'Bartender':
                return 'Bartender';
            case 'Cervecero':
                return 'Cervecero';
            case 'Cocinero':
                return 'Cocinero';
            case 'Mozo':
                return 'Mozo';
            case 'Socio':
                return 'Socio';
                break;
            default:
                throw new Exception ("Perfil invalido");
        }
    }

    public static function CrearCodigo()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyz';
        $codigo = substr(str_shuffle($caracteres), 0, 5);
        return $codigo;
    }

    public function GuardarFoto($foto)
    {
        $path= 'FotosMesas/';
        if (!file_exists($path))
        {
            mkdir('FotosMesas/', 0777, true);    
        }
        $extension = explode(".", $foto["name"]);
        
        $destino = $path.$this->codigo." - ".$this->idMesa."_".end($extension);
    
        if(move_uploaded_file($foto["tmp_name"],$destino))
        {
            echo "\nImagen guardada con exito!\n";
            $this->foto = $destino;
        }
        else
        {
            echo "Error";
            var_dump($foto["error"]);
        }
    }


}
?>