<?php
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();


$pk_id_feedback 		= 	$_POST["pk_id_feedback"]; //fk
$capsulaId 				= 	$_POST["capsulaId"];
$capsulaVersion 		= 	$_POST["capsulaVersion"];
$administradorId 		=   $_POST['administradorId'];
$contenidoAlternativa	= 	$_POST["contenidoAlternativa"];


/* echo "<pre>";
print_r($_POST);
echo "</pre>"; */

//METODO
$alternativa = $objCapsula->capCrearAlternativa($capsulaId, $capsulaVersion, $pk_id_feedback, $administradorId,$contenidoAlternativa );

///print_r($alternativa );

$results[] 	= array	(
					'id_alternativa' 		=> $alternativa['id_alternativa'],
					'contenidoAlternativa' => $alternativa['contenidoAlternativa']
					);
echo json_encode($results);
?>