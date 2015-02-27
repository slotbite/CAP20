<?

session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

//$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

$yyyy = date("Y");

$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");


//$ruta = "../multimedia/" . $yyyy . "/CAP_" . (string) $capsulaId . "_" . (string) $capsulaVersion;

$ruta = "../multimedia/" . $yyyy . "/";

foreach ($_FILES as $key) {

    if ($key['error'] == UPLOAD_ERR_OK) {

        $nombre = $key['name'];
        $temporal = $key['tmp_name'];
        $tipo = $key['type']; //Obtenemos el nombre del archivo temporal
        //$tamano = ($key['size'] / 1000) . "Kb"; //Obtenemos el tamaño en KB
        $tamano = $key['size'];

        if ($tipo == "image/png" || $tipo == "image/x-png" || $tipo == "image/jpeg" || $tipo == "image/pjpeg" || $tipo == "image/gif") {

            if ($tamano <= 5242880) { // 5MB=5242880 (1024 x 1024 x 5), 2MB=2097152
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0700);
                }

                $ruta = $ruta . "/CAP_" . (string) $capsulaId . "_" . (string) $capsulaVersion;

                if (!file_exists($ruta)) {
                    mkdir($ruta, 0700);
                }

                $nombre = mb_convert_encoding(trim($nombre), "ISO-8859-1", "UTF-8");

                $capsulaImagen = trim($ruta . "/" . $nombre);

                $filaResultado = $objCapsula->capGuardarImagen($capsulaId, $capsulaVersion, $capsulaImagen, $administradorId);

                if (trim($filaResultado['estado']) == 1) {
                    move_uploaded_file($temporal, $capsulaImagen);
                    //echo "<script>parent.$('#imagenCargada').html('');</script> ";
                    echo "<script>parent.$('#imagenCargada').html('');</script> ";
                    echo "<script>parent.$('#ulImagenes').prepend(\"<li><img src='" . htmlentities($capsulaImagen) . "' alt='' /></li>\");</script> ";
                    echo "<script>parent.$('#divVistaPrevia').prepend(\"<img src='" . htmlentities($capsulaImagen) . "' alt='' />\");</script> ";
                    echo "<script>parent.habilitarImagenes();</script> ";
                } else {
                    echo "<script>parent.$('#imagenCargada').html('Ya existe una imagen con el mismo nombre.');</script> ";
                }
            } else {
                echo "<script>parent.$('#imagenCargada').html('El archivo pesa sobre los 5MB.');</script> ";
            }
        } else {
            echo "<script>parent.$('#imagenCargada').html('Archivo incorrecto. Sólo se aceptan archivos del tipo PNG, JPEG y GIF.');</script> ";
        }
    } else {
        echo "<script>parent.$('#imagenCargada').html('No se ha podido cargar el archivo.');</script> ";
        //echo $key['error']; //Si no se cargo mostramos el error
    }
}