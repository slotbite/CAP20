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

$planificacionId = mb_convert_encoding(trim($_POST['planificacionId']), "ISO-8859-1", "UTF-8");
$planificacionNombre = mb_convert_encoding(trim($_POST['planificacionNombre']), "ISO-8859-1", "UTF-8");
$planificacionDescripcion = mb_convert_encoding(trim($_POST['planificacionDescripcion']), "ISO-8859-1", "UTF-8");
$planificacionEstado = mb_convert_encoding(trim($_POST['planificacionEstado']), "ISO-8859-1", "UTF-8");


$idsCapsulas = mb_convert_encoding(trim($_POST['idsCapsulas']), "ISO-8859-1", "UTF-8");
$idsUsuarios = mb_convert_encoding(trim($_POST['idsUsuarios']), "ISO-8859-1", "UTF-8");

if($idsCapsulas == ""){
    $idsCapsulas = "0";
}

if($idsUsuarios == ""){
    $idsUsuarios = "0";
}   

$consulta = $objPlanificacion->planEditarPlanificacion($planificacionId, $clienteId, $administradorId, $planificacionNombre, $planificacionDescripcion,  $planificacionEstado, $idsCapsulas, $idsUsuarios, $usuarioModificacion);

if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>
