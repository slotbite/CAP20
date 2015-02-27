<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];

$contenidoId = mb_convert_encoding(trim($_POST['contenidoId']), "ISO-8859-1", "UTF-8");
$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$contenidoTipo = mb_convert_encoding(trim($_POST['contenidoTipo']), "ISO-8859-1", "UTF-8");

$contenido = $objCapsula->capEliminarContenido($capsulaId, $capsulaVersion, $contenidoId, $contenidoTipo);


//if ($contenido) {
//
//    $capsulaImagenRuta = mb_convert_encoding(trim($contenido['contenidoUrl']),"UTF-8", "ISO-8859-1");
//        
//    if (file_exists($capsulaImagenRuta)) {
//        unlink($capsulaImagenRuta);
//    }
//
//}
?>