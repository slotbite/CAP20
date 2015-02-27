<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/ayudas.class.php');
$Objayudas = new ayudas();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$consulta = $Objayudas->planBuscarEvaluaciones($clienteId);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaEvaluaciones' class='tabla'>";
    //$lista.= '<tr><th></th><th>Evaluación</th></tr></thead><tbody>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaEvaluaciones'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['evaluacionNombre'])) . "</td></tr>";
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

