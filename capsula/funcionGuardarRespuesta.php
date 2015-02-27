<?
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

$preguntaId = mb_convert_encoding(trim($_POST['preguntaId']), "ISO-8859-1", "UTF-8");
$respuestaId = mb_convert_encoding(trim($_POST['respuestaId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$respuestaTexto = mb_convert_encoding(trim($_POST['respuestaTexto']), "ISO-8859-1", "UTF-8");
$respuestaCorrecta = mb_convert_encoding(trim($_POST['respuestaCorrecta']), "ISO-8859-1", "UTF-8");
$alternativa = mb_convert_encoding(trim($_POST['alternativa']), "ISO-8859-1", "UTF-8");
$orden = mb_convert_encoding(trim($_POST['orden']), "ISO-8859-1", "UTF-8");
$respuestaId = 0;

$respuestaTexto = str_replace("\'", "'", $respuestaTexto);
$respuestaTexto = str_replace('\"', '"', $respuestaTexto);

$respuesta = $objCapsula->capGuardarRespuesta($capsulaId, $capsulaVersion, $preguntaId, $respuestaId, $respuestaTexto, $respuestaCorrecta, $alternativa, $orden, $administradorId);

echo $respuesta['respuestaId'];

?>