<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;
$contenido_id	  	  = $_POST['contenido_id'] 	? $_POST['contenido_id'] 	         		 : 0;
$orden				  = $_POST['orden'] 	    ? $_POST['orden'] 	         		         : 0;

	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion'] != 0)){
	
	$query 	= "EXEC capOrdenaContenido ".$capsulaId.",".$capsulaVersion.",".$contenido_id.",".$orden." ";
			
				
				$result = $base_datos->sql_query($query);
								
	}
?>


