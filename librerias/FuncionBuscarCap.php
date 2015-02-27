<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$perfilId = $_SESSION['perfilId'];
$tema = $_POST['temaNombre'] ? $_POST['temaNombre']: '';



$tema = mb_convert_encoding($tema, "ISO-8859-1", "UTF-8");


$query = "  Select distinct CASE WHEN capsulaNumero is NULL or LTRIM(RTRIM(capsulaNumero)) = '' THEN capsulaNombre ELSE LTRIM(RTRIM(capsulaNumero)) + '.- ' + capsulaNombre END as capsulaNombre
            From Capsulas  c (nolock) 
            inner join Temas t (nolock) on c.temaId = t.temaId
            Where   c.clienteId = $clienteId             
            and     t.temaNombre like LTRIM(RTRIM('" . $tema . "%'))
            and     capsulaEstado = 1";


if($perfilId == "2"){
    
    $query = $query . " and c.usuarioCreacion = '" . $administradorId . "'";    
    
}

$query = $query . " Order by capsulaNombre";


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


