<?PHP 
include ("../librerias/conexion.php");

$nombre_capsula = $_POST['nombre_capsula'] 	? $_POST['nombre_capsula']: '';
$cliente_id		= $_POST['cliente_id'] 		? $_POST['cliente_id'] 		: '';
$capsula_id		= $_POST['capsula_id'] 		? $_POST['capsula_id'] 		: 0;
$nombre_capsula= mb_convert_encoding($nombre_capsula, "ISO-8859-1", "UTF-8");
	if(($_POST["nombre_capsula"] != "")&&($_POST["cliente_id"] != "")){
	$query 	= "EXEC capVerificarNombreCapsula_ed '".$nombre_capsula."',".$cliente_id.",".$capsula_id."";
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$errorIns = $row["ExisteNombre"] ? $row["ExisteNombre"] : 0;
				if($errorIns ==1){
					echo "<span style='color:red'><b>ERROR: Ya existe una C&aacutepsula con ese nombre</b></span>";
				}else{
				   //echo "ok";
				}
	}
?>