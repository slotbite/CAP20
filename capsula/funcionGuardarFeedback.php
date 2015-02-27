<?php
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$administradorId 		=   $_POST['administradorId'];
$id_feedback 			= 	$_POST["id_feedback"];
$capsulaId 				= 	$_POST["capsulaId"];
$capsulaVersion 		= 	$_POST["capsulaVersion"];
//$contenidoFeedback		= 	$_POST["contenidoFeedback"];
$c1						= 	$_POST["c1"];
$c2						= 	$_POST["c2"];
$c3						= 	$_POST["c3"];
$c4						= 	$_POST["c4"];
$c5						= 	$_POST["c5"];


/* echo "<pre>";
print_r($_POST);
echo "</pre>"; */

//METODO
$feedback = $objCapsula->capCrearFeedback($capsulaId, $capsulaVersion, $id_feedback, $administradorId,$c1,$c2,$c3,$c4,$c5 );

///print_r($feedback );

//echo 'asasdasd';
$results[] 	= array	(
					'id_feedback' 		=> $feedback['id_feedback'],
					//'contenidoFeedback' => $feedback['contenidoFeedback'],
					'o1' 				=> $feedback['o1'],
					'o2' 				=> $feedback['o2'],
					'o3' 				=> $feedback['o3'],
					'o4' 				=> $feedback['o4'],
					'o5' 				=> $feedback['o5']
					);
echo json_encode($results);
?>