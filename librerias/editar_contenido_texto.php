<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;
$contenidoTitulo	  = $_POST['contenidoTitulo'] 	? $_POST['contenidoTitulo'] 	         : '';
$contenidoDescripcion = $_POST['contenidoDescripcion'] 	? $_POST['contenidoDescripcion'] 	 : '';
$contenidoId          = $_POST['contenidoId'] 	? $_POST['contenidoId'] 	 : 0;
$contenidoTitulo=mb_convert_encoding($contenidoTitulo, "ISO-8859-1", "UTF-8");
//$contenidoDescripcion=mb_convert_encoding($contenidoDescripcion, "ISO-8859-1", "UTF-8");

	if(($_POST['contenidoTitulo'] != "")&&($_POST['contenidoDescripcion'] != "")){
	
	$contenidoDescripcion=str_replace('||','&',$contenidoDescripcion);
	$contenidoDescripcion=str_replace('--','+',$contenidoDescripcion);
	$contenidoDescripcion=mb_convert_encoding($contenidoDescripcion, "ISO-8859-1", "UTF-8");
	
	$query 	= "EXEC capEditarContenidoTexto ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",'".$contenidoTitulo."','".$contenidoDescripcion."','".$nusuario."',".$contenidoId."";
			echo $query;
				
				$result = $base_datos->sql_query($query);
				//$row	= $base_datos->sql_fetch_assoc($result);
				
				
	}
?>


