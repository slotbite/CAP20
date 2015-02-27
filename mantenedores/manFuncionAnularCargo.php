<?php
session_start();

include("../default.php");
require('clases/cargos.class.php');

$cargoId=$_GET['cargoId'];
$objCargo=new cargos();
if( $objCargo->manAnularCargo($cargoId) == true){
	echo "";
}else{
	echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>