<?php
session_start();

include("../default.php");
require('clases/grupos.class.php');
$objGrupo = new grupos();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$usuarioModificacion = $_SESSION['usuario'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}
$grupoNombre = mb_convert_encoding(trim($_POST['grupoNombre']), "ISO-8859-1", "UTF-8");
$grupoDescripcion = mb_convert_encoding(trim($_POST['grupoDescripcion']), "ISO-8859-1", "UTF-8");
$consulta = $objGrupo->manGuardarGrupo($clienteId, $administradorId, trim($grupoNombre), trim($grupoDescripcion), $usuarioModificacion);

if ($consulta) {

    $data = "";
    
    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $data .= $row['estado'];
    }
    
    echo trim($data);
    
    
}
?>
