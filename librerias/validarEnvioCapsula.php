<?PHP 
session_start();

include ("../librerias/conexion.php");

$capsulaId = $_POST['capsulaId'] 	? $_POST['capsulaId']: 0;
$temaId		= $_POST['temaId'] 		? $_POST['temaId'] 		: 0;
$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;



	if(($_POST["capsulaId"] != 0)&&($_POST["temaId"] != 0)){
	$query 	= "EXEC envVerificarCapsulaTema ".$capsulaId.",".$temaId.",".$cliente_id." ";
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$version = $row["capsulaVersion"] ? $row["capsulaVersion"] : 0;
				if($version!=0){
					$_SESSION['capsulaId2']=$capsulaId ;
					$_SESSION['capsulaVersion2']=$version;
					echo "1";
				}else{
					echo "0";
				}
				
	}
?>