<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$subject		    = $_POST['subject'] 	      ? $_POST['subject']           : '';
$encabezado         = $_POST['encabezado']        ? $_POST['encabezado']        : '';
$footer	            = $_POST['footer']            ? $_POST['footer']            : 0;
$personalizacionID  = $_POST['personalizacionID'] ? $_POST['personalizacionID'] : 0;


$subject=mb_convert_encoding($subject, "ISO-8859-1", "UTF-8");
$encabezado=mb_convert_encoding($encabezado, "ISO-8859-1", "UTF-8");
$footer=mb_convert_encoding($footer, "ISO-8859-1", "UTF-8");

	if(($_POST['subject'] != "")&&($_POST['encabezado'] != "")){
	
	$query 	= "EXEC capActualizaPersonalizacion ".$personalizacionID.",'".$subject."','".$encabezado."','".$footer."','".$nusuario."' ";
			
				
				$result = $base_datos->sql_query($query);
			
	}
?>