<?php
session_start();

include("../default.php");
require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$temaNombre = mb_convert_encoding(trim($_POST['temaNombre']), "ISO-8859-1", "UTF-8");
$evaluacionNombre = mb_convert_encoding(trim($_POST['evaluacionNombre']), "ISO-8859-1", "UTF-8");
$evaluacionDescripcion = mb_convert_encoding(trim($_POST['evaluacionDescripcion']), "ISO-8859-1", "UTF-8");

$consulta = $objEvaluacion->evaGuardarCabecera($clienteId, $administradorId, trim($temaNombre), trim($evaluacionNombre), trim($evaluacionDescripcion), $usuarioModificacion);


if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>
