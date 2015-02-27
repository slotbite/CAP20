<?

session_start();
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
include ("../librerias/config.php");
require_once "../librerias/funciones_correo.php";

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;

$usuarioEnvio  =   $_POST['usuarioEnvio']   ? $_POST['usuarioEnvio']:'';
$mailenvio     =   $_POST['mailenvio']      ? $_POST['mailenvio']:'';
$usuarioIdEnvio=   $_POST['usuarioIdEnvio'] ? $_POST['usuarioIdEnvio']:0;
$envioId=  $_POST['envioId'] ? $_POST['envioId']:0;

 $iv="brains12";
 $real_message= "capsula_id=".$capsula_id ."&version=".$capsulaVersion."&usuarioid=".$usuarioIdEnvio."&envioid=".$envioId;

  
  // $i3 = encryptData("blowfish",
                  // "cfb",
                  // MCRYPT_RAND,
                  // "ThisIsDemo",
                  // $real_message,
                  // $iv);

// $edata2 = $i3[1];  
$edata2 = enc_dec($real_message); 

$link=$url."hash=".urlencode($edata2);

$queryP 	= "EXEC capListarPersonalizacion ".$clienteId." ";
$resultP = $base_datos->sql_query($queryP);
$rowP	= $base_datos->sql_fetch_assoc($resultP);
$personalizacionId=$rowP['personalizacionId'] ? $rowP['personalizacionId']:0;
$subject=$rowP['subject'] ? $rowP['subject']:'';
$encabezado=$rowP['encabezado'] ? $rowP['encabezado']:'';
$footerT=$rowP['footer'] ? $rowP['footer']:'';


$queryL 	= "EXEC envTraeLogoUsuario ".$usuarioIdEnvio." ";
$resultL = $base_datos->sql_query($queryL);
$rowL	= $base_datos->sql_fetch_assoc($resultL);
$logo=$rowL['organizacionLogo'] ? $rowL['organizacionLogo']:'';
$nombreOrg=$rowL['organizacionNombre'] ? $rowL['organizacionNombre']:'';

$mensajeCorreo=$mensajeCorreo."<table border='0' style='border:1px solid #5F95EF;width:500px;'><tr><td>";
$mensajeCorreo=$mensajeCorreo."<img src='".$ruta.$logo."' border='0' alt='".$nombreOrg."' title='".$nombreOrg."'><hr></td></tr>";
$mensajeCorreo=$mensajeCorreo."<tr><td>";
$mensajeCorreo=$mensajeCorreo."Estimado(a):<br>".$usuarioEnvio."<br><br>";
$mensajeCorreo=$mensajeCorreo.stripslashes($encabezado)."<br><br>";
$mensajeCorreo=$mensajeCorreo."Para acceder a la c&aacute;psula haga click <a href='".$link."'>Aqui</a>";
$mensajeCorreo=$mensajeCorreo."<br><br>";
$mensajeCorreo=$mensajeCorreo.$footerT;
$mensajeCorreo=$mensajeCorreo."</td></tr></table>";



$resultadoEnvio=enviarCorreo($correoDe,$correoDeNombre,$mailenvio,$subject,$mensajeCorreo);
echo $resultadoEnvio;

	if($resultadoEnvio==1) {
		$query1="EXEC envActualizaEstado $envioId ,$capsula_id  ,$capsulaVersion ,$usuarioIdEnvio,1 ";
		$resultb = $base_datos->sql_query($query1);
	}else{
		$query1="EXEC envActualizaEstado $envioId ,$capsula_id  ,$capsulaVersion ,$usuarioIdEnvio,2 ";	
		$resultb = $base_datos->sql_query($query1);
	}

?>