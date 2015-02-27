<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$consulta = $objEvaluacion->evaBuscarGrupos($clienteId);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaGrupos' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Grupo</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaGrupos'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['grupoNombre'])) . "</td></tr>";
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


