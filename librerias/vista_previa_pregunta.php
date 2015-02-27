<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	         : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	     : 0;
$preguntaId			  = $_POST['pregunta_id'] 	? $_POST['pregunta_id'] 	             : 0;


	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion']!= 0)){
	
	$query 	= "EXEC capVistaPregunta ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",".$preguntaId;
			
				//echo $query;
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				
				
				
				

?>
<div>
				<? echo stripslashes($row['preguntaTexto']);?>
</div>
<br/>
<div>
<?

	$query2 	= "EXEC capVistaPreguntaRespuestas ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",".$preguntaId;
			
				//echo $query2;
				$result2 = $base_datos->sql_query($query2);
				//$row2	= $base_datos->sql_fetch_assoc($result2);
				while ($row2 = $base_datos->sql_fetch_assoc($result2)) {
	
?>	
				<input type='radio' name='Respuesta<? echo $row2['preguntaId'];?>' DISABLED /><? ECHO htmlentities($row2["respuestaTexto"])?><br/>
 <?
				}
	}
?>
</div>


