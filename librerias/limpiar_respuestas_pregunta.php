<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$preguntaId      = $_POST['preguntaId'] 	    ? $_POST['preguntaId']: 0;



	
	if(($_POST["preguntaId"] != 0)){

	$query 	= "EXEC capLimpiarRespuestasPregunta ".$preguntaId." ";
				echo $query;
				$result = $base_datos->sql_query($query);
								
	}
?>


