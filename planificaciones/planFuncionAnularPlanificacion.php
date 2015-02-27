<?php
session_start();

include("../default.php");
require('clases/planificaciones.class.php');

$planificacionId = $_GET['planificacionId'];
$objPlanificacion = new planificaciones();
if($objPlanificacion->planAnularPlanificacion($planificacionId) == true){
	echo "";
}else{
	echo "Error";
}
?>