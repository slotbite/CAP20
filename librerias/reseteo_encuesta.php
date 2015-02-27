<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$envioId		 = $_POST['envioId'] 		? $_POST['envioId'] 	                 : 0;
$usuarioId       = $_POST['usuarioId'] 	? $_POST['usuarioId'] 	             : 0;



	if(($_POST['envioId'] != 0)&&($_POST['usuarioId']!= 0)){
	
	$query 	= "EXEC envResetearEncuesta ".$envioId.",".$usuarioId." ";
			
				
				$result = $base_datos->sql_query($query);
				
	}

?>