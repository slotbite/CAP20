<?php

session_start();
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
include ("../librerias/config.php");
include ("../librerias/funciones_correo.php");
require('clase/capsula.class.php');

$queryFecha = "EXEC envTraeDuracionCapsula " . $clienteId . "";
$resultF = $base_datos->sql_query($queryFecha);
$rowF = $base_datos->sql_fetch_assoc($resultF);
$dias = $rowF["plazoDias"];

$fechaCierre = date("d-m-Y");
$fechaCierre = strtotime('+' . $dias . ' day', strtotime($fechaCierre));
$fechaCierre = date("d-m-Y", $fechaCierre);


$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];

$temaId = mb_convert_encoding(trim($_POST['temaId']), "ISO-8859-1", "UTF-8");
$temaImagen = mb_convert_encoding(trim($_POST['temaImagen']), "ISO-8859-1", "UTF-8");

$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$capsulaNombre = mb_convert_encoding(trim($_POST['capsulaNombre']), "ISO-8859-1", "UTF-8");
$capsulaTipo = mb_convert_encoding(trim($_POST['capsulaTipo']), "ISO-8859-1", "UTF-8");

$usuarioId = mb_convert_encoding(trim($_POST['usuarioId']), "ISO-8859-1", "UTF-8");
$usuarioEmail = mb_convert_encoding(trim($_POST['usuarioEmail']), "ISO-8859-1", "UTF-8");
$usuarioNombre = mb_convert_encoding(trim($_POST['usuarioNombre']), "ISO-8859-1", "UTF-8");
$organizacionId = mb_convert_encoding(trim($_POST['organizacionId']), "ISO-8859-1", "UTF-8");
$sectorId = mb_convert_encoding(trim($_POST['sectorId']), "ISO-8859-1", "UTF-8");
$areaId = mb_convert_encoding(trim($_POST['areaId']), "ISO-8859-1", "UTF-8");

$caso = mb_convert_encoding(trim($_POST['caso']), "ISO-8859-1", "UTF-8");


$capsulaNombre = str_replace("\'", "'", $capsulaNombre);
$capsulaNombre = str_replace('\"', '"', $capsulaNombre);

$usuarioNombre = str_replace("\'", "'", $usuarioNombre);
$usuarioNombre = str_replace('\"', '"', $usuarioNombre);





/** Obtenemos la personalización * */
$queryPersonalizacion = "EXEC capListarPersonalizacion " . $clienteId . "," . $temaId;      // 27-11-2012 se cambia la personalizacion: de organizacion a tema

$personalizacion = $base_datos->sql_query($queryPersonalizacion);
$personalizacionData = $base_datos->sql_fetch_assoc($personalizacion);

$encabezado = htmlentities($personalizacionData['encabezado']) ? htmlentities($personalizacionData['encabezado']) : '';
$footer = htmlentities($personalizacionData['footer']) ? htmlentities($personalizacionData['footer']) : '';
$subject = $personalizacionData['subject'] ? $personalizacionData['subject'] : '';

if (trim($subject) != "") {
    $subject = $capsulaNombre . " - " . $subject;
} else {
    $subject = $capsulaNombre;
}

if ($caso != "prueba") {

    /** Guardamos en la tabla Envio * */
    $queryInsertarEnvio = "EXEC envCrearEnvio " . $envioId . "," . $capsulaId . "," . $capsulaVersion . "," . $usuarioId . ",'" . $link . "'," . $organizacionId . "," . $sectorId . "," . $areaId . ",0, 'system','" . $fechaCierre . "'," . $clienteId . ",0,0";
    $base_datos->sql_query($queryInsertarEnvio);

    /** Guardamos en la tabla Cuestionario * */
    $queryInsertarCuestionario = "EXEC envCargaTablaCuestionario " . $envioId . "," . $capsulaId . "," . $capsulaVersion . "," . $usuarioId . ",'" . $administradorId . "'";
    $base_datos->sql_query($queryInsertarCuestionario);

    $queryMaxEnvioId = "  Select ISNULL(MAX(e.envioId), 0) + 1 as 'envioId' From Envios e (nolock) Where e.clienteId = " . $clienteId;
    $envios = $base_datos->sql_query($queryMaxEnvioId);
    $envioData = mssql_fetch_array($envios);
    $envioId = htmlentities($envioData['envioId']);
    
    $pre_url = "capsula_id=" . $capsulaId . "&version=" . $capsulaVersion . "&usuarioid=" . $usuarioId . "&envioid=" . $envioId . "&tipo=" . $capsulaTipo;
    
    mssql_free_result($envios);
    
} else {
        
    // /** Guardamos en la tabla Cuestionario Prueba * */
    // $queryInsertarCuestionario = "EXEC envCargaTablaCuestionarioPrueba " . $capsulaId . "," . $capsulaVersion . ",'" . $usuarioEmail . "','" . $administradorId . "'"; 
    // echo $queryInsertarCuestionario ;
	// $resultInsert = mssql_query($queryInsertarCuestionario);
    // $rowDev = mssql_fetch_assoc($resultInsert);
    // $envioId = $rowDev["envioId"];
   // echo $envioId ; 

//FEB 2015 ACTUALIZACION FEEDBACK
$objCapsula = new Capsula();
$resultado = $objCapsula->envCargaTablaCuestionarioPrueba($capsulaId, $capsulaVersion, $usuarioEmail, $administradorId);
$envioId = $resultado["envioId"];

//echo $envioId; 
    $pre_url = "capsula_id=" . $capsulaId . "&version=" . $capsulaVersion . "&envioid=" . $envioId . "&tipo=" . $capsulaTipo;
    
}



$cod_1 = enc_dec($pre_url);

if ($caso != "prueba") {
    $link = $url . "hash=" . urlencode($cod_1);
} else {
    $link = $urlprueba . "hash=" . urlencode($cod_1);
}



if ($caso != "prueba") {

    /** Obtenemos el logo de la organización por cada usuario * */
    $queryContenidoLogo = "EXEC envTraeLogoUsuario " . $usuarioId . " ";
    $contenidoLogo = $base_datos->sql_query($queryContenidoLogo);
    $contenidoLogoData = $base_datos->sql_fetch_assoc($contenidoLogo);

    $organizacionNombre = htmlentities($contenidoLogoData['organizacionNombre']) ? htmlentities($contenidoLogoData['organizacionNombre']) : '';
    $organizacionLogo = htmlentities($contenidoLogoData['organizacionLogo']) ? htmlentities($contenidoLogoData['organizacionLogo']) : '';
    
    mssql_free_result($contenidoLogo);
    
} else {
    $organizacionNombre = "Cápsula de prueba";
    $organizacionLogo = "#";
}


$mensajeCorreo = "";

$mensajeCorreo = $mensajeCorreo . "<table border='0' style='border:1px solid #4B6C9F;width:600px;' CELLSPACING='0' CELLSPADDING='0'><tr>";
//$mensajeCorreo=$mensajeCorreo."<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
//$mensajeCorreo=$mensajeCorreo."<span style='font-weight:bolder;font-size:16px;'> </span></td>";

if (trim($temaImagen) == "") {
    $mensajeCorreo = $mensajeCorreo . "<td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
    $mensajeCorreo = $mensajeCorreo . "<span style='font-weight:bolder;font-size:16px;'>" . htmlentities($capsulaNombre) . "</span></td>";
} else {
    $mensajeCorreo = $mensajeCorreo . "<td style='background-color:#FFFFFF; border-right: 1px solid #4B6C9F; color:white;vertical-align:middle;width:450px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>";
    $mensajeCorreo = $mensajeCorreo . "<img src='.." . $temaImagen . "' border='0' width='100%' alt='" . htmlentities($capsulaNombre) . "' title='" . htmlentities($capsulaNombre) . "'></td>";
}


$mensajeCorreo = $mensajeCorreo . "<td style='border-bottom:1px solid #4B6C9F;width:150px;height:75px;' align='center'><img src='" . $organizacionLogo . "' border='0' alt='" . mb_convert_encoding($organizacionNombre, "UTF-8", "ISO-8859-1") . "' title='" . mb_convert_encoding($organizacionNombre, "UTF-8", "ISO-8859-1") . "' width='75%' height='75%'> </td></tr>";
$mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr>";
$mensajeCorreo = $mensajeCorreo . "<tr><td colspan='2' style='padding-left:30px;padding-right:30px;text-align:justify;word-wrap: break-word;'>";
$mensajeCorreo = $mensajeCorreo . "Estimado(a):<br><b>" . mb_convert_encoding($usuarioNombre, "ISO-8859-1", "UTF-8") . "</b><br><br>";
$mensajeCorreo = $mensajeCorreo . stripslashes(mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8")) . "<br><br>";
$mensajeCorreo = $mensajeCorreo . "Para acceder a la c&aacute;psula haga click <a href='" . $link . "'>Aqui</a>";
$mensajeCorreo = $mensajeCorreo . "<br><br>";
$mensajeCorreo = $mensajeCorreo . stripslashes(mb_convert_encoding($footer, "ISO-8859-1", "UTF-8"));
$mensajeCorreo = $mensajeCorreo . "</td></tr><tr><td colspan='2' style='background-color:#ffffff;'>&nbsp;</td></tr><tr><td colspan='2' style='background-color:#4B6C9F;'>&nbsp;</td></tr></table>";

//echo $mensajeCorreo;

$correoDeNombre = $organizacionNombre;  // 25-10-2012     Se reemplaza el remitente ('Administrador') por el nombre de la empresa.

$resultadoEnvio = enviarCorreo($correoDe, mb_convert_encoding($correoDeNombre, "ISO-8859-1", "UTF-8"), $usuarioEmail, $subject, mb_convert_encoding($mensajeCorreo, "ISO-8859-1", "UTF-8"));

$estadoEnvio = "";

if ($resultadoEnvio == "") {
    $estadoEnvio = 1;
} else {
    $estadoEnvio = 2;
}



if ($caso != "prueba") {
    
    $stmt = mssql_init("envActualizaEstado");

    mssql_bind($stmt, '@envioId', $envioId, SQLINT4);
    mssql_bind($stmt, '@capsulaId', $capsulaId, SQLINT4);
    mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLINT2);
    mssql_bind($stmt, '@usuarioId', $usuarioId, SQLINT4);
    mssql_bind($stmt, '@estado', $estadoEnvio, SQLINT2);
    mssql_bind($stmt, '@mensajeEnvio', $resultadoEnvio, SQLTEXT);

    $base_datos->sql_ejecutar_sp($stmt);   
}

echo $estadoEnvio;



mssql_free_result($personalizacion);

?>
