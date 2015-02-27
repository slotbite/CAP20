<? 
$queryCu 	= "EXEC capListarPreguntasCapsula ".$capsulaId.",".$capsulaVersion.",".$cliente_id."";
			//echo $queryCu;
			$resultCu = $base_datos->sql_query($queryCu);
			$i=0;	
			while ($rowCu = $base_datos->sql_fetch_assoc($resultCu)) {
			$i=$i+1;
			$preguntaId=$rowCu['preguntaId'];
			$mensaje="Muchas gracias por responder esta encuesta";			
?>


<br/>
<br/>
<br/>

<div id="Pregunta_<? echo $rowCu['preguntaId'];?>" class="Capsula_<? echo $capsulaid;?>">
<input type='hidden' class='PreguntaId' value='<? echo $rowCu['preguntaId'];?>'>
<div class="pregunta">
<b>Pregunta <? echo $i;?>:</b> <? echo stripslashes(mb_convert_encoding($rowCu['preguntaTexto'], "UTF-8","ISO-8859-1"));?>
</div>
	<div class="alternativas">
		<?
		$queryPr 	= "EXEC capVistaPreguntaRespuestas ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",".$preguntaId;
					$resultPr = $base_datos->sql_query($queryPr);
					while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
					$rTexto=trim($rowPr['respuestaTexto']);
					$rId=trim($rowPr['respuestaId']);
		?>
				<input type='radio' name='Respuesta<? echo $rowCu['preguntaId'];?>'  DISABLED ><? echo htmlentities($rowPr['respuestaTexto']);?></br>
			<?}?>
	
	</div>
</div>
<?

}
?>
<?if($comentario==1){



?>
<br/><br/>
<h3>Por favor indique sus comentarios:</h3>
<textarea name="Comentario" id="Comentario" rows="4" cols="40" disabled style="border:solid 1px #135293;" "></textarea>
<?}?>
<br/>
<br/>
<hr>
<input type='button' id='Validar' value='Enviar' style='width:150px;text-align:center;' disabled />
</div>
<br/>
<br/>
<br/>
<div class="positivo" style="display:block;" id="Positivo<?echo $rowCu['preguntaId'];?>">
			<? echo "Muchas Gracias por responder esta encuesta";?>
</div>
