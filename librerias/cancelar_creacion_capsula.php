<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;



	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion']!= 0)){
	
	$query 	= "EXEC capCancelarCreacionCapsula ".$capsulaId.",".$capsulaVersion." ";
			
				
				$result = $base_datos->sql_query($query);
				unset ($_SESSION['capsulaId2']);
                unset ($_SESSION['capsulaVersion2']);
	}
	unset ($_SESSION['capsulaId2']);
    unset ($_SESSION['capsulaVersion2']);
?>