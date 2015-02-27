<?
session_start();
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;
?>
<html>
<head>
<script type="text/javascript" src="../scripts/mootools.js"></script>
<script type="text/javascript" src="../scripts/mootools-1.2.5.1-more.js"></script>
<script type="text/javascript" src="../scripts/Observer.js"></script>
<script type="text/javascript" src="../scripts/Autocompleter.js"></script>
<script type="text/javascript" src="../scripts/Autocompleter.Request.js"></script>
<script type="text/javascript" src="../scripts/mooeditable/js/MooEditable.js"></script>
<script type="text/javascript" src="../scripts/Funciones.js"></script>
<link rel="stylesheet" type="text/css" href="../skins/saam/Autocompleter.css" />
<link rel="stylesheet" type="text/css" href="../skins/saam/plantillas/style.css" media="screen" />
<link rel="stylesheet" href="../scripts/mooeditable/css/MooEditable.css" />

<style>
.texto{
font: 11px "Lucida Sans Unicode", "Lucida Sans", "Lucida Grande", verdana, arial, helvetica;
}

.texto th{
background-color:#CDCDCD;
color:white;
}

.texto td{
	border-bottom:1px solid #D3D3D3;
}

</style>
</head>
<body style="font: 9px 'Lucida Sans Unicode', 'Lucida Sans', 'Lucida Grande', verdana, arial, helvetica;">

<form method="post" id="FormTema" action="crear_tema.php">
<br/>
<table style="width:550px;" border="0" cellspacing="0">
	<tr>
		<td width="100px" id="Preguntas">
		Preguntas
		</td>
		<td width="100px" id="Mensajes">
		 Mensajes
		<td width="350px">
		&nbsp;
		</td>
	</tr>
	<tr>
	<td colspan="3" class="tabcontainer">
		<div id="DivPreguntas" style="display:block;width:100%;height:300px;">
				<table border="0">
				<tr>
					<td>
					<h3>Pregunta</h3>
						<input name="preguntaID" id="preguntaID" type="hidden" />
					</td>
				</tr>
				<tr>	
					<td>
					<textarea name="TextoPregunta" id="TextoPregunta" rows="4" cols="40"></textarea>
					</td>
				</tr>
				<tr>
					<td>
					<h3>Alternativas</h3>
					</td>
				</tr>
				<tr>	
					<td>
					<div id="lista">
					<table id="listaAlternativas1" border="0" class="texto" width="496px">
							<tbody>
							<tr>
								<th style="width:60px"></th>
								<th>Alternativa</th>
								<th style="width:75px">Correcta</th>
							</tr>
							</tbody>
						</table>
						<div style="width:496px;height:85px;overflow:hidden;overflow-y:auto;">
						<table id="listaAlternativas" border="0" class="texto">
							<tbody>
							
							</tbody>
						</table>
						<div>
					</div>
					</td>
				</tr>
				<tr>
					<td>
					<br/>
					<input type="button"  value="Agregar" onclick="AgregarAlternativa()"/>
					</td>
				</tr>
				</table>
		</div>
		<div id="DivMensajes" style="display:block;width:100%;height:300px;">
			<table border="0">
			<tr>
				<td>
					<H3>Mensaje Positivo</H3>
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="MensajePos" id="MensajePos" rows="4" cols="40"></textarea>
				</td>
			</tr>
			<tr>
				<td>
				<H3>Mensaje Negativo</H3>
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="MensajeNeg" id="MensajeNeg" rows="4" cols="40"></textarea>
				</td>
			</tr>
			</table>
		</div>
</td>
</tr>
<tr>
<td colspan="3">
<div id="mensaje" style="font: 9px 'Lucida Sans Unicode', 'Lucida Sans', 'Lucida Grande', verdana, arial, helvetica;height:20px;"> </div>
	<input type="button"  value="Agregar Pregunta" onclick="ValidarPregunta();parent.RecargarLista();"/>
	<input type="button" value="Cancelar" onclick="parent.RecargarLista();parent.searchBox.close()"/>
</td>
</tr>
</table>	
</form>

</body>
</html>


<script type="text/javascript" type="text/javascript">
	var myMooEditableInstance;
	var myMooEditableInstance2;
	var myMooEditableInstance3;
	var cantidadAlternativas;
	var AlternativasGuardadas;
	window.addEvent('domready', function(){
	
	 var textarea1=$('TextoPregunta')
	 myMooEditableInstance = new MooEditable(textarea1,{actions:'bold italic underline | insertunorderedlist insertorderedlist indent outdent |'
														,extraCSS:'body{font-family: sans-serif;font-size:9pt;}'
	 });
	
	
	 var textarea2=$('MensajePos')
	 myMooEditableInstance2 = new MooEditable(textarea2,{actions:'bold italic underline | insertunorderedlist insertorderedlist indent outdent |'
														,extraCSS:'body{font-family: sans-serif;font-size:9pt;}'});
														
	 var textarea3=$('MensajeNeg')
	 myMooEditableInstance3 = new MooEditable(textarea3,{actions:'bold italic underline | insertunorderedlist insertorderedlist indent outdent |'
														,extraCSS:'body{font-family: sans-serif;font-size:9pt;}'});
	
	$('DivMensajes').setStyle('display', 'none');
	
    });
	
	function AgregarAlternativa(){
		tabla=$('listaAlternativas');
		largo=tabla.rows.length;
		var chequeado=0;
		//agregar funcion para cortar &
		for(i=0;i<largo;i++){
				var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				if(elcheckbox.checked==true){
					chequeado=1;
				}
		}

			var x=tabla.insertRow(largo);
			var y=x.insertCell(0);
			y.innerHTML="<a href='#' id='seleccionado"+largo+"' onclick='QuitarSeleccionadas(this)' style='width:60px'>Quitar</a>";
			
			var y1=x.insertCell(1);
			y1.innerHTML="<input type='text' style='width:350px;'  onkeyup='isAlfaNum(this);'/>";
			
			var y2=x.insertCell(2);
			if(chequeado==0){
				y2.innerHTML="<span style='width:75px;text-align:center;'><input type='checkbox' onclick='SeleccionarCorrecta(this)'/></span>";
			}else{
				y2.innerHTML="<span style='width:75px;text-align:center;'><input type='checkbox' onclick='SeleccionarCorrecta(this)' disabled/></span>";
			}
	}
	
	function SeleccionarCorrecta(objeto){
		tabla=$('listaAlternativas');
		largo=tabla.rows.length;
		if(objeto.checked==true){
			for(i=0;i<largo;i++){
				var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				if(elcheckbox.checked==false){
					elcheckbox.disabled=true;
				}else{
				   elcheckbox.disabled=false;
				}
			}
		}else{
			for(i=0;i<largo;i++){
			var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				elcheckbox.disabled=false;
			}
		}
		
	}
	
	function QuitarSeleccionadas(objeto){
		if(confirm('Desea eliminar la alternativa Seleccionada?')==true){
			var td = objeto.parentNode;
			var tr = td.parentNode;
			var table = tr.parentNode;
			table.removeChild(tr);
			
			var tabla=$('listaAlternativas');
			var largo=tabla.rows.length;
			var chequeado=0;
		
				for(i=0;i<largo;i++){
						var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
						if(elcheckbox.checked==true){
							chequeado=1;
						}
				}
				
				if(chequeado==0){
					for(i=0;i<largo;i++){
							var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
							 elcheckbox.disabled=false;
					}
				}else{
							for(i=0;i<largo;i++){
							var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
								if(elcheckbox.checked==false){
									elcheckbox.disabled=true;
								}else{
								   elcheckbox.disabled=false;
								}
							}
				}
				
			
		}
	}
	
window.addEvent('domready', function (){	
	$('DivPreguntas').setStyle('display', 'block');
	$('DivMensajes').setStyle('display', 'none');
	$('Preguntas').setStyle('font-size','11px');
	$('Preguntas').setStyle('background-color','#003366');
	$('Preguntas').setStyle('color','white');
	$('Preguntas').setStyle('cursor','default');
	
	$('Mensajes').setStyle('font-size','11px');
	$('Mensajes').setStyle('background-color','white');
	$('Mensajes').setStyle('color','black');
	$('Mensajes').setStyle('cursor','default');
	
	$('Preguntas').addEvent('click', function(){
		$('DivPreguntas').setStyle('display', 'block');
		$('DivMensajes').setStyle('display', 'none');
		$('Preguntas').setStyle('font-size','11px');
		$('Preguntas').setStyle('background-color','#003366');
		$('Preguntas').setStyle('color','white');
		$('Preguntas').setStyle('cursor','default');
		
		$('Mensajes').setStyle('font-size','11px');
		$('Mensajes').setStyle('background-color','white');
		$('Mensajes').setStyle('color','black');
		$('Mensajes').setStyle('cursor','default');
	});
	
   $('Mensajes').addEvent('click', function(){
		$('DivMensajes').setStyle('display', 'block');
		$('DivPreguntas').setStyle('display', 'none');
		
		$('Mensajes').setStyle('font-size','11px');
		$('Mensajes').setStyle('background-color','#003366');
		$('Mensajes').setStyle('color','white');
		$('Mensajes').setStyle('cursor','default');
		
		$('Preguntas').setStyle('font-size','11px');
		$('Preguntas').setStyle('background-color','white');
		$('Preguntas').setStyle('color','black');
		$('Preguntas').setStyle('cursor','default');
	
   });
	
	
});	

function ValidarPregunta(){
	$('mensaje').set('html', "");
	filasvacias=0;
	marcadas=0;
	var textoPregunta=$('TextoPregunta').retrieve('MooEditable').getContent(); 
	var textoPos=$('MensajePos').retrieve('MooEditable').getContent(); 
	var textoNeg=$('MensajeNeg').retrieve('MooEditable').getContent(); 
		
	tabla=$('listaAlternativas');
	largo=tabla.rows.length;	
	if(largo==0){
		$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar al menos dos Alternativas de Respuesta</b></span>");
	}else{
		for (i=0;i<largo;i++){
			texto=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
			correctas=tabla.rows[i].cells[2].getElementsByTagName('input')[0].checked;
			
			if(texto==''){
				filasvacias=filasvacias+1;
			}
			
			if(correctas==true){
				
				marcadas=marcadas+1;
			}
		}
		if(filasvacias>0){
			$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar el texto de todas las Alternativas de Respuesta</b></span>");
		}else{
			if(marcadas!=1){
				$('mensaje').set('html', "<span style='color:red'><b>Debe Seleccionar una Alternativa de Respuesta como correcta</b></span>");
			}else{
				
				if(textoPregunta==''||textoPos==''||textoNeg==''){
					$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar el Texto de la Pregunta y el mensaje Positivo y Negativo</b></span>");
				}else{
					
					
					textoPregunta=(textoPregunta.replace(/&/g,"||"));
					textoPregunta=(textoPregunta.replace(/\u002b/g,'--'));
					textoPos=(textoPos.replace(/&/g,"||"));
					textoPos=(textoPos.replace(/\u002b/g,'--'));
					textoNeg=(textoNeg.replace(/&/g,"||"));
					textoNeg=(textoNeg.replace(/\u002b/g,'--'));
					GuardarPregunta(textoPregunta,textoPos,textoNeg);
					parent.RecargarLista();
					<? unset($_SESSION['idPregunta']); ?>
					parent.searchBox.close();
					parent.RecargarLista();
					
				}
			}
		}
	}
}

function GuardarPregunta(preguntaTexto,mensajePositivo,mensajeNegativo){
	capsulaId=parent.document.getElementById('capsula').value;
	capsulaVersion=parent.document.getElementById('version').value;

	var elRequest = new Request({
						url		: '../librerias/insertar_pregunta.php', 
						method  :'post',
						onSuccess: function(html) {
							$('preguntaID').value=html;
							RecorrerRespuestas();
							//alert('idPregunta:'+html);
						},
						//Si Falla
						onFailure: function() {
							$('mensaje').set('html', 'Error de conexi&oacute;n');
						}
					});
	
	
	elRequest.send(	"capsulaId=" 		        + capsulaId + 
					"&capsulaVersion=" 	        + capsulaVersion+
					"&preguntaTexto="			+ preguntaTexto+
					"&mensajePositivo="			+ mensajePositivo+
					"&mensajeNegativo="			+ mensajeNegativo
					);
	
	
}


function RecorrerRespuestas(){
	var capsulaId=<? echo $capsula_id; ?>;
	var capsulaVersion=<? echo $capsulaVersion;?>;
	var preguntaId=$('preguntaID').value;
	var largo=tabla.rows.length;	
	cantidadAlternativas=largo;
			for (i=0;i<largo;i++){
				texto=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
				correctas=tabla.rows[i].cells[2].getElementsByTagName('input')[0].checked;
					if(correctas==true){
						resp_correcta='SI';
					}else{
						resp_correcta='NO';
					}
					if(texto=='0'){
						texto=texto+' ';
					}else{
						texto=texto+'';
					}
					
				GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta);
			}
};

function GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta){
	var elRequest = new Request({
						url		: '../librerias/insertar_respuesta.php', 
						method  :'post',
						onSuccess: function(html) {
						AlternativasGuardadas=AlternativasGuardadas+1;
							if(AlternativasGuardadas==cantidadAlternativas){
								
							}
						},
						//Si Falla
						onFailure: function() {
							$('mensaje').set('html', 'Error de conexi&oacute;n');
						}
					});
	
	
	elRequest.send(	"preguntaId="				+ preguntaId+
					"&capsulaId=" 		        + capsulaId + 
					"&capsulaVersion=" 	        + capsulaVersion+
					"&Texto="					+ texto+
					"&resp_correcta="			+ resp_correcta
					);

		
}
</script>