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
width:600px;

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
<body>

<form method="post" id="FormTema" action="crear_tema.php">
<br/>
<table style="width:550px;" border="0" cellspacing="0">
	<tr>
	<td  class="tabcontainer">
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
						<table id="listaAlternativas1" border="0" class="texto">
							<tbody>
							<tr>
								<th style="width:80px"></th>
								<th>Alternativa</th>
							</tr>
							</tbody>
						</table>
							<div style="width:516px;height:85px;overflow:hidden;overflow-y:auto;">
								<table id="listaAlternativas" border="0" class="texto">
									<tbody>
									
									</tbody>
								</table>
							</div>
					</div>
					</td>
				</tr>
				<tr>
					<td>
					<br/>
					<input type="button"  value="Agregar" style="width:100px;" onclick="AgregarAlternativa()"/>
					</td>
				</tr>
				</table>
		</div>
	</td>
</tr>
<tr>
<td >
<div id="mensaje" style="font: 9px 'Lucida Sans Unicode', 'Lucida Sans', 'Lucida Grande', verdana, arial, helvetica;height:20px;"></div>
	<input type="button"  value="Agregar Pregunta" onclick="ValidarPregunta();"/>
	<input type="button" value="Cancelar" onclick="parent.RecargarLista();parent.searchBox.close()"/>
</td>
</tr>
</table>	
</form>

</body>
</html>


<script type="text/javascript" type="text/javascript">
	var myMooEditableInstance;
	var cantidadAlternativas;
	var AlternativasGuardadas;
	window.addEvent('domready', function(){
	
	 var textarea1=$('TextoPregunta')
	 myMooEditableInstance = new MooEditable(textarea1,{actions:'bold italic underline | insertunorderedlist insertorderedlist indent outdent |'
														,extraCSS:'body{font-family: sans-serif;font-size:9pt;}'
	 });
	
		
    });
	
	function AgregarAlternativa(){
		var tabla=$('listaAlternativas');
		var largo=tabla.rows.length;
			var x=tabla.insertRow(largo);
			var y=x.insertCell(0);
			y.innerHTML="<a href='#' id='seleccionado"+largo+"' onclick='QuitarSeleccionadas(this)'>Quitar</a>";
			
			var y1=x.insertCell(1);
			y1.innerHTML="<input type='text' style='width:500px;' onkeyup='isAlfaNum(this);'/>";
						
	}
	
	function QuitarSeleccionadas(objeto){
		if(confirm('Desea eliminar la alternativa Seleccionada?')==true){
			var td = objeto.parentNode;
			var tr = td.parentNode;
			var table = tr.parentNode;
			table.removeChild(tr);
		}
	}
	

function ValidarPregunta(){
	$('mensaje').set('html', "");
	var filasvacias=0;
	var marcadas=0;
	var textoPregunta=$('TextoPregunta').retrieve('MooEditable').getContent(); 
				
	var tabla=$('listaAlternativas');
	var largo=tabla.rows.length;	
	if(largo==0){
		$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar al menos dos Alternativas de Respuesta</b></span>");
	}else{
		for (i=0;i<largo;i++){
			var texto=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
						
			if(texto==''){
				filasvacias=filasvacias+1;
			}
			
		}
		if(filasvacias>0){
			$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar el texto de todas las Alternativas de Respuesta</b></span>");
		}else{
			
				
				if(textoPregunta==''){
					$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar el Texto de la Pregunta</b></span>");
				}else{
					textoPregunta=(textoPregunta.replace(/&/g,"||"));
					textoPregunta=(textoPregunta.replace(/\u002b/g,'--'));
					GuardarPregunta(textoPregunta,'','');
					parent.RecargarLista();
					<? unset($_SESSION['idPregunta']); ?>
					parent.searchBox.close();
					parent.RecargarLista();
				}
			
		}
	}
}

function GuardarPregunta(preguntaTexto,mensajePositivo,mensajeNegativo){
	var capsulaId=parent.document.getElementById('capsula').value;
	var capsulaVersion=parent.document.getElementById('version').value;

	var elRequest = new Request({
						url		: '../librerias/insertar_pregunta.php', 
						method  :'post',
						onSuccess: function(html) {
							$('preguntaID').value=html;
							RecorrerRespuestas();
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
	var tabla=$('listaAlternativas');
	var capsulaId=<? echo $capsula_id; ?>;
	var capsulaVersion=<? echo $capsulaVersion;?>;
	cantidadAlternativas=largo;
	var preguntaId=$('preguntaID').value;
	var largo=tabla.rows.length;	
			for (i=0;i<largo;i++){
				var texto=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
				//alert("p:"+preguntaId+":c:"+capsulaId+":v:"+capsulaVersion+":t:"+texto);
				GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,'')
			}
};

function GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta){
	var elRequest = new Request({
						url		: '../librerias/insertar_respuesta.php', 
						method  :'post',
						link    : 'chain',
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

