<?php
session_start();
include("../default.php");
// declarando capsula
require('clase/capsula.class.php');
$objCapsula = new Capsula();
//rescatando variables 
$administradorId 		= $_SESSION['administradorId'];

$total_preguntas 		= 	$_GET["total_preguntas"];
$Nombre_cap 			= 	$_GET["Nombre_cap"];
$capsulaId 				= 	$_GET["capsulaId"];
$capsulaVersion 		= 	$_GET["capsulaVersion"];

//  metodo 
$feedback = $objCapsula->capCrearFeedback($total_preguntas, $Nombre_cap, $capsulaId,$capsulaVersion);

$results[] 	= array	('estado' 			=> $feedback['estado'],
					 'capsulaId' 		=> $feedback['capsulaId'],
					 'feedbackId' 		=> $feedback['feedbackId']
					);

echo json_encode($results);


/* // retorna un json (mensaje) para comprobar 
$mensaje["mensaje"] 		= 'DATOS RECIVIDOS';
$mensaje["envio"]   		=$contenidoId.$comentario.$envio ;
$mensaje["usuarioId"]		=$usuarioId ;
$mensaje["capsulaId"]		=$capsulaId ;
$mensaje["capsulaVersion"]	=$capsulaVersion;

echo $_GET['jsoncallback'] . '(' . json_encode(    $mensaje     ) . ');';  */
?>