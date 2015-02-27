<?php
session_start();

include("../default.php");
require('clases/clientes.class.php');

$clienteId=$_GET['cliClienteId'];
$objCliente=new clientes();
if( $objCliente->manAnularCliente($clienteId) == true){
	echo "";
}else{
	echo "Se ha producido un error. Por favor, inténtelo más tarde.";
}
?>