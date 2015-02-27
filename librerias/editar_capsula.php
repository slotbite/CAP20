<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';


$cliente_id		= $_POST['cliente_id'] 		? $_POST['cliente_id'] 	  : '';
$nombre_capsula = $_POST['nombre_capsula'] 	? $_POST['nombre_capsula']: '';
$descripcion    = $_POST['descripcion'] 	? $_POST['descripcion']   : '';
$capsula_id		= $_POST['capsula_id'] 		? $_POST['capsula_id']   : '';
$capsulaVersion = $_POST['capsula_ver'] 	? $_POST['capsula_ver']   : '';

$administrador_id=$_SESSION['administradorId'];

$nombre_capsula=mb_convert_encoding($nombre_capsula, "ISO-8859-1", "UTF-8");
$descripcion=mb_convert_encoding($descripcion, "ISO-8859-1", "UTF-8");



	if(($_POST["nombre_capsula"] != "")&&($_POST["cliente_id"] != "")){
	$query 	= "EXEC capEditarCapsula ".$cliente_id.",".$capsula_id.",".$capsulaVersion.",'".$nombre_capsula."','".$descripcion."','".$administrador_id."' ";
			
				
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);

				
				if($errorIns ==0){
					echo $id;
				}
	}
?>


