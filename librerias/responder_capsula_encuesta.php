<?PHP 
session_start();
include ("../librerias/conexion.php");

$envioId		  = $_POST['envioId'] 	       ? $_POST['envioId']         : 0;
$capsulaId		  = $_POST['capsulaId'] 	   ? $_POST['capsulaId']       : 0;
$capsulaVersion   = $_POST['capsulaVersion']   ? $_POST['capsulaVersion']  : 0;
$usuarioId        = $_POST['usuarioId']   	   ? $_POST['usuarioId']       : 0;
$preguntaId		  = $_POST['preguntaId']       ? $_POST['preguntaId']      : 0;
$respuestaUsuario = $_POST['respuestaUsuario'] ? $_POST['respuestaUsuario']: 0;


	if(($_POST['capsulaId'] != "")&&($_POST['capsulaVersion'] != "")){
	
	$query 	= "EXEC capGuardaRespuestaEncuesta ".$envioId.",".$capsulaId.",".$capsulaVersion.",".$usuarioId.",".$preguntaId.",".$respuestaUsuario." ";
			
				
				$result = $base_datos->sql_query($query);
				echo $query;
			
	}
?>