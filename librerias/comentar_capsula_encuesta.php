<?PHP 
session_start();
include ("../librerias/conexion.php");

$envioId		  = $_POST['envioId'] 	       ? $_POST['envioId']         : 0;
$capsulaId		  = $_POST['capsulaId'] 	   ? $_POST['capsulaId']       : 0;
$capsulaVersion   = $_POST['capsulaVersion']   ? $_POST['capsulaVersion']  : 0;
$usuarioId        = $_POST['usuarioId']   	   ? $_POST['usuarioId']       : 0;
$comentario       = $_POST['comentario']       ? $_POST['comentario']: '';


	if(($_POST['capsulaId'] != "")&&($_POST['capsulaVersion'] != "")){
	
	$query 	= "EXEC capGuardaComentarioEncuesta ".$envioId.",".$capsulaId.",".$capsulaVersion.",".$usuarioId.",'".$comentario."' ";
				
				$result = $base_datos->sql_query($query);
				echo $query;
			
	}
?>