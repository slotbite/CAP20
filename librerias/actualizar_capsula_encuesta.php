<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		= $_POST['capsulaId'] 	     ? $_POST['capsulaId']        : 0;
$capsulaVersion = $_POST['capsulaVersion']   ? $_POST['capsulaVersion']   : 0;
$comentario	    = $_POST['tieneComentarios'] ? $_POST['tieneComentarios'] : 0;
$texto          = $_POST['Mensaje'] 	     ? $_POST['Mensaje'] 	      :'';

	$texto=str_replace('||','&',$texto);
	$texto=str_replace('--','+',$texto);
	
	$texto=mb_convert_encoding($texto, "ISO-8859-1", "UTF-8");
	
	if(($_POST['capsulaId'] != "")&&($_POST['capsulaVersion'] != "")){
	
	$query 	= "EXEC capActualizaCapEncuesta ".$capsulaId.",".$capsulaVersion.",".$comentario.",'".$texto."' ";
			
				echo $query;
				$result = $base_datos->sql_query($query);
			
	}
?>