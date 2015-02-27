<?PHP

session_start();
include ("../librerias/conexion.php");
require('clases/ayudas.class.php');
$Objayudas = new ayudas();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");

$organizacionNombre = str_replace("\'", "''", $organizacionNombre);
$organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
$sectorNombre = str_replace("\'", "''", $sectorNombre);
$sectorNombre = str_replace('\"', '"', $sectorNombre);


$queryOrganizaciones = "    Select distinct a.areaNombre
                            From Organizaciones o (nolock)
                            inner join Sectores s (nolock) on o.organizacionId = s.organizacionId
                            inner join Areas a (nolock) on o.organizacionId = a.organizacionId and s.sectorId = a.sectorId
                            Where   o.clienteId = " . $clienteId . "
                            and     o.organizacionNombre like LTRIM(RTRIM('" . $organizacionNombre . "%'))
                            and     s.sectorNombre like LTRIM(RTRIM('" . $sectorNombre . "%'))
                            and     o.organizacionEstado = 1
                            and     s.sectorEstado = 1
                            and     a.areaEstado = 1
                            Order by a.areaNombre";

$consulta = $base_datos->sql_query($queryOrganizaciones);

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


