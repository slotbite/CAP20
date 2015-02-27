<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId']:0;
$version   = $_POST['version'] ? $_POST['version']:0;



	if(($cliente_id!= 0)){
		$_SESSION['capsulaId2'] =$capsulaId;
		$_SESSION['capsulaVersion2']=$version ;
	}
?>


