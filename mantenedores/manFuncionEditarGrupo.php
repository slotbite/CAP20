<?php
session_start();

include("../default.php");
require('clases/grupos.class.php');
$objGrupo = new grupos();

$clienteId = $_SESSION['clienteId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$grupoId = mb_convert_encoding(trim($_POST['grupoId']), "ISO-8859-1", "UTF-8");
$grupoNombre = mb_convert_encoding(trim($_POST['grupoNombre']), "ISO-8859-1", "UTF-8");
$grupoDescripcion = mb_convert_encoding(trim($_POST['grupoDescripcion']), "ISO-8859-1", "UTF-8");
$grupoEstado = mb_convert_encoding(trim($_POST['grupoEstado']), "ISO-8859-1", "UTF-8");
$usuariosEliminados = mb_convert_encoding(trim($_POST['ids']), "ISO-8859-1", "UTF-8");

$consulta = $objGrupo->manEditarGrupo($grupoId, $clienteId, trim($grupoNombre), trim($grupoDescripcion), $usuariosEliminados, $grupoEstado, $usuarioModificacion);

if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>
