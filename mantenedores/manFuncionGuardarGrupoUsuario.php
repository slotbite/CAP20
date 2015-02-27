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
$usuarioId = mb_convert_encoding(trim($_POST['usuarioId']), "ISO-8859-1", "UTF-8");


$consulta = $objGrupo->manGuardarGrupoUsuario($grupoId, $clienteId, $usuarioId, $usuarioModificacion);
        
if ($consulta) {

    $resultado = mssql_fetch_array($consulta);
    echo $resultado['estado'];
}
?>