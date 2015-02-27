<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$preguntaId     = $_POST['preguntaId']      ? $_POST['preguntaId']:0;     
$capsulaId      = $_POST['capsulaId'] 	    ? $_POST['capsulaId']: 0;
$capsulaVersion = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion']: 0;
$Texto  		= $_POST['Texto'] 			? $_POST['Texto']		  : '';
$resp_correcta= $_POST['resp_correcta'] ? $_POST['resp_correcta']: '';
$orden=$_POST['orden'] ? $_POST['orden']: 0;
$Texto=mb_convert_encoding($Texto, "ISO-8859-1", "UTF-8");  	
//$Texto=str_replace("'","",$Texto);

	if(($_POST["capsulaId"] != 0)&&($_POST["capsulaVersion"] != 0)){
	
	$query 	= "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."',$orden";
				echo $query;
				$result = $base_datos->sql_query($query);
				
				
	}
?>