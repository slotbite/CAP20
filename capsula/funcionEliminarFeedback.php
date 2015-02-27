<?
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$capsulaId 				= $_POST["capsulaId"];
$id_feedback 			= $_POST["id_feedback"];
$capsulaVersion		 	= $_POST["capsulaVersion"];
		
$contenido = $objCapsula->capEliminarFeedback($capsulaId, $id_feedback, $capsulaVersion);

$results[] 	= array	(
					'estado' 		=> $contenido['estado']
					);
echo json_encode($results);
?>