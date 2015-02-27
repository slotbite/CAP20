<?

session_start();
include ("../librerias/conexion.php");


//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=Extraccion.xls");
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Pragma: public");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("Accept-Ranges: bytes");

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

$temaNombre = $_GET['temaNombre'] ? $_GET['temaNombre'] : '';
$capsulaNombre = $_GET['capsulaNombre'] ? $_GET['capsulaNombre'] : '';
$organizacionNombre = $_GET['organizacionNombre'] ? $_GET['organizacionNombre'] : '';
$sectorNombre = $_GET['sectorNombre'] ? $_GET['sectorNombre'] : '';
$areaNombre = $_GET['areaNombre'] ? $_GET['areaNombre'] : '';
$tipo = $_GET['tipo'] ? $_GET['tipo'] : 2;
$fechaInicio = $_GET['fechaInicio'] ? $_GET['fechaInicio'] : '';
$fechaFin = $_GET['fechaFin'] ? $_GET['fechaFin'] : '';

$temaNombre = mb_convert_encoding($temaNombre, "ISO-8859-1", "UTF-8");
$capsulaNombre = mb_convert_encoding($capsulaNombre, "ISO-8859-1", "UTF-8");
$organizacionNombre = mb_convert_encoding($organizacionNombre, "ISO-8859-1", "UTF-8");
$sectorNombre = mb_convert_encoding($sectorNombre, "ISO-8859-1", "UTF-8");
$areaNombre = mb_convert_encoding($areaNombre, "ISO-8859-1", "UTF-8");


$ruta = "../extracciones/" . $cliente_id . "/";

if (!file_exists($ruta)) {
    mkdir($ruta, 0700);
}


if ($fechaInicio != '' && $fechaFin != '') {    

    $Query = "exec repExtraerDatos '$temaNombre','$capsulaNombre','$organizacionNombre','$sectorNombre','$areaNombre',$tipo,'$fechaInicio','$fechaFin','$cliente_id'";    
    //echo $Query;    
    $result = $base_datos->sql_query($Query);


    if ($result) {

        $enlace = "../extracciones/" . trim($cliente_id) . "/Extracciones.csv";//$_GET['id'];
        header ("Content-Disposition: attachment; filename=Extracción.csv");
        header ("Content-Type: application/force-download");
        header ("Content-Length: ".filesize($enlace));
        readfile($enlace);

    }
}



//if($fechaInicio!='' && $fechaFin!=''){
//$fileName = 'Reporte_Extraccion.xls';
//$codigo="";
//$codigo=$codigo."<TABLE BORDER='1'>";
//$codigo=$codigo."<TR>";
//$codigo=$codigo."<TD>ID_USUARIO</TD>";
//$codigo=$codigo."<TD>NOMBRE_USUARIO</TD>";
//$codigo=$codigo."<TD>ID_TEMA</TD>";
//$codigo=$codigo."<TD>TEMA</TD>";
//$codigo=$codigo."<TD>ID_CAPSULA</TD>";
//$codigo=$codigo."<TD>CAPSULA</TD>";
//$codigo=$codigo."<TD>ID_PREGUNTA</TD>";
//$codigo=$codigo."<TD>PREGUNTA</TD>";
//$codigo=$codigo."<TD>ID_RESPUESTA</TD>";
//$codigo=$codigo."<TD>RESPUESTA</TD>";
//$codigo=$codigo."<TD>FECHA_RESPUESTA</TD>";
//$codigo=$codigo."<TD>FECHA_ENVIO</TD>";
//$codigo=$codigo."<TD>FECHA_CIERRE</TD>";
//$codigo=$codigo."<TD>ORGANIZACION</TD>";
//$codigo=$codigo."<TD>GERENCIA/AGENCIA</TD>";
//$codigo=$codigo."<TD>AREA</TD>";
//$codigo=$codigo."<TD>COMENTARIOS</TD>";
//$codigo=$codigo."</TR>";
//
//
//
////echo $Query;
//
//
//$result = $base_datos->sql_query($Query);
//	while ($row = $base_datos->sql_fetch_assoc($result)) {
//		$codigo=$codigo."<TR><TD>".$row['ID_USUARIO']."</TD><TD>".htmlentities($row['NOMBRE_USUARIO'])."</TD><TD>".htmlentities($row['ID_TEMA'])."</TD><TD>".htmlentities($row['TEMA'])."</TD><TD>".$row['ID_CAPSULA']."</TD><TD>".htmlentities($row['CAPSULA'])."</TD><TD>".$row['ID_PREGUNTA']."</TD><TD>".htmlentities($row['PREGUNTA'])."</TD><TD>".$row['ID_RESPUESTA']."</TD><TD>".$row['RESPUESTA']."</TD><TD>".$row['fResp']."</TD><TD>".$row['fEnvio']."</TD><TD>".$row['fCierre']."</TD><TD>".$row['organizacionNombre']."</TD><TD>".$row['sectorNombre']."</TD><TD>".$row['areaNombre']."</TD><TD>".mb_convert_encoding($row['comentarios'], "ISO-8859-1", "UTF-8")."</TD></tr>";										
//	}	
//
//$codigo=$codigo."</table>";
//echo $codigo;
//}
?>