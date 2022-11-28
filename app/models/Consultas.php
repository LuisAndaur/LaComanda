<?php
require_once './db/AccesoDatos.php';

class Consultas
{

    public static function IngresoAlSistema()
    {
        try {
            
            // $detalle = Capsule::select('select logs.idUsuario, logs.accion, logs.detalle, logs.fechaCreacion, logs.horaRegistro
            // from comanda.logs inner join comanda.usuarios on logs.idUsuario
            // = usuarios.idUsuarios where usuarios.tipo =  "Empleado" and logs.accion = "Login"');
    
            // $returnAux =  json_encode(array("resultado"=>$detalle));
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function OperacionesPorSector()
    {
        try 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.puesto,logs.tipo, count(logs.tipo) AS operaciones FROM comanda.logs INNER JOIN comanda.usuarios ON logs.nombreUsuario = usuarios.nombre WHERE usuarios.puesto !=  'Socio' AND logs.tipo != 'Login' GROUP BY usuarios.puesto ,logs.tipo");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        }
        catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function OperacionesPorSectorYEmpleado()
    {
        try 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.nombre, usuarios.puesto,logs.tipo, count(logs.tipo) as operaciones FROM comanda.logs INNER JOIN comanda.usuarios ON logs.nombreUsuario = usuarios.nombre WHERE usuarios.puesto != 'Socio' and logs.tipo != 'Login' GROUP BY usuarios.nombre,usuarios.puesto,logs.tipo");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }


        return $returnAux;
    }

    public static function OperacionesPorSeparado()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.nombre, usuarios.puesto,logs.tipo, logs.accion, count(logs.tipo) AS operaciones FROM comanda.logs INNER JOIN comanda.usuarios ON logs.nombreUsuario = usuarios.nombre WHERE usuarios.puesto != 'Socio' AND logs.tipo != 'Login' GROUP BY usuarios.nombre, usuarios.puesto,logs.tipo,logs.accion");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function ProductoMasVendido()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.tipo, productos.nombre, productos.puesto, cantidadVendida.cantidad from( select pedidos.datosProducto, sum(pedidos.cantidad) as cantidad from comanda.pedidos where pedidos.estado = 'Completado' group by pedidos.datosProducto) as cantidadVendida inner join comanda.productos on cantidadVendida.datosProducto = productos.nombre where cantidadVendida.cantidad = (select max(cuentaVendido.cantidad) maximo from (select pedidos.datosProducto, sum(pedidos.cantidad) as cantidad from comanda.pedidos where pedidos.estado = 'Completado' group by pedidos.datosProducto) as cuentaVendido)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function ProductoMenosVendido()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.tipo, productos.nombre, productos.puesto, cantidadVendida.cantidad from( select pedidos.datosProducto, sum(pedidos.cantidad) as cantidad from comanda.pedidos where pedidos.estado = 'Completado' group by pedidos.datosProducto) as cantidadVendida inner join comanda.productos on cantidadVendida.datosProducto = productos.nombre where cantidadVendida.cantidad = (select min(cuentaVendido.cantidad) minimo from (select pedidos.datosProducto, sum(pedidos.cantidad) as cantidad from comanda.pedidos where pedidos.estado = 'Completado' group by pedidos.datosProducto) as cuentaVendido)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }

    public static function PedidosFueraDeTiempo()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * from logs where  logs.tipo = 'pedidos' and logs.descripcion = 'Entregado tarde'");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function  PedidosCancelados()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * from pedidos where  estado = 'Cancelado'");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    public static function MesaMasUsada()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT uso.idMesa, mesas.numero, uso.cantidad from (select idMesa, count(idFacturacion) cantidad from comanda.facturacion group by idMesa) as uso inner join comanda.mesas on mesas.id =  uso.idMesa where uso.cantidad = (select max(maximo.cantidad) from (select idMesa, count(idFacturacion) cantidad from comanda.facturacion group by idMesa) as maximo)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }

    
    public static function MesaMenosUsada()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT uso.idMesa, mesas.numero, uso.cantidad from (select idMesa, count(idFacturacion) cantidad from comanda.facturacion group by idMesa) as uso inner join comanda.mesas on mesas.id =  uso.idMesa where uso.cantidad = (select min(minimo.cantidad) from (select idMesa, count(idFacturacion) cantidad from comanda.facturacion group by idMesa) as minimo)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }

    public static function MesaMasFacturo()
    {
        try 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT importes.idMesa,mesas.numero, importes.sumImporte as total from (select idMesa, sum(importe) sumImporte from comanda.facturacion group by idMesa) as importes inner join comanda.mesas on mesas.id = importes.idMesa where importes.sumImporte = (select max(sumImporte.sumImporte) maxImporte from(select idMesa, sum(importe) sumImporte from comanda.facturacion group by idMesa) as sumImporte)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }

    public static function MesaMenosFacturo()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT importes.idMesa,mesas.numero, importes.sumImporte as total from (select idMesa, sum(importe) sumImporte from comanda.facturacion group by idMesa) as importes inner join comanda.mesas on mesas.id = importes.idMesa where importes.sumImporte = (select min(sumImporte.sumImporte) minImporte from(select idMesa, sum(importe) sumImporte from comanda.facturacion group by idMesa) as sumImporte)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }

    public static function MesaFacturaMayorImporte()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT facturacion.idMesa,facturacion.fechaCreacion as fecha, mesas.numero, facturacion.importe as maximo from comanda.facturacion inner join comanda.mesas on facturacion.idMesa = mesas.id where importe = (select max(importe) from comanda.facturacion) group by facturacion.idMesa,facturacion.fechaCreacion, mesas.numero, facturacion.importe");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }


        return $returnAux;
    }

    public static function MesaFacturaMenorImporte()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT facturacion.idMesa,facturacion.fechaCreacion as fecha, mesas.numero, facturacion.importe as maximo from comanda.facturacion inner join comanda.mesas on facturacion.idMesa = mesas.id where importe = (select min(importe) from comanda.facturacion) group by facturacion.idMesa,facturacion.fechaCreacion, mesas.numero, facturacion.importe");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }


        return $returnAux;
    }

    

    public static function FacturacionEntreFechas($codigoMesa, $fechaInicio, $fechaFinal)
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, mesas.numero, sum(importe) as facturacion from comanda.facturacion
            inner join comanda.mesas on mesas.numero = facturacion.codigoMesa
            where facturacion.codigoMesa = '$codigoMesa' and facturacion.fechaCreacion >= '$fechaInicio' and facturacion.fechaCreacion <= '$fechaFinal' group by codigoMesa");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }

    public static function MejoresComentarios()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT codigo, mesa as valoracion, experiencia  from comanda.encuestas where mesa = (select max(mesa) calMaxima from comanda.encuestas)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }

        return $returnAux;
    }
    public static function PeoresComentarios()
    {
        try
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT codigo, mesa as valoracion, experiencia  from comanda.encuestas where mesa = (select min(mesa) calMinima from comanda.encuestas)");
            $consulta->execute();
            $detalle = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            $returnAux =  $detalle;
        } catch (Exception $ex) {
            $returnAux = json_encode(array("error" => $ex->getMessage()));
        }
        return $returnAux;
    }


}