<?php

session_start();

include("../default.php");
require('clases/sectores.class.php');

$sectorId = $_GET['sectorId'];
$objSector = new sectores();
$objSector2 = new sectores();

$consulta = $objSector2->manBusquedaCascada($sectorId);
$resultado = mssql_fetch_array($consulta);
$estado = $resultado['estado'];

if ($estado == "OK") {
    if ($objSector->manAnularSector($sectorId) == true) {
        echo "";
    } else {
        echo "Se ha producido un error. Por favor, inténtelo más tarde.";
    }
} else {
    echo $estado;
}
?>