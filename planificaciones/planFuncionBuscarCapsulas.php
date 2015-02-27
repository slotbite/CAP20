<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$perfilId = $_SESSION['perfilId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$nombreTema = mb_convert_encoding(trim($_POST['nombreTema']), "ISO-8859-1", "UTF-8");
$ids = mb_convert_encoding(trim($_POST['ids']), "ISO-8859-1", "UTF-8");

$consulta = $objPlanificacion->planBuscarCapsulas($clienteId, $nombreTema, $administradorId, $perfilId, $ids);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaCapsulas' class='tabla'>";
    $lista.= '<tr><th><input type="checkbox" onclick="planSeleccionarTodoCapsulas(this.checked)" title="Seleccionar Todo"/></th><th>Tema</th><th>Cápsula</th><th style="display:none"></th><th>Tipo</th></tr></thead><tbody>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='checkbox'/></td><td style='width:200px;'>";
        $lista.= htmlentities(trim($row['temaNombre'])) . "</td><td style='width:400px;'>" . htmlentities(trim($row['capsulaNombre'])) . "</td><td style='display:none'><input type='hidden' id='capsulaId' value='" . htmlentities(trim($row['capsulaId'])) . "'/></td><td style='width:100px;'>" . htmlentities(trim($row['capsulaTipo'])) . "</td></td></tr>";
        $count = $count + 1;
    }
    $lista.= "</tbody></table>";
            
    if ($count == 0) {
        echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
    }
    else{
        echo $lista;
    }
    
} else {
    echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
}
?>


