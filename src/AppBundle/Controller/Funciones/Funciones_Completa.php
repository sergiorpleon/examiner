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

    public static function rmDir_rf($uploaddir)
    {
        foreach (glob($uploaddir . "/*") as $archivos_carpeta)
        {
            if (is_dir($archivos_carpeta))
            {
                Funciones_Completa::rmDir_rf($archivos_carpeta);
            }

            else
            {
                unlink($archivos_carpeta);
            }
        }
        rmdir($uploaddir);
    }


    public static function rmFile_rf($uploaddir)
    {
        foreach (glob($uploaddir . "/*") as $archivos_carpeta)
        {
            if (is_dir($archivos_carpeta))
            {
                Funciones_Completa::rmFile_rf($archivos_carpeta);
            }

            else
            {
                unlink($archivos_carpeta);
            }
        }
        //rmdir($uploaddir);
    }


    public static function full_copy( $source, $target ) {
        if ( is_dir( $source ) ) {
            @mkdir( $target );
            $d = dir( $source );
            while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if ( is_dir( $Entry ) ) {
                    Funciones_Completa::full_copy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }

            $d->close();
        }else {
            copy( $source, $target );
        }
    }
}