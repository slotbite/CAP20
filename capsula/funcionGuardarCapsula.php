<?
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];


$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$temaNombre = mb_convert_encoding(trim($_POST['temaNombre']), "ISO-8859-1", "UTF-8");
$capsulaNombre = mb_convert_encoding(trim($_POST['capsulaNombre']), "ISO-8859-1", "UTF-8");
$capsulaDescripcion = mb_convert_encoding(trim($_POST['capsulaDescripcion']), "ISO-8859-1", "UTF-8");
$capsulaTipo = mb_convert_encoding(trim($_POST['capsulaTipo']), "ISO-8859-1", "UTF-8");
$capsulaEstado = mb_convert_encoding(trim($_POST['capsulaEstado']), "ISO-8859-1", "UTF-8");
$capsulaNumero = mb_convert_encoding(trim($_POST['capsulaNumero']), "ISO-8859-1", "UTF-8");

$temaNombre = str_replace("\'", "'", $temaNombre);
$temaNombre = str_replace('\"', '"', $temaNombre);

$capsulaNombre = str_replace("\'", "'", $capsulaNombre);
$capsulaNombre = str_replace('\"', '"', $capsulaNombre);

$capsulaDescripcion = str_replace("\'", "'", $capsulaDescripcion);
$capsulaDescripcion = str_replace('\"', '"', $capsulaDescripcion);


$capsula = $objCapsula->capGuardarCapsula($capsulaId, $capsulaVersion, $clienteId, $temaNombre, $capsulaNombre, $capsulaDescripcion, $capsulaTipo, $capsulaEstado, $administradorId, $capsulaNumero);

$results[] = array('estado' => $capsula['estado'], 'capsulaId' => $capsula['capsulaId'], 'capsulaVersion' => $capsula['capsulaVersion'], 'temaId' => $capsula['temaId'], 'temaUrl' => $capsula['temaUrl'], 'capsulaNumero' => $capsula['capsulaNumero']);

echo json_encode($results);

?>