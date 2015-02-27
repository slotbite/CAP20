<?php
session_start();

include("../default.php");
require('clases/administradores.class.php');

$administradorId=$_GET['administradorId'];
$objAdministrador=new administradores();
if( $objAdministrador->manAnularAdministrador($administradorId) == true){
	echo "";
}else{
	echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>