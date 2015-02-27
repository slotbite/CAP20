<?

session_start();
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
//$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
//$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;

$capsula_id = $_POST['capsula'] ? $_POST['capsula'] : 0;
$queryTipo = "select MAX(capsulaVersion) as capsulaVersion from Capsulas where capsulaID=$capsula_id and clienteID=$cliente_id";
$result = $base_datos->sql_query($queryTipo);
$row = $base_datos->sql_fetch_assoc($result);
$capsulaVersion = $row["capsulaVersion"] ? $row["capsulaVersion"] : 1;


$_SESSION['capsulaId2'] = $capsula_id;
$_SESSION['capsulaVersion2'] = $capsulaVersion;

$usuarioIdEnvio = $_POST['usuarioIdEnvio'] ? $_POST['usuarioIdEnvio'] : 0;
//"capsula_id=".$capsula_id ."&version=".$capsulaVersion."&usuarioid=".$usuarioIdEnvio
$grupoId = $_POST['grupoId'] ? $_POST['grupoId'] : 0;
$envioId = $_POST['envioId'] ? $_POST['envioId'] : 0;
$fechacierre = $_POST['fechacierre'] ? $_POST['fechacierre'] : '';

//if($grupoId==0){

$query = "EXEC envTraeDatosUsuario " . $usuarioIdEnvio . " ";
$result = $base_datos->sql_query($query);
$row = $base_datos->sql_fetch_assoc($result);
$org = $row["organizacionId"];
$sect = $row["sectorId"];
$area = $row["areaId"];
$usuarioNombre = $row["nombres"];
$mailenvio = $row["usuarioEmail"];

//}

$real_message = "capsula_id=" . $capsula_id . "&version=" . $capsulaVersion . "&usuarioid=" . $usuarioIdEnvio . "&envioid=" . $envioId;


$edata2 = enc_dec($real_message);
$link = $url . "hash=" . urlencode($edata2);



$queryInsert = "EXEC envCrearEnvio " . $envioId . "," . $capsula_id . "," . $capsulaVersion . "," . $usuarioIdEnvio . ",'" . $link . "'," . $org . "," . $sect . "," . $area . "," . $grupoId . ",'" . $nusuario . "','" . $fechacierre . "'," . $cliente_id . ", 0, 0";
$resultInsert = $base_datos->sql_query($queryInsert);

//echo $queryInsert;

$queryCuestionario = "EXEC envCargaTablaCuestionario " . $envioId . "," . $capsula_id . "," . $capsulaVersion . "," . $usuarioIdEnvio . ",'" . $nusuario . "'";
$resultInsert = $base_datos->sql_query($queryCuestionario);

//echo $queryCuestionario;



/** Obtenemos el logo de la organización por cada usuario * */
$queryContenidoLogo = "EXEC envTraeLogoUsuario " . $usuarioIdEnvio . " ";
$contenidoLogo = $base_datos->sql_query($queryContenidoLogo);
$contenidoLogoData = $base_datos->sql_fetch_assoc($contenidoLogo);

$organizacionLogo = htmlentities($contenidoLogoData['organizacionLogo']) ? htmlentities($contenidoLogoData['organizacionLogo']) : '';
$organizacionNombre = htmlentities($contenidoLogoData['organizacionNombre']) ? htmlentities($contenidoLogoData['organizacionNombre']) : '';

$queryPersonalizacion = "   Select top 1 CASE WHEN c.capsulaNumero is NULL or LTRIM(RTRIM(c.capsulaNumero)) = '' THEN c.capsulaNombre ELSE LTRIM(RTRIM(c.capsulaNumero)) + '.- ' + c.capsulaNombre END as capsulaNombre, t.temaImagen, pm.subject, pm.encabezado, pm.footer
                            From Capsulas c (nolock)
                            inner join Temas t (nolock) on c.temaId = t.temaId
                            left join PersonalizacionesMails pm (nolock) on c.temaId = pm.temaId
                            Where   c.capsulaId = " . $capsula_id . "
                            and     c.capsulaVersion = " . $capsulaVersion . "";

$personalizacion = $base_datos->sql_query($queryPersonalizacion);
$personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

$temaImagen = htmlentities($personalizacionData['temaImagen']);             //  27-11-2012      obtenemos la imagen del tema para el mail        
$capsulaNombre = $personalizacionData['capsulaNombre'];   
$encabezado = htmlentities($personalizacionData['encabezado']);
$footer = htmlentities($personalizacionData['footer']);
$subject = $personalizacionData['subject'];

if(trim($subject) != ""){                
    $subject = $capsulaNombre . " - " . $subject;
}
else{
    $subject = $capsulaNombre;
}


$mensajeCorreo = "";


$mensajeCorreo=$mensajeCorreo."<table border='0' style='border:1px solid #4B6C9F;width:600px;' CELLSPACING='0' CELLSPADDING='0'><tr>";
//$mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
//$mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'> </span></td>";

if(trim($temaImagen) == ""){        
    $mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
    $mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'>" . htmlentities($capsulaNombre) . "</span></td>";
}
else{
    $mensajeCorreo=$mensajeCorreo."<td style='background-color:#FFFFFF; border-right: 1px solid #4B6C9F; color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
    $mensajeCorreo=$mensajeCorreo."<img src='..". $temaImagen ."' border='0' width='100%' alt='" . htmlentities($capsulaNombre) . "' title='" . htmlentities($capsulaNombre) . "'></td>";
} 


$mensajeCorreo=$mensajeCorreo."<td style='border-bottom:1px solid #4B6C9F;width:150px;height:75px;' align='center'><img src='". $organizacionLogo ."' border='0' alt='".mb_convert_encoding($organizacionNombre, "ISO-8859-1", "UTF-8") . "' title='".mb_convert_encoding($organizacionNombre, "ISO-8859-1", "UTF-8") . "' width='75%' height='75%'> </td></tr>";
$mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
$mensajeCorreo=$mensajeCorreo."<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
$mensajeCorreo=$mensajeCorreo."Estimado(a):<br><b>".mb_convert_encoding($usuarioNombre, "ISO-8859-1", "UTF-8")."</b><br><br>";
$mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8"))."<br><br>";
$mensajeCorreo=$mensajeCorreo."Para acceder a la c&aacute;psula haga click <a href='".$link."'>Aqui</a>";
$mensajeCorreo=$mensajeCorreo."<br><br>";
$mensajeCorreo=$mensajeCorreo.stripslashes(mb_convert_encoding($footer, "ISO-8859-1", "UTF-8"));
$mensajeCorreo=$mensajeCorreo."</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";


$correoDeNombre = $organizacionNombre;

$resultadoEnvio = enviarCorreo($correoDe, $correoDeNombre, $mailenvio, $subject, mb_convert_encoding($mensajeCorreo, "ISO-8859-1", "UTF-8"));
//echo $resultadoEnvio;

$estadoEnvio = "";

if ($resultadoEnvio == "") {
    $estadoEnvio = 1;
} else {
    $estadoEnvio = 2;
}

    
$stmt = mssql_init("envActualizaEstado");

mssql_bind($stmt, '@envioId', $envioId, SQLINT4);
mssql_bind($stmt, '@capsulaId', $capsula_id, SQLINT4);
mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
mssql_bind($stmt, '@usuarioId', $usuarioIdEnvio, SQLINT4);
mssql_bind($stmt, '@estado', $estadoEnvio, SQLINT2);
mssql_bind($stmt, '@mensajeEnvio', $resultadoEnvio, SQLTEXT);

$base_datos->sql_ejecutar_sp($stmt);



?>