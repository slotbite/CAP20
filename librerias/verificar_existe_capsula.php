<?PHP 
include ("../librerias/conexion.php");

$nombre_capsula = $_POST['nombre_capsula'] 	? $_POST['nombre_capsula']: '';
$cliente_id		= $_POST['cliente_id'] 		? $_POST['cliente_id'] 		: '';


	if(($_POST["nombre_capsula"] != "")&&($_POST["cliente_id"] != "")){
	$query 	= "EXEC capVerificarNombreCapsula '".$nombre_capsula."',".$cliente_id." ";
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$errorIns = $row["ExisteNombre"] ? $row["ExisteNombre"] : 1;
				$CapsulaId = $row["idCapsula"] ? $row["idCapsula"] : 0;
				
				echo $CapsulaId;
	}
?>