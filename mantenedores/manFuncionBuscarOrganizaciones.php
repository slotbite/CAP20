<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}


$queryOrganizaciones = "   Select distinct o.organizacionNombre  
                            From Organizaciones o (nolock)                            
                            Where   o.clienteId = '" . $clienteId . "'
                            and     o.organizacionEstado = 1
                            Order by o.organizacionNombre";

$consulta = $base_datos->sql_query($queryOrganizaciones);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaOrganizaciones' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Organización</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaOrganizaciones'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['organizacionNombre'])) . "</td></tr>";
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


