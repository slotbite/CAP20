<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];

$preguntaId = mb_convert_encoding(trim($_POST['preguntaId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");

$pregunta = $objCapsula->capSeleccionarPregunta($capsulaId, $capsulaVersion, $preguntaId);

$results[] = array('preguntaId' => $pregunta['preguntaId'], 'preguntaTexto' => mb_convert_encoding(trim($pregunta['preguntaTexto']), "UTF-8", "ISO-8859-1"), 'mensajePositivo' => mb_convert_encoding(trim($pregunta['mensajePositivo']), "UTF-8", "ISO-8859-1"), 'mensajeNegativo' => mb_convert_encoding(trim($pregunta['mensajeNegativo']), "UTF-8", "ISO-8859-1"));

echo json_encode($results);
?>