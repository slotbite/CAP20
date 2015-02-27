<?php
session_start();

include("../default.php");
require('clases/usuarios.class.php');

$usuarioId=$_GET['usuarioId'];
$objUsuario=new usuarios();
if( $objUsuario->manAnularUsuario($usuarioId) == true){
	echo "";
}else{
	echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>