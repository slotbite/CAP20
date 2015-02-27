<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

$tema = $_POST['temaId'] ? $_POST['temaId']:0;
$query="Select distinct CASE WHEN capsulaNumero is NULL or LTRIM(RTRIM(capsulaNumero)) = '' THEN capsulaNombre ELSE LTRIM(RTRIM(capsulaNumero)) + '.- ' + capsulaNombre END as capsulaNombre from capsulas where clienteId=$clienteId and temaId=$tema order by capsulaNombre";
$resultPr = $base_datos->sql_query($query);
//echo $query;
if ($resultPr) {

    $count = 0;

    $lista = "<table id='tablaListaCap' class='tabla'><tbody>";
    //$lista.= '<tr><td></td><td>C&aacute;psulas</td></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($resultPr)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaTemas'/></td>";
        $lista.= "<td style='width:200px;' align='left'>" . htmlentities(trim($row['capsulaNombre'])) . "</td></tr>";
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


