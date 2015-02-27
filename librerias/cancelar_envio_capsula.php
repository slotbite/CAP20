<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;


	
	
				 unset ($_SESSION['capsulaId2']);
                 unset ($_SESSION['capsulaVersion2']);

?>