<? 
$queryCu 	= "EXEC capListarPreguntasCapsula ".$capsulaid.",".$version.",".$clienteId." ";
			$resultCu = $base_datos->sql_query($queryCu);
			$i=0;	
			while ($rowCu = $base_datos->sql_fetch_assoc($resultCu)) {
			$i=$i+1;
				$mensaje="Muchas gracias por responder esta encuesta";	
?>
<br/>
<br/>
<br/>
<DIV ALIGN="CENTER">
<div id="Pregunta_<? echo $rowCu['preguntaId'];?>" class="Capsula_<? echo $capsulaid;?>">
<input type='hidden' class='PreguntaId' value='<? echo $rowCu['preguntaId'];?>'>
<div class="pregunta">
<b>Pregunta <? echo $i;?>:</b><br><? echo stripslashes(mb_convert_encoding($rowCu['preguntaTexto'], "UTF-8","ISO-8859-1"));?>
</div>
	<div class="alternativas">
		<?
		$queryPr 	= "EXEC capListarRespuestasPregunta ".$capsulaid.",".$version.",".$clienteId.",".$rowCu['preguntaId'].",".$usuario."";
					//echo $queryPr;
					$resultPr = $base_datos->sql_query($queryPr);
					while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
					$rCorrecta=strtolower($rowPr['respuestaCorrecta']);
					$rUsuario=trim(strtolower($rowPr['respuestaUsuario']));
					$rTexto=trim($rowPr['respuestaTexto']);
					$rId=trim($rowPr['respuestaId']);
					$escorrecta=0;
		?>
			<?if($rUsuario==''){?>
				<input type='radio' name='Respuesta<? echo $rowCu['preguntaId'];?>' class='Respuesta__<? echo $i;?>' value='<? echo $rId; ?>'><? echo htmlentities($rTexto);?></br>
			<?}else{?>
				<input type='radio' name='Respuesta<? echo $rowCu['preguntaId'];?>'  DISABLED  <?if(strtolower($rTexto)==strtolower($rUsuario)){ echo 'checked'; }?> ><? echo htmlentities($rowPr['respuestaTexto']);?></br>
			<?}?>
		<?}?>
	</div>
</div>
<?
}
?>
<?if($comentario==1){

	$queryCom 	= "EXEC capTraeComentarioUsuario ".$envio.",".$capsulaid.",".$version.",".$usuario."";
	//echo $queryCom;
	$resultCom = $base_datos->sql_query($queryCom);
	$rowCom	= $base_datos->sql_fetch_assoc($resultCom);
	$comentario=$rowCom['ComentarioUsuario'] ? $rowCom['ComentarioUsuario']:'';


?>
<br/><br/>
<h3>Por favor indique sus comentarios:</h3>
<textarea name="Comentario" id="Comentario" rows="4" cols="40" <?if($rUsuario!=''){ echo 'DISABLED';} ?> style="border:solid 1px #135293;" onkeyup="isAlfaNum(this);"><? echo $comentario;?></textarea>
<?}?>
<br/>
<br/>
<hr>
<input type='button' id='Validar' value='Enviar' style='width:150px;text-align:center;' <? if(trim($rUsuario)!=''){ echo 'disabled=disabled';}?> onclick="ValidarE('Capsula_<? echo $capsulaid;?>')"/>
</div>
<div class="positivo" style="display:none;" id="Positivo<?echo $rowCu['preguntaId'];?>">
			<? echo stripslashes($mensaje);?>
</div>
</DIV>

