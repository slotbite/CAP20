<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['planOrganizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['planSectorNombre']), "ISO-8859-1", "UTF-8");
$areaNombre = mb_convert_encoding(trim($_POST['planAreaNombre']), "ISO-8859-1", "UTF-8");
$grupoNombre = mb_convert_encoding(trim($_POST['planGrupoNombre']), "ISO-8859-1", "UTF-8");
$evaluacionNombre = mb_convert_encoding(trim($_POST['planEvaluacionNombre']), "ISO-8859-1", "UTF-8");
$tipoBusqueda = mb_convert_encoding(trim($_POST['tipoBusqueda']), "ISO-8859-1", "UTF-8");
$ids = mb_convert_encoding(trim($_POST['ids']), "ISO-8859-1", "UTF-8");

//$planificacionId = mb_convert_encoding(trim($_POST['planificacionId']), "ISO-8859-1", "UTF-8");


$consulta = $objPlanificacion->planBuscarUsuarios($clienteId, $organizacionNombre, $sectorNombre, $areaNombre, $grupoNombre, $evaluacionNombre, $tipoBusqueda, $ids);


if ($consulta) {

    $count = 0;

    $lista = "<table id='ListadeUsuarios' class='tabla'><thead style='display:none'>";
    $lista.= "<tr><th></th><th class='sortable'>Usuario</th><th class='sortable'>E-Mail</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='checkbox'/><input type='hidden' id='usuarioId' value='" . htmlentities(trim($row['usuarioId'])) . "'/></td><td style='width:151px;'>";
        $lista.= htmlentities(trim($row['nombre'])) . "</td><td style='width:150px;'>" . htmlentities(trim($row['usuarioEmail'])) . "</td><td style='width:153px;'>" . htmlentities(trim($row['organizacionNombre'])) . "</td><td style='width:151px;'>" . htmlentities(trim($row['sectorNombre'])) . "</td><td style='width:150px;'>" . htmlentities(trim($row['areaNombre'])) . "</td><td style='width:153px;'>" . htmlentities(trim($row['cargoNombre'])) . "</td></tr>";
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


