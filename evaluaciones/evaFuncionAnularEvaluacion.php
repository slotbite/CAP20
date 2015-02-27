<?php
session_start();

include("../default.php");
require('clases/evaluaciones.class.php');

$evaluacionId = $_GET['evaluacionId'];
$objEvaluacion = new evaluaciones();
if($objEvaluacion->evaAnularEvaluacion($evaluacionId) == true){
	echo "";
}else{
	echo "Error";
}
?>