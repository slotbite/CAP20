<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/evaluaciones.class.php');
$objEvaluacion = new evaluaciones();

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$perfilId = $_SESSION['perfilId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$consulta = $objEvaluacion->evaBuscarTemasMantenedor($clienteId, $administradorId, $perfilId);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaTemas' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Tema</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaTemas'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['temaNombre'])) . "</td></tr>";
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


