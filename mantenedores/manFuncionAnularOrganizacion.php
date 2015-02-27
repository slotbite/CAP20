<?php
session_start();

include("../default.php");
require('clases/organizaciones.class.php');

$organizacionId = $_GET['organizacionId'];
$objOrganizacion = new organizaciones();
$objOrganizacion2 = new organizaciones();

$consulta = $objOrganizacion2->manBusquedaCascada($organizacionId);
$resultado = mssql_fetch_array($consulta);
$estado = $resultado['estado'];

if ($estado == "OK") {    
    if ($objOrganizacion->manEliminarOrganizacion($organizacionId) == true) {
        echo "";
    } else {
        echo "Se ha producido un error. Por favor, inténtelo más tarde.";
    }
} else {
    echo $estado;
}
?>