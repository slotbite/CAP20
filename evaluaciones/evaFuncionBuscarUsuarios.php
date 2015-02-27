<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");
$areaNombre = mb_convert_encoding(trim($_POST['areaNombre']), "ISO-8859-1", "UTF-8");
$grupoNombre = mb_convert_encoding(trim($_POST['grupoNombre']), "ISO-8859-1", "UTF-8");
$tipoBusqueda = mb_convert_encoding(trim($_POST['tipoBusqueda']), "ISO-8859-1", "UTF-8");
$ids = mb_convert_encoding(trim($_POST['ids']), "ISO-8859-1", "UTF-8");


$consulta = $objEvaluacion->evaBuscarUsuarios($clienteId, $organizacionNombre, $sectorNombre, $areaNombre, $grupoNombre, $tipoBusqueda, $ids);

if ($consulta) {

    $count = 0;

    $lista = "<table id='ListadeUsuarios' class='tabla'><thead style='display:none'>";
    $lista.= "<tr><th></th><th class='sortable'>Usuario</th><th class='sortable'>E-Mail</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:32px;' align='center'><input type='checkbox'/><input type='hidden' id='usuarioId' value='" . htmlentities(trim($row['usuarioId'])) . "'/></td><td style='width:147px;'>";
        $lista.= htmlentities(trim($row['nombre'])) . "</td><td style='width:147px;'>" . htmlentities(trim($row['usuarioEmail'])) . "</td><td style='width:147px;'>" . htmlentities(trim($row['organizacionNombre'])) . "</td><td style='width:147px;'>" . htmlentities(trim($row['sectorNombre'])) . "</td><td style='width:147px;'>" . htmlentities(trim($row['areaNombre'])) . "</td><td style='width:147px;'>" . htmlentities(trim($row['cargoNombre'])) . "</td></tr>";
        $count = $count + 1;
    }
    
    $lista.= "</tbody></table>";
    echo $lista;

    if ($count == 0) {
        echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
    }
} else {
    echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
}
?>


