<?php

include ("../librerias/conexion.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");
require('clases/informes.class.php');

date_default_timezone_set('Europe/London');
set_include_path('../Classes/');


include 'PHPExcel/IOFactory.php';

$fechaActual = date("d-m-Y");

echo "Envio automatico: <br> " . $fechaActual . "<br/><br/>";


$objInforme = new informes();
$consulta = $objInforme->ListarPlanificaciones();

$queryMail = "EXEC CapXLSTextoMail";
$resultMail = $base_datos->sql_query($queryMail);
$rowMail = $base_datos->sql_fetch_assoc($resultMail);
$subject = $rowMail['SUBJECT'] ? $rowMail['SUBJECT'] : '';
$body = $rowMail['BODY'] ? $rowMail['BODY'] : '';
$footer = $rowMail['FOOTER'] ? $rowMail['FOOTER'] : '';
$mensajeCorreo = $body . $footer;

$enviar = "no";


$administradorId = $row['administradorId'];
                                     
$planificacionFecha = $fechaActual;

//ORGANIZACION MAZATLAN

echo "<br><br>" . "ORGANIZACION MAZATLAN" . "<br><br>";

$sectorNombre = "MAZATLAN";
$contactoEmail = '';        

$asunto = $subject;

include("GenerarExcel_MAZATLAN.php");

//$contactoEmail = $contactoEmail . $row1['usuarioEmail'] . ",";
$contactoEmail = "miguel.chavez@tmaz.mx, marcela.aguilar@tmaz.mx, Equipo.Gestion.Iso@saam.cl,";

$contactoEmail = substr($contactoEmail, 0, strlen($contactoEmail) - 1);
$asunto = $asunto . ' ' . $sectorNombre;

echo $contactoEmail . "<br>";


if($enviar == "si"){

    $resultadoEnvio = enviarCorreoInformes($correoDe, $correoDeNombre, $contactoEmail, $asunto, $mensajeCorreo, 'RepGerencia_MAZATLAN.xls');
    echo $resultadoEnvio . "<br>";

}
else{
    echo "No existen registros <br>";
}



?>
