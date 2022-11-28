<?php
require_once './models/Consultas.php';

class ConsultasController extends Consultas
{
    public function OperacionesSector($request, $response, $args)
    {
        $lista = Consultas::OperacionesPorSector();
        $payload = json_encode(array("Operaciones por sector: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function OperacionesSectorEmpleado($request, $response, $args)
    {
        $lista = Consultas::OperacionesPorSectorYEmpleado();
        $payload = json_encode(array("Operaciones por sector y Empleado: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function OperacionesSeparado($request, $response, $args)
    {
        $lista = Consultas::OperacionesPorSeparado();
        $payload = json_encode(array("Operaciones por empleado: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MasVendido($request, $response, $args)
    {
        $lista = Consultas::ProductoMasVendido();
        $payload = json_encode(array("Producto/s más vendidos: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MenosVendido($request, $response, $args)
    {
        $lista = Consultas::ProductoMenosVendido();
        $payload = json_encode(array("Producto/s menos vendidos: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FueraDeTiempo($request, $response, $args)
    {
        $lista = Consultas::PedidosFueraDeTiempo();
        $payload = json_encode(array("Pedidos entregados fuera de tiempo: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Cancelados($request, $response, $args)
    {
        $lista = Consultas::PedidosCancelados();
        $payload = json_encode(array("Pedidos cancelados: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MasUsada($request, $response, $args)
    {
        $lista = Consultas::MesaMasUsada();
        $payload = json_encode(array("Mesa /s más usada: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MenosUsada($request, $response, $args)
    {
        $lista = Consultas::MesaMenosUsada();
        $payload = json_encode(array("Mesa /s menos usada: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MasFacturo($request, $response, $args)
    {
        $lista = Consultas::MesaMasFacturo();
        $payload = json_encode(array("Mesa /s que más facturó: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MenosFacturo($request, $response, $args)
    {
        $lista = Consultas::MesaMenosFacturo();
        $payload = json_encode(array("Mesa /s que menos facturó: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MayorImporte($request, $response, $args)
    {
        $lista = Consultas::MesaFacturaMayorImporte();
        $payload = json_encode(array("Mesa /s que tuvo la factura con el mayor importe: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MenorImporte($request, $response, $args)
    {
        $lista = Consultas::MesaFacturaMenorImporte();
        $payload = json_encode(array("Mesa /s que tuvo la factura con el menor importe: " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function EntreFechas($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigoMesa = $args['codigoMesa'];
        $fechaInicio = $parametros['fechaInicio'];
        $fechaFinal = $parametros['fechaFinal'];

        $lista = Consultas::FacturacionEntreFechas($codigoMesa, $fechaInicio, $fechaFinal);
        $payload = json_encode(array("Lo que facturó la MESA# $codigoMesa - entre $fechaInicio y $fechaFinal " => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BuenosComentarios($request, $response, $args)
    {
        $lista = Consultas::MejoresComentarios();
        $payload = json_encode(array("Mesa /s con mejores comentarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MalosComentarios($request, $response, $args)
    {
        $lista = Consultas::PeoresComentarios();
        $payload = json_encode(array("Mesa /s con peores comentarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
}