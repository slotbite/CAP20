<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$temaId    = $_POST['temaId'] ? $_POST['temaId']:0;
$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId']:0;
$version   = $_POST['version'] ? $_POST['version']:0;
$estado    = $_POST['estado'] ? $_POST['estado']:0;


	if(($cliente_id!= 0)){
	
	$query 	= "EXEC capCambiaEstadoCapsulas ".$temaId.",".$capsulaId.",".$version.",".$cliente_id.",".$estado."";
			
				$result = $base_datos->sql_query($query);
				
				
	}
?>


