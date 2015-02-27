<?php
session_start();

include("../default.php");
require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$planificacionNombre = mb_convert_encoding(trim($_POST['planificacionNombre']), "ISO-8859-1", "UTF-8");
$planificacionDescripcion = mb_convert_encoding(trim($_POST['planificacionDescripcion']), "ISO-8859-1", "UTF-8");

$consulta = $objPlanificacion->planGuardarCabecera($clienteId, $administradorId, $planificacionNombre, $planificacionDescripcion, $usuarioModificacion);


if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>
