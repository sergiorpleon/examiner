<?php

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 4/9/2018
 * Time: 15:33
 */
namespace AppBundle\Controller\Funciones;

class Funciones_Completa
{
    public static function Completa_Comprueba_Respuesta($Rcorrecta,$Restudiante)
    {
        $respuestas = explode('-', $Rcorrecta);
        foreach ($respuestas as $respuesta) {
            if (strtoupper($respuesta) == strtoupper($Restudiante)) {
                return true;
            }
        }
        return false;
    }
}