<?php

require_once 'Producto.php';
require_once './fpdf/fpdf.php';


class Archivo{

    public function CrearPdf($request, $response, $args)
    {
        $listaProductos = Producto::ObtenerTodos();
        if($listaProductos != null)
        {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(40,20, 'id,    nombre,     precio,     tipo,     perfilEmpleado,    idPuesto,     puesto');
            $pdf->Ln();
            foreach($listaProductos as $pedido)
            {
                $pdf->Cell(60,10, $pedido->toString());
                $pdf->Ln();
            }

            $pdf->Output('D', 'productos.pdf');
            $response->getBody()->write("Descarga con exito");
            return $response->withHeader('Content-Type', 'application/force-download');
        }
        else
        {
            $response->getBody()->write("No hay ningún producto");
        }
        
        return $response->withHeader('Content-Type', 'application/json'); 
    }

    public function CrearCsv($request, $response, $args)
    {
        $listaProducto = Producto::obtenerTodos();
        if($listaProducto != null)
        {
            $string = 'id,nombre,precio,tipo,perfilEmpleado,idPuesto,puesto' . PHP_EOL;
            foreach($listaProducto as $producto)
            {
                $string .= $producto->GetCSV() . PHP_EOL;
            }

            $file = "productos.csv";
            $txt = fopen($file, "w");
            fwrite($txt, $string);
            fclose($txt);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header("Content-Type: text/plain");
            readfile($file);
        }
        else
        {
            $response->getBody()->write(json_encode('Lista de productos vacia'));
        }
        return $response->withHeader('Content-Type', 'application/force-download');
    }

  
    public function CargarDatosCsv($request, $response, $args)
    {
        $archivo = $request->getUploadedFiles()['archivoCSV'];
        $split = explode(".", $archivo->getClientFilename());
        $extension = end($split);

        if($extension == 'csv')
        {
            $stream = $archivo->getStream();
            $lineas = explode(PHP_EOL, $stream);
            array_splice($lineas, 0, 1);
            foreach($lineas as $linea)
            {
                $explode = explode(',', $linea);
                $nuevoProducto = new Producto();
                $nuevoProducto->idProduc = $explode[0];
                $nuevoProducto->nombre = $explode[1];
                $nuevoProducto->precio = $explode[2];
                $nuevoProducto->tipo = $explode[3];
                $nuevoProducto->perfilEmpleado = $explode[4];
                $nuevoProducto->idPuesto = $explode[5];
                $nuevoProducto->puesto = $explode[6];
                $nuevoProducto->CrearProducto();
            }
            $response->getBody()->write("Archivo correcto");
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write("Archivo incorrecto");
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>
