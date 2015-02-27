<?PHP

session_start();
include ("../librerias/conexion.php");

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

/*
require('clases/grupos.class.php');
$objGrupo = new grupos();



$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");
$areaNombre = mb_convert_encoding(trim($_POST['areaNombre']), "ISO-8859-1", "UTF-8");


$grupoId = 0;

$consulta = $objGrupo->manBuscarUsuarios($grupoId, $clienteId, $organizacionNombre, $sectorNombre, $areaNombre);

*/

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$organizacionNombre = mb_convert_encoding(trim($_POST['organizacionNombre']), "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding(trim($_POST['sectorNombre']), "ISO-8859-1", "UTF-8");
$areaNombre = mb_convert_encoding(trim($_POST['areaNombre']), "ISO-8859-1", "UTF-8");
$ids = mb_convert_encoding(trim($_POST['ids']), "ISO-8859-1", "UTF-8");

$organizacionNombre = str_replace("\'", "''", $organizacionNombre);
$organizacionNombre = str_replace('\"', '"', $organizacionNombre);                    
        
$sectorNombre = str_replace("\'", "''", $sectorNombre);
$sectorNombre = str_replace('\"', '"', $sectorNombre);
        
$areaNombre = str_replace("\'", "''", $areaNombre);
$areaNombre = str_replace('\"', '"', $areaNombre); 


$queryUsuarios = "      Select u.usuarioId, (LTRIM(RTRIM(u.usuarioNombres)) + ' ' + LTRIM(RTRIM(u.usuarioApellidos))) as 'nombre', u.usuarioEmail, o.organizacionNombre, s.sectorNombre, a.areaNombre, c.cargoNombre
                        From Usuarios u (nolock)
                        left join Organizaciones o (nolock) on u.organizacionId = o.organizacionId
						left join Sectores s (nolock) on u.sectorId = s.sectorId and s.organizacionId = o.organizacionId
						left join Areas a (nolock) on u.areaId = a.areaId and a.organizacionId = o.organizacionId and a.sectorId = s.sectorId
                        left join Cargos c (nolock) on u.cargoId = c.cargoId
                        Where	u.clienteId = ". $clienteId ."
                        and     u.usuarioEstado = 1
                        and     o.organizacionNombre like LTRIM(RTRIM('".$organizacionNombre."%'))";

if($sectorNombre != ""){
    $queryUsuarios = $queryUsuarios. " and s.sectorNombre like LTRIM(RTRIM('".$sectorNombre."%'))";
}

if($areaNombre != ""){
    $queryUsuarios = $queryUsuarios. " and a.areaNombre like LTRIM(RTRIM('".$areaNombre."%'))";
}

if($ids != ""){
    $queryUsuarios = $queryUsuarios. "and u.usuarioId not in (".$ids.")";
}

$queryUsuarios = $queryUsuarios. " Order by 2";

$consulta = $base_datos->sql_query($queryUsuarios);

if ($consulta) {

    $count = 0;

    $lista = "<table id='ListadeUsuarios' class='tabla'><thead style='display:none'>";
    $lista.= "<tr><th></th><th class='sortable'>Nombre</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";

    while ($row = $base_datos->sql_fetch_assoc($consulta)) {
        $lista.= "<tr><td style='width:25px;' align='center'><input type='checkbox'/><input type='hidden' id='usuarioId' value='" . htmlentities(trim($row['usuarioId'])) . "'/></td>";
        $lista.= "<td style='width:180px;'>".htmlentities(trim($row['nombre'])) . "</td><td style='width:179px;'>" . htmlentities(trim($row['organizacionNombre'])) . "</td><td style='width:180px;'>" . htmlentities(trim($row['sectorNombre'])) . "</td><td style='width:180px;'>" . htmlentities(trim($row['areaNombre'])) . "</td><td style='width:179px;'>" . htmlentities(trim($row['cargoNombre'])) . "</td></tr>";
        $count = $count + 1;
    }
    $lista.= "</tbody></table>";
    echo $lista;

    if ($count == 0) {
        echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
    }
} else {
    echo "<label style='color: #FF0000; font-size: 12px'>Búsqueda sin resultados.</label>";
}
?>


