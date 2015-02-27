<?php

session_start();

include("../default.php");
require('clases/areas.class.php');

$areaId = $_GET['areaId'];
$objArea = new areas();
$objArea2 = new areas();

$consulta = $objArea2->manBusquedaCascada($areaId);
$resultado = mssql_fetch_array($consulta);
$estado = $resultado['estado'];

if ($estado == "OK") {
    if ($objArea->manAnularArea($areaId) == true) {
        echo "";
    } else {
        echo "Se ha producido un error. Por favor, inténtelo más tarde.";
    }
} else {
    echo $estado;
}
?>