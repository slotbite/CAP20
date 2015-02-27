<?
session_start();
include("../default.php");

require('clase/capsula.class.php');

//INSTANCIA LA Capsula
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

$preguntaId = mb_convert_encoding(trim($_POST['preguntaId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$preguntaTexto = mb_convert_encoding(trim($_POST['preguntaTexto']), "ISO-8859-1", "UTF-8");
$mensajePositivo = mb_convert_encoding(trim($_POST['mensajePositivo']), "ISO-8859-1", "UTF-8");
$mensajeNegativo = mb_convert_encoding(trim($_POST['mensajeNegativo']), "ISO-8859-1", "UTF-8");


 // echo "<pre>";
// print_r($_POST);
// echo "</pre>"; 

$preguntaTexto = str_replace("\'", "'", $preguntaTexto);
$preguntaTexto = str_replace('\"', '"', $preguntaTexto);

$mensajePositivo = str_replace("\'", "'", $mensajePositivo);
$mensajePositivo = str_replace('\"', '"', $mensajePositivo);

$mensajeNegativo = str_replace("\'", "'", $mensajeNegativo);
$mensajeNegativo = str_replace('\"', '"', $mensajeNegativo);

//METODO
$pregunta = $objCapsula->capGuardarPregunta($capsulaId, $capsulaVersion, $preguntaId, $preguntaTexto, $mensajePositivo, $mensajeNegativo, $administradorId);

//echo $contenido['contenidoDescripcion'];

$results[] = array
( 
'preguntaId' => $pregunta['preguntaId'],
'preguntaTexto' => mb_convert_encoding(trim($pregunta['preguntaTexto']),"UTF-8", "ISO-8859-1"),
'mensajePositivo' => mb_convert_encoding(trim($pregunta['mensajePositivo']),"UTF-8", "ISO-8859-1"),
'mensajeNegativo' => mb_convert_encoding(trim($pregunta['mensajeNegativo']),"UTF-8", "ISO-8859-1"));

echo json_encode($results);

?>