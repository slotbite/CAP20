<?PHP 
session_start();
include ("../librerias/conexion.php");

$nombre_grupo = $_POST['nombre_grupo'] 			? $_POST['nombre_grupo']: '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
	//echo "hola";

	
$nombre_grupo=mb_convert_encoding($nombre_grupo, "ISO-8859-1", "UTF-8");
	
	if(($nombre_grupo != "")&&($cliente_id != 0)){
	$query 	= "EXEC capTraerGrupoId '".$nombre_grupo."',".$cliente_id." ";
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$errorIns = $row["grupoID"] ? $row["grupoID"] : 0;
				echo $errorIns;
	}
?>