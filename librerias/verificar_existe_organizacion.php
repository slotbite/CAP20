<?PHP 
session_start();
include ("../librerias/conexion.php");

$nombre_org = $_POST['organizacionNombre'] 	? $_POST['organizacionNombre']: '';
$cliente_id	 = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;


	if(($nombre_org != "")&&($cliente_id != 0)){
	$query 	= "EXEC capVerificaOrganizacion '".$nombre_org."',".$cliente_id." ";
	// echo $query;
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$organizacionId = $row["organizacionId"] ? $row["organizacionId"] : 0;
				
				echo $organizacionId;
	}else{
		echo "0";
	}
?>