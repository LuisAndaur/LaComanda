<?php
require_once './db/AccesoDatos.php';
require_once './models/Usuario.php';

class Encuesta
{
    public $id;
    public $codigo;
    public $mesa;
    public $restaurante;
    public $mozo;
    public $cocinero;
    public $experiencia;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (mozo, mesa, restaurante, cocinero, experiencia, codigo) VALUES (:mozo, :mesa, :restaurante, :cocinero, :experiencia, :codigo)");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);       
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $this->experiencia, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mozo, mesa, restaurante, cocinero, experiencia, codigo FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mozo, mesa, restaurante, cocinero, experiencia, codigo FROM encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function validarNum($valor)
    {
        $p = 0;

        if(is_numeric($valor))
        { 
            if($valor < 1)
            {
                $p = 1;
            }
            else if($valor > 10)
            {
                $p = 10;
            }
            else
            {
                $p = $valor;
            }
        }
        else
        { 
            $p = 1; 
        }
        echo $p;        
        return $p;
    }

    public static function LeerEncuestasCSV($nombreCSV)
    {
        $archivo = fopen($nombreCSV, "r");
        if ($archivo != null)
        {
            $datos = array();
            while (!feof($archivo))
            {
                $aux = fgets($archivo);
                $lectura = explode(",", $aux);
       
                if (isset($lectura[0]) && !empty($lectura[0]) 
                    && isset($lectura[1]) && !empty($lectura[1])
                    && isset($lectura[2]) && !empty($lectura[2])
                    && isset($lectura[3]) && !empty($lectura[3])
                    && isset($lectura[4]) && !empty($lectura[4])
                    && isset($lectura[5]) && !empty($lectura[5])
                    && isset($lectura[6]) && !empty($lectura[6])
                )
                {
                    $encuesta = new Encuesta();
                    $encuesta->id = $lectura[0];
                    $encuesta->codigo = $lectura[1];
                    $encuesta->mesa = $lectura[2];
                    $encuesta->restaurante = $lectura[3];
                    $encuesta->mozo = $lectura[4];
                    $encuesta->cocinero = $lectura[5];
                    $encuesta->experiencia = $lectura[6];

                    if (!is_null($encuesta))
                    {
                        array_push($datos, $encuesta);
                    }
                }
            }
            fclose($archivo);
            return $datos;
        }
        return false;
    }

    public static function GuardarCsvEnBd(){
        $respuesta = false;
        $arrayEncuestas = Encuesta::LeerEncuestasCSV('./archivos/encuestaBD.csv');

        if($arrayEncuestas != null)
        {
            foreach ($arrayEncuestas as $e) {
                $e->crearEncuesta();
            } 
            $respuesta = true;
        }
        return $respuesta;
    }

    public static function obtenerMejoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, mozo, mesa, restaurante, cocinero, experiencia, codigo FROM encuestas WHERE mesa > 8 AND restaurante > 8 AND cocinero > 8 AND mozo > 8");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
}