<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$perfilId = $_SESSION['perfilId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}
$query = "  Select distinct temaNombre 
            From Temas t (nolock) 
			inner join Capsulas c (nolock) on c.temaId = t.temaId
            Where 	c.clienteId = $clienteId 
            and 	t.temaEstado = 1";


if($perfilId == "2"){
    $query = $query . " and c.usuarioCreacion = " . $administradorId . "";
    
}

$query = $query . " Order by t.temaNombre";

//echo $query;

$resultPr = $base_datos->sql_query($query);

if ($resultPr) {

    $count = 0;

    $lista = "<table id='tablaListaTemas' class='tabla'><tbody>";
    //$lista.= '<tr><td></td><td>Tema</td></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($resultPr)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaTemas'/></td>";
        $lista.= "<td style='width:200px;' align='left'>" . htmlentities(trim($row['temaNombre'])) . "</td></tr>";
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


