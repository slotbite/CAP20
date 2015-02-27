<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$id_feedback 	= mb_convert_encoding(trim($_POST['id_feedback']), "ISO-8859-1", "UTF-8");
$capsulaId 		= mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");


$feedback = $objCapsula->capSeleccionarFeedback($capsulaId, $capsulaVersion, $id_feedback );

$results[] 	= array	(
					'id_feedback' 		=> $feedback['id_feedback'],
					'o1' 				=> $feedback['o1'],
					'o2' 				=> $feedback['o2'],
					'o3' 				=> $feedback['o3'],
					'o4' 				=> $feedback['o4'],
					'o5' 				=> $feedback['o5']
					);
echo json_encode($results);
?>