<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';


$cliente_id		= $_POST['cliente_id'] 		? $_POST['cliente_id'] 	  : '';
$tema_id        = $_POST['tema_id'] 		? $_POST['tema_id'] 	  : '';
$nombre_capsula = $_POST['nombre_capsula'] 	? $_POST['nombre_capsula']: '';
$descripcion    = $_POST['descripcion'] 	? $_POST['descripcion']   : '';
$tipo           = $_POST['tipo'] 		    ? $_POST['tipo'] 		  : '';

$administrador_id=$_SESSION['administradorId'];



$nombre_capsula=mb_convert_encoding($nombre_capsula, "ISO-8859-1", "UTF-8");
$descripcion=mb_convert_encoding($descripcion, "ISO-8859-1", "UTF-8");



	if(($_POST["nombre_capsula"] != "")&&($_POST["cliente_id"] != "")){
	$query 	= "EXEC capInsertarCapsula ".$cliente_id.",".$tema_id.",'".$nombre_capsula."','".$descripcion."',".$tipo.",'".$administrador_id."' ";
			
				
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				
				$errorIns = $row["errorIns"] ? $row["errorIns"] : 0;
				$id = $row["capsulaId"] ? $row["capsulaId"] : 0;
				
				if($errorIns ==0){
					echo $id;
					$_SESSION['capsulaId2']=$id;
					$_SESSION['capsulaVersion2']=1;
				}
				// }else{
					// echo "<span style='color:red'><b>ERROR: Ya existe una C\u00e1psula con ese nombre</b></span>";
				// }
	}
?>


