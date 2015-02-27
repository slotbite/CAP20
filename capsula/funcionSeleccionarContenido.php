<?
session_start();
ini_set('mssql.textlimit', 2147483647);
ini_set('mssql.textsize', 2147483647); 

include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];

$contenidoId = mb_convert_encoding(trim($_POST['contenidoId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");

$contenido = $objCapsula->capSeleccionarContenido($capsulaId, $capsulaVersion, $contenidoId);

//echo $contenido['contenidoDescripcion'];

$results[] = array('contenidoId' => $contenido['contenidoId'], 
                    'contenidoDescripcion' => mb_convert_encoding(trim($contenido['contenidoDescripcion']), "UTF-8", "ISO-8859-1"), 
                    'contenidoUrl' => mb_convert_encoding(trim($contenido['contenidoUrl']), "UTF-8", "ISO-8859-1"),
                    'contenidoObligatorio' => mb_convert_encoding(trim($contenido['contenidoObligatorio']), "UTF-8", "ISO-8859-1"));


echo json_encode($results);

?>