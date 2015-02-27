<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;
$contenido_id	  	  = $_POST['contenido_id'] 	? $_POST['contenido_id'] 	         		 : '';


	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion'] != 0)){
	
	$query 	= "EXEC capTraerContenido ".$capsulaId.",".$capsulaVersion.",".$contenido_id." ";
	      $result = $base_datos->sql_query($query);
		  $row	= $base_datos->sql_fetch_assoc($result);
		  $titulo = $row["contenidoUrl"] ? $row["contenidoUrl"] : '';
		  echo htmlentities($titulo);
								
	}
?>


