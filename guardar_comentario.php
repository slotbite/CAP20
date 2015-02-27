<?php
header('Content-type: application/json');
include ("librerias/conexion.php");
include ("librerias/crypt.php");
session_start();

/* 
	-- $_SESSION : incopativilidad con jQuery  
	-- Se manda los datos por un json {  ID: 111 , X:X  }
*/

// recibiendo las variables  del jsonp
$contenidoId 	= 	$_GET["ID"];
$comentario 	= 	$_GET["Comentario"];
$comentario 	= 	mb_convert_encoding(trim($comentario), "ISO-8859-1", "UTF-8");
$envio 			= 	$_GET["envio"];
$usuarioId 		= 	$_GET["usuarioId"];
$capsulaId 		= 	$_GET["capsulaid"];
$capsulaVersion = 	$_GET["version"];


$stmt = mssql_init("AcapGuardarComentarioUsuario");

mssql_bind($stmt, '@envioId', $envio, SQLVARCHAR);
mssql_bind($stmt, '@capsulaId', $capsulaId, SQLVARCHAR);
mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLVARCHAR);
mssql_bind($stmt, '@contenidoId', $contenidoId, SQLVARCHAR);
mssql_bind($stmt, '@usuarioId', $usuarioId, SQLVARCHAR);
mssql_bind($stmt, '@comentario', $comentario, SQLTEXT);

$base_datos->sql_ejecutar_sp($stmt);


// retorna un json (mensaje) para comprobar 
$mensaje["mensaje"] 		= 'DATOS RECIVIDOS';
$mensaje["envio"]   		=$contenidoId.$comentario.$envio ;
$mensaje["usuarioId"]		=$usuarioId ;
$mensaje["capsulaId"]		=$capsulaId ;
$mensaje["capsulaVersion"]	=$capsulaVersion;

echo $_GET['jsoncallback'] . '(' . json_encode(    $mensaje     ) . ');'; 
?>