<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;
$pregunta_id	  	  = $_POST['pregunta_id'] 		? $_POST['pregunta_id'] 	             : 0;
$dato				  = $_POST['dato'] 				? $_POST['dato'] 	         		 	 : 0;

	if($_POST['pregunta_id'] != 0){
	
	$query 	= "EXEC capTraerPregunta ".$pregunta_id." ";

	      $result = $base_datos->sql_query($query);
		  $row	= $base_datos->sql_fetch_assoc($result);
		  $preguntaTexto = $row["preguntaTexto"] ? $row["preguntaTexto"] : '';
		  $mensajePositivo = $row["mensajePositivo"] ? $row["mensajePositivo"] : '';
		  $mensajeNegativo = $row["mensajeNegativo"] ? $row["mensajeNegativo"] : '';
		  
		  
		  $preguntaTexto   = stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8","ISO-8859-1"));
		  $mensajePositivo = stripslashes(mb_convert_encoding($mensajePositivo, "UTF-8","ISO-8859-1"));
		  $mensajeNegativo = stripslashes(mb_convert_encoding($mensajeNegativo, "UTF-8","ISO-8859-1"));
		 
		  
		  if($dato==1){
			echo $preguntaTexto;
		  }
		  
		  if($dato==2){
			echo $mensajePositivo;
		  }
		  
		  if($dato==3){
			echo $mensajeNegativo;
		  }
		  
	}
?>


