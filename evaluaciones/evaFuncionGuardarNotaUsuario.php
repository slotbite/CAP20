<?php
session_start();

include("../default.php");
require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$clienteId = $_SESSION['clienteId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$evaluacionId = mb_convert_encoding(trim($_POST['evaluacionId']), "ISO-8859-1", "UTF-8");
$usuarioId = mb_convert_encoding(trim($_POST['usuarioId']), "ISO-8859-1", "UTF-8");
$practicaId = mb_convert_encoding(trim($_POST['practicaId']), "ISO-8859-1", "UTF-8");
$nota = mb_convert_encoding(trim($_POST['nota']), "ISO-8859-1", "UTF-8");
$ponderacion = mb_convert_encoding(trim($_POST['ponderacion']), "ISO-8859-1", "UTF-8");


$consulta = $objEvaluacion->evaGuardarNotaUsuario($clienteId, $evaluacionId, $usuarioId, $practicaId, $nota, $ponderacion, $usuarioModificacion);
        
if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>