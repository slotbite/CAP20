<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
$pregunta_id	  	  = $_POST['preguntaId'] 		? $_POST['preguntaId'] 	             : 0;
$tipo				  = $_POST['tipo'] 				? $_POST['tipo'] 	             	 : 0;
//echo $pregunta_id;

	if($pregunta_id!= 0){
	
	$query 	= "EXEC capTraerRespuestasPregunta ".$pregunta_id." ";

	      $result = $base_datos->sql_query($query);
		  //$row	= $base_datos->sql_fetch_assoc($result);
?>
		<table id="listaAlternativas" border="0" class="tabla" width="485px">
		<tbody>
		<tr>
			<th style="width:60px"></th>
			<th>Alternativa</th>
			<? if($tipo==1){?>
			<th style="width:75px">Correcta</th>
			<?}?>
		</tr>
<?		  
		while ($row = $base_datos->sql_fetch_assoc($result)) {  
		  $respuestaTexto = $row["respuestaTexto"] ? $row["respuestaTexto"] : '';
		  $respuestaCorrecta = $row["respuestaCorrecta"] ? $row["respuestaCorrecta"] : '';
		  
		  $respuestaTexto=htmlentities($respuestaTexto);
?>
		<tr>
			<td><a href='#' id='seleccionado1' onclick='QuitarSeleccionadas(this)' style='width:60px;font-size:10px;'>Quitar</a></td>
			<td><input type='text' style='width:345px;'  onkeyup='isAlfaNum(this);' value='<? echo $respuestaTexto; ?>'/></td>
			
			<? if($tipo==1){?>
			<td><span style='width:75px;text-align:center;'>
			
			<input type='checkbox' onclick='SeleccionarCorrecta(this)' <? if($respuestaCorrecta=='SI'){ echo " checked='true' "; }?> <? if($respuestaCorrecta!='SI'){ echo " disabled='true' "; } ?> />
			
			</span></td>
			<?}?>
		</tr>
<?
		}
?>
	</tbody>
   </table>

<?		
}?>

