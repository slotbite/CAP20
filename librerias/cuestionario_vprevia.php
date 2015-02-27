<? 
$queryCu 	= "EXEC capListarPreguntasCapsula ".$capsulaId.",".$capsulaVersion.",".$cliente_id."";
			//echo $queryCu;
			$resultCu = $base_datos->sql_query($queryCu);
			$i=0;	
			while ($rowCu = $base_datos->sql_fetch_assoc($resultCu)) {
			$i=$i+1;
			$preguntaId=$rowCu['preguntaId'];
?>
<br/>
<br/>
<br/>
<div class="pregunta">
<b>Pregunta <? echo $i;?>:</b> <? echo stripslashes(mb_convert_encoding($rowCu['preguntaTexto'], "UTF-8","ISO-8859-1"));?>
</div>


<div class="alternativas">
	<?
	$queryPr 	= "EXEC capVistaPreguntaRespuestas ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",".$preguntaId;
				//echo $queryPr;
				$resultPr = $base_datos->sql_query($queryPr);
				while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
				
				$rTexto=$rowPr['respuestaTexto'];
				$rId=trim($rowPr['respuestaId']);
				
	?>
		
			<input type='radio' name='Respuesta<? echo $rowCu['preguntaId'];?>' DISABLED ><? echo stripslashes(htmlentities($rowPr['respuestaTexto']));?></br>
		
	<?}?>

	<input type='button' id='Validar<?echo $rowCu['preguntaId'];?>' value='Validar' style='width:150px;text-align:center;' disabled="true"/>
</div>

		<div class="positivo" style="display:block;" id="Positivo<?echo $rowCu['preguntaId'];?>">
			<? echo mb_convert_encoding($rowCu['mensajePositivo'], "UTF-8","ISO-8859-1");?>
		</div>

		<div class="negativo" style="display:block;" id="Negativo<?echo $rowCu['preguntaId'];?>">
			<? echo mb_convert_encoding($rowCu['mensajeNegativo'], "UTF-8","ISO-8859-1");?>
		</div>
	
		
<?
}
?>
