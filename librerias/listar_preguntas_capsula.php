<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;



	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion']!= 0)){
	
	$query 	= "EXEC capListarPreguntas ".$capsulaId.",".$capsulaVersion." ";
			
				
				$result = $base_datos->sql_query($query);
			
				while ($row = $base_datos->sql_fetch_assoc($result)) {
				
					$lista= "<li onclick='FuncionesLista(this);'><input type='hidden' id='preguntaId' value='".trim($row['preguntaId'])."'/><input type='checkbox'/>";
					$lista.='<img src="../images/help.png" border="0" /><span style="font-family: sans-serif;font-size:8pt;">';
					$lista.=stripslashes(htmlentities(trim($row['preguntaTexto'])))."</span>&nbsp;&nbsp;<a href='#' onclick='EditarPregunta(".trim($row['preguntaId']).")' style='font-size:9px;'>Editar</a></li>";
					echo $lista;
				}
	}
?>


