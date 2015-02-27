<?


include ("../librerias/conexion.php");

    $Query = "xp_cmdshell 'whoami.exe'";    
    //echo $Query;    
    $result = $base_datos->sql_query($Query);

echo $result;

    if ($result) {

        $enlace = "../extracciones/" . trim($cliente_id) . "/Extracciones.csv";//$_GET['id'];
        header ("Content-Disposition: attachment; filename=Extracción.csv");
        header ("Content-Type: application/force-download");
        header ("Content-Length: ".filesize($enlace));
        readfile($enlace);

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