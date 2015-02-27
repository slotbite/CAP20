<?
session_start();
include("../default.php");

require('clase/capsula.class.php');
$objCapsula = new Capsula();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

$temaLike = mb_convert_encoding(trim($_REQUEST['term']), "ISO-8859-1", "UTF-8");

if(mb_convert_encoding(trim($_REQUEST['caso']), "ISO-8859-1", "UTF-8") != ""){
    $caso = 1;
}
else{
    $caso = 0;
}

$temas = $objCapsula->capMostrarTemas($clienteId, $administradorId, $temaLike, $caso);
//$data = '{[';

if ($temas) {
    while ($listaTemas = $base_datos->sql_fetch_assoc($temas)) {
        //$data .= '{"temaId":"' . $listaTemas['temaId'] . '", "temaNombre":"' . htmlentities($listaTemas['temaNombre']) . '"},';
        $results[] = array('value' => mb_convert_encoding(trim($listaTemas['temaNombre']), "UTF-8", "ISO-8859-1"), 'label' => mb_convert_encoding(trim($listaTemas['temaNombre']), "UTF-8", "ISO-8859-1"), 'temaId' => $listaTemas['temaId'], 'temaUrl' => $listaTemas['temaImagen']);
    }
}

//$data = substr($data, 0, strlen($data) - 1);
//$data .= ']}';

//echo $data;
echo json_encode($results);

?>

