<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");

$organizacionNombre = str_replace("\'", "''", $organizacionNombre);
$organizacionNombre = str_replace('\"', '"', $organizacionNombre); 

$queryOrganizaciones = "    Select distinct s.sectorNombre
                            From Organizaciones o (nolock)
                            inner join Sectores s (nolock) on o.organizacionId = s.organizacionId
                            Where   o.clienteId = " . $clienteId . "
                            and     o.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%'))
                            and     o.organizacionEstado = 1
                            and     s.sectorEstado = 1
                            Order by s.sectorNombre";

$consulta = $base_datos->sql_query($queryOrganizaciones);

if ($consulta) {

    $count = 0;

    $lista = "<table id='tablaListaSectores' class='tabla'><tbody>";
    //$lista.= '<tr><th></th><th>Organización</th><th>Gerencia/Agencia</th></tr></thead>';

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:16px;'><input type='radio' name='listaSectores'/></td>";
        $lista.= "<td style='width:200px;'>" . htmlentities(trim($row['sectorNombre'])) . "</td></tr>";
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


