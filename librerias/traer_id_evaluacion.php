<?PHP 
session_start();
include ("../librerias/conexion.php");

$nombre_eval = $_POST['nombre_eval'] 			? $_POST['nombre_eval']: '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
	//echo "hola";

$nombre_eval=mb_convert_encoding($nombre_eval, "ISO-8859-1", "UTF-8");
	
	if(($nombre_eval != "")&&($cliente_id != 0)){
	$query 	= "EXEC capTraerEvaluacionId '".$nombre_eval."',".$cliente_id." ";
	//echo $query;
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$errorIns = $row["evaluacionId"] ? $row["evaluacionId"] : 0;
				echo $errorIns;
	}
?>