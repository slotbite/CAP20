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

$evaluacionId = mb_convert_encoding(trim($_POST['evaluacionId']), "ISO-8859-1", "UTF-8");
$temaNombre = mb_convert_encoding(trim($_POST['temaNombre']), "ISO-8859-1", "UTF-8");
$evaluacionNombre = mb_convert_encoding(trim($_POST['evaluacionNombre']), "ISO-8859-1", "UTF-8");
$evaluacionDescripcion = mb_convert_encoding(trim($_POST['evaluacionDescripcion']), "ISO-8859-1", "UTF-8");
$evaluacionEstado = mb_convert_encoding(trim($_POST['evaluacionEstado']), "ISO-8859-1", "UTF-8");


$idsCapsulas = mb_convert_encoding(trim($_POST['idsCapsulas']), "ISO-8859-1", "UTF-8");
$idsPracticas = mb_convert_encoding(trim($_POST['idsPracticas']), "ISO-8859-1", "UTF-8");
$idsUsuarios = mb_convert_encoding(trim($_POST['idsUsuarios']), "ISO-8859-1", "UTF-8");

if($idsCapsulas == ""){
    $idsCapsulas = "0";
}

if($idsPracticas == ""){
    $idsPracticas = "0";
}

if($idsUsuarios == ""){
    $idsUsuarios = "0";
}
    
$consulta = $objEvaluacion->evaEditarEvaluacion($evaluacionId, $clienteId, $administradorId, $temaNombre, $evaluacionNombre, $evaluacionDescripcion, $evaluacionEstado, $idsCapsulas, $idsPracticas, $idsUsuarios, $usuarioModificacion);

if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>
