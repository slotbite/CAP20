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

$contenido = $objCapsula->capEliminarPregunta($capsulaId, $capsulaVersion, $preguntaId);

?>