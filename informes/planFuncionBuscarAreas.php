<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/planificaciones.class.php');
$objPlanificacion = new planificaciones();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");

$consulta = $objPlanificacion->planBuscarAreas($clienteId, $organizacionNombre, $sectorNombre);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaAreas' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Organización</th><th>Gerencia/Agencia</th><th>Área</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaAreas'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['areaNombre'])) . "</td></tr>";
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


