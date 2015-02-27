<?php
session_start();

include("../default.php");
require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$clienteId = $_SESSION['clienteId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$planificacionId = mb_convert_encoding(trim($_POST['planificacionId']), "ISO-8859-1", "UTF-8");
$usuarioId = mb_convert_encoding(trim($_POST['usuarioId']), "ISO-8859-1", "UTF-8");

$consulta = $objPlanificacion->planGuardarUsuario($planificacionId, $clienteId, $usuarioId, $usuarioModificacion);
        
if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>