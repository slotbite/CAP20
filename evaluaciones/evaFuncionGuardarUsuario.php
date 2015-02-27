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

$evaluacionId = htmlspecialchars(trim($_POST['evaluacionId']));
$usuarioId = htmlspecialchars(trim($_POST['usuarioId']));


$consulta = $objEvaluacion->evaGuardarUsuario($evaluacionId, $clienteId, $usuarioId, $usuarioModificacion);
        
if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>