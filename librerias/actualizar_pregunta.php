<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$preguntaId      = $_POST['preguntaId'] 	    ? $_POST['preguntaId']: 0;
$preguntaTexto  = $_POST['preguntaTexto'] 	? $_POST['preguntaTexto']: '';
$mensajePositivo= $_POST['mensajePositivo'] ? $_POST['mensajePositivo']: '';
$mensajeNegativo= $_POST['mensajeNegativo'] ? $_POST['mensajeNegativo']: '';


	
	if(($_POST["preguntaId"] != 0)){
	$preguntaTexto=str_replace('||','&',$preguntaTexto);
	$preguntaTexto=str_replace('--','+',$preguntaTexto);
	$mensajePositivo=str_replace('||','&',$mensajePositivo);
	$mensajePositivo=str_replace('--','+',$mensajePositivo);
	$mensajeNegativo=str_replace('||','&',$mensajeNegativo);
	$mensajeNegativo=str_replace('--','+',$mensajeNegativo);
	
	$preguntaTexto=mb_convert_encoding($preguntaTexto, "ISO-8859-1", "UTF-8");
	$mensajePositivo=mb_convert_encoding($mensajePositivo, "ISO-8859-1", "UTF-8");
	$mensajeNegativo=mb_convert_encoding($mensajeNegativo, "ISO-8859-1", "UTF-8");
	
	$query 	= "EXEC capActualizarPregunta ".$preguntaId.",'".$preguntaTexto."','".$mensajePositivo."','".$mensajeNegativo."','".$nusuario."' ";
				echo $query;
				$result = $base_datos->sql_query($query);
								
	}
?>


