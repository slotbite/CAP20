<?php
header('Content-type: application/json');

include("../default.php");
require('clase/capsula.class.php');


// INSTANCIANDO LA CAPSULA
$objCapsula = new Capsula();



$total_preguntas 		= 	$_GET["total_preguntas"];
$Nombre_cap 			= 	$_GET["Nombre_cap"];
$capsulaId 				= 	$_GET["capsulaId"];


$stmt = mssql_init("AcapGuardarFeedbackUsuario");

mssql_bind($stmt, '@total_preguntas', $Nombre_cap, SQLVARCHAR);
mssql_bind($stmt, '@Nombre_cap', $Nombre_cap, SQLVARCHAR);
mssql_bind($stmt, '@capsulaId', $capsulaId, SQLVARCHAR);

$base_datos->sql_ejecutar_sp($stmt);






// retorna un json (mensaje) para comprobar 
$mensaje["mensaje"] 		= 'DATOS RECIVIDOS';
$mensaje["envio"]   		=$contenidoId.$comentario.$envio ;
$mensaje["usuarioId"]		=$usuarioId ;
$mensaje["capsulaId"]		=$capsulaId ;
$mensaje["capsulaVersion"]	=$capsulaVersion;

echo $_GET['jsoncallback'] . '(' . json_encode(    $mensaje     ) . ');'; 
?>