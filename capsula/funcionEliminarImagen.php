<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];


$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$capsulaImagenRuta = mb_convert_encoding(trim($_POST['capsulaImagenRuta']), "ISO-8859-1", "UTF-8");


$contenido = $objCapsula->capEliminarImagen($capsulaId, $capsulaVersion, $capsulaImagenRuta);

if ($contenido) {

    $capsulaImagenRuta = mb_convert_encoding(trim($contenido['capsulaImagenRuta']),"UTF-8", "ISO-8859-1");

    if (file_exists($capsulaImagenRuta)) {
        unlink($capsulaImagenRuta);
    }

}


?>