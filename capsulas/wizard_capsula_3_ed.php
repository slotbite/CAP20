<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario       = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsula_id.",".$capsulaVersion." ";
$result = $base_datos->sql_query($queryTipo);
$row	= $base_datos->sql_fetch_assoc($result);
$nombre = $row["capsulaNombre"];
$tipo = $row["capsulaTipo"];
$tema=$row["temaId"];
$plantilla->setTemplate("header_3");
//


echo $plantilla->show();
?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<script type="text/javascript" src="../scripts/mooeditable/js/MooEditable.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<link rel="stylesheet" href="../scripts/mooeditable/css/MooEditable.css" />
<div>
<?
$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{

// $plantilla->setTemplate("menu2");
// $fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							// "FECHA"	=>	"$fecha"
							// ));


// echo $plantilla->show();

$plantilla->setTemplate("wizard_capsula_3_ed");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"CLIENTE"	=>	"$Cliente_id",
							"NOMBRE"    => "$nombre",
							"CAPSULA_ID"=>"$capsula_id",
							"CAPSULA_VER"=>"$capsulaVersion",
							"TIPO"       =>"$tipo"
							));
echo $plantilla->show();

}
?>
</div>
<?
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
?>


<script type="text/javascript" type="text/javascript">
	function RecargarLista(){
		var capsulaId=$('capsula').get('value');
		var capsulaVersion=$('version').get('value');
		var elRequest = new Request({
						url		: '../librerias/listar_preguntas_capsula.php', 
						method  :'post',
						link:'chain',
						onSuccess: function(html) {
							$('listadoPreguntas').set('html',html);
							/*var example1 = $('listadoPreguntas');
							new Sortables(example1,{revert:true});*/
							recargarVistaPrevia();
						},
						//Si Falla
						onFailure: function() {
							$('listadoPreguntas').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion
									);
		
	}
	
	
function siguiente(){
	var tipocapsula=$('tipoCapsula').value;
	var elementos = $$("li");
	var cantidad=0;
		for (i=0; i<elementos.length; i++){
				cantidad=cantidad+1;
			}
		if (cantidad==0){
			$('ErrorListado').set('html', "<span style='color:red'><b>No ha agregado Preguntas a la C&aacute;psula</b></span>");
		}else{
				var capsulaId=$('capsula').get('value');
				var capsulaVersion=$('version').get('value');
				
				var elRequest1 = new Request({
						url		: '../librerias/anular_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
						
							$('IrWizardF').action='wizard_capsula_finEd.php'
							$('IrWizardF').submit();
						
						},
						//Si Falla
						onFailure: function() {
							$('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest1.send(	"temaId=" 		        + <? echo $tema; ?>  +
										"&capsulaId="			+ capsulaId +
										"&version="				+ capsulaVersion +
										"&estado="				+ 1
									);
				
				
					
		}
	
	}
	
	
function cancelar(){
		if (confirm('Perder\u00e1 todos los cambios realizados a la C\u00e1psula\nEst\u00e1 Seguro?')==true){
			CancelarCreacionCapsula();
		}
}

function FuncionesLista(elemento){
	var elementos = $$("li");
	for (i=0; i<elementos.length; i++){
		elementos[i].className='noseleccionado';
	}
	
	// if(elemento.className=='seleccionado'){
		// elemento.className='noseleccionado';
	// }else{
		// elemento.className='seleccionado';
	// }
}

function EliminaSel(){
	$('ErrorListado').set('html','');
	var elementos = $$("li");
	var cantidad=0;
	for (i=0; i<elementos.length; i++){
	   if(elementos[i].getElementsByTagName('input')[1].checked==true){
		cantidad=cantidad+1;
	   }
	}
	if(cantidad>0){
			if(confirm('Desea Eliminar la(s) pregunta(s) seleccionada(s)?')==true){
				for (i=0; i<elementos.length; i++){
				   if(elementos[i].getElementsByTagName('input')[1].checked==true){
						var valor=elementos[i].getElementsByTagName('input')[0].value;
						var capsulaId=$('capsula').value;
						var version=$('version').value;
					    EliminaPregunta(capsulaId,version,valor);
				   }
				}
				
			}
	}else{
		$('ErrorListado').set('html', "<span style='color:red'><b>No ha seleccionado ning&uacute;na pregunta</b></span>");
	}
	LimpiaForm();
}

function EliminaPregunta(capsula,version,pregunta_id){
var elRequest = new Request({
						url		: '../librerias/eliminar_pregunta.php', 
						method  :'post',
						onSuccess: function(html) {
							RecargarLista();
						},
						onFailure: function() {
							$('ErrorListado').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsula + 
									"&capsulaVersion=" 	        + version +
									"&pregunta_id=" 	        + pregunta_id 
									);
}

function SeleccionarTodo(valor){
var chequeado=valor.checked;
var elementos = $$("li");
	for (i=0; i<elementos.length; i++){
		elementos[i].getElementsByTagName('input')[1].checked=chequeado;
	}
}

	var myMooEditableInstance;
	var myMooEditableInstance2;
	var myMooEditableInstance3;
	var cantidadAlternativas;
	var AlternativasGuardadas;
	window.addEvent('domready', function(){
	RecargarLista();
	
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
	
	recargarVistaPrevia();
	
    });
	
function AgregarAlternativa(){
		tabla=$('listaAlternativas');
		largo=tabla.rows.length;
		var chequeado=0;
		for(i=1;i<largo;i++){
				var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				if(elcheckbox.checked==true){
					chequeado=1;
				}
		}

			var x=tabla.insertRow(largo);
			var y=x.insertCell(0);
			y.innerHTML="<a href='#' id='seleccionado"+largo+"' onclick='QuitarSeleccionadas(this)' style='width:60px;font-size:10px;'>Quitar</a>";
			
			var y1=x.insertCell(1);
			y1.innerHTML="<input type='text' style='width:345px;'  onkeyup='isAlfaNum(this);'/>";
			
			var y2=x.insertCell(2);
			if(chequeado==0){
				y2.innerHTML="<span style='width:75px;text-align:center;'><input type='checkbox' onclick='SeleccionarCorrecta(this)'/></span>";
			}else{
				y2.innerHTML="<span style='width:75px;text-align:center;'><input type='checkbox' onclick='SeleccionarCorrecta(this)' disabled/></span>";
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
		
				for(i=1;i<largo;i++){
						var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
						if(elcheckbox.checked==true){
							chequeado=1;
						}
				}
				
				if(chequeado==0){
					for(i=1;i<largo;i++){
							var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
							 elcheckbox.disabled=false;
					}
				}else{
							for(i=1;i<largo;i++){
							var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
								if(elcheckbox.checked==false){
									elcheckbox.disabled=true;
								}else{
								   elcheckbox.disabled=false;
								}
							}
				}
			if($('btnAgregarPregunta').disabled==true){	
				LimpiaForm();
			}
		}
	}
	
		function SeleccionarCorrecta(objeto){
		tabla=$('listaAlternativas');
		largo=tabla.rows.length;
		if(objeto.checked==true){
			for(i=1;i<largo;i++){
				var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				if(elcheckbox.checked==false){
					elcheckbox.disabled=true;
				}else{
				   elcheckbox.disabled=false;
				}
			}
		}else{
			for(i=1;i<largo;i++){
			var elcheckbox=tabla.rows[i].cells[2].getElementsByTagName('input')[0]
				elcheckbox.disabled=false;
			}
		}
		
	}
	
	
	function ValidarPregunta(){
	$('mensaje').set('html', "");
	filasvacias=0;
	marcadas=0;
	var textoPregunta=$('TextoPregunta').retrieve('MooEditable').getContent(); 
	var textoPos=$('MensajePos').retrieve('MooEditable').getContent(); 
	var textoNeg=$('MensajeNeg').retrieve('MooEditable').getContent(); 
		
	tabla=$('listaAlternativas');
	largo=tabla.rows.length;	
	if(largo==1){
		$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar al menos dos Alternativas de Respuesta</b></span>");
	}else{
		for (i=1;i<largo;i++){
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
					<? unset($_SESSION['idPregunta']); ?>
					RecargarLista();
				}
			}
		}
	}
}


function GuardarPregunta(preguntaTexto,mensajePositivo,mensajeNegativo){
	
	capsulaId=$('capsula').value;
	capsulaVersion=$('version').value;

	var elRequest = new Request({
						url		: '../librerias/insertar_pregunta.php', 
						method  :'post',
						onSuccess: function(html) {
							$('preguntaID').value=html;
							RecorrerRespuestas();
							RecargarLista();
							LimpiaForm();
						},
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
			for (i=1;i<largo;i++){
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
					
				GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta,i+1);
			}
};

function GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta,orden){
	var elRequest = new Request({
						url		: '../librerias/insertar_respuesta.php', 
						method  :'post',
						link:'chain',
						onSuccess: function(html) {
						AlternativasGuardadas=AlternativasGuardadas+1;
							if(AlternativasGuardadas==cantidadAlternativas){
								
							}
						},
						onFailure: function() {
							$('mensaje').set('html', 'Error de conexi&oacute;n');
						}
					});
	
	
	elRequest.send(	"preguntaId="				+ preguntaId+
					"&capsulaId=" 		        + capsulaId + 
					"&capsulaVersion=" 	        + capsulaVersion+
					"&Texto="					+ texto+
					"&resp_correcta="			+ resp_correcta+
                                        "&orden="+orden
					);

		
}
	
function LimpiaForm(){
	myMooEditableInstance.setContent('');
	myMooEditableInstance2.setContent('');
	myMooEditableInstance3.setContent('');

	var tabla=$('listaAlternativas');
	var largo=tabla.rows.length;
	
	for(i=largo-1;i>=1;i--){
		document.getElementById('listaAlternativas').deleteRow(i);
	}
		
	AgregarAlternativa();
	AgregarAlternativa();
	$('btnGuardarPregunta').disabled=true;
	$('btnAgregarPregunta').disabled=false;
	
}



function EditarPregunta(preguntaId){
	var elRequest = new Request({
		url		: '../librerias/traer_datos_pregunta.php', 
		method  :'post',
		link: 'chain',
		onSuccess: function(html2) {
			$('preguntaID').value=preguntaId;
			myMooEditableInstance.setContent(html2);
		},
		//Si Falla
		onFailure: function() {
			$('ErrorListado').set('html', 'Error de conexi&oacute;n');
		}
	   });
		elRequest.send(	
					"pregunta_id=" 	        + preguntaId +
					"&dato=1"
					);
					
					
		var elRequest2 = new Request({
		url		: '../librerias/traer_datos_pregunta.php', 
		method  :'post',
		link: 'chain',
		onSuccess: function(html2) {
			myMooEditableInstance2.setContent(html2);
		},
		//Si Falla
		onFailure: function() {
			$('ErrorListado').set('html', 'Error de conexi&oacute;n');
		}
	   });
		elRequest2.send(	
					"pregunta_id=" 	        + preguntaId +
					"&dato=2"
					);
		
		
		var elRequest3 = new Request({
		url		: '../librerias/traer_datos_pregunta.php', 
		method  :'post',
		link: 'chain',
		onSuccess: function(html2) {
			myMooEditableInstance3.setContent(html2);
		},
		//Si Falla
		onFailure: function() {
			$('ErrorListado').set('html', 'Error de conexi&oacute;n');
		}
	   });
		elRequest3.send(	
					"pregunta_id=" 	        + preguntaId +
					"&dato=3"
					);
	
	
	var elRequest4 = new Request({
		url		: '../librerias/traer_respuestas_pregunta.php', 
		method  :'post',
		link: 'chain',
		onSuccess: function(html2) {
			$('lista').set('html',html2);
		},
		//Si Falla
		onFailure: function() {
			$('ErrorListado').set('html', 'Error de conexi&oacute;n');
		}
	   });
		elRequest4.send(	
					"preguntaId=" 	        + preguntaId +
					"&tipo=1"
					);
	$('preguntaID').value=preguntaId;
	$('btnAgregarPregunta').disabled=true;
	$('btnGuardarPregunta').disabled=false;
}


function ValidarPreguntaEd(){
	var preguntaId=$('preguntaID').value
	$('mensaje').set('html', "");
	filasvacias=0;
	marcadas=0;
	var textoPregunta=$('TextoPregunta').retrieve('MooEditable').getContent(); 
	var textoPos=$('MensajePos').retrieve('MooEditable').getContent(); 
	var textoNeg=$('MensajeNeg').retrieve('MooEditable').getContent(); 
		
	tabla=$('listaAlternativas');
	largo=tabla.rows.length;	
	if(largo==1){
		$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar al menos dos Alternativas de Respuesta</b></span>");
	}else{
		for (i=1;i<largo;i++){
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
				
				if(textoPregunta.stripTags().length<=1||textoPos.stripTags().length<=1||textoNeg.stripTags().length<=1){
					$('mensaje').set('html', "<span style='color:red'><b>Debe Ingresar el Texto de la Pregunta y el mensaje Positivo y Negativo</b></span>");
				}else{
					textoPregunta=(textoPregunta.replace(/&/g,"||"));
					textoPregunta=(textoPregunta.replace(/\u002b/g,'--'));
					textoPos=(textoPos.replace(/&/g,"||"));
					textoPos=(textoPos.replace(/\u002b/g,'--'));
					textoNeg=(textoNeg.replace(/&/g,"||"));
					textoNeg=(textoNeg.replace(/\u002b/g,'--'));
					GuardarPreguntaEd(preguntaId,textoPregunta,textoPos,textoNeg);
					<? unset($_SESSION['idPregunta']); ?>
					RecargarLista();
				}
			}
		}
	}
}

textoPregunta.stripTags().length>=1

function GuardarPreguntaEd(preguntaId,preguntaTexto,mensajePositivo,mensajeNegativo){
	var preguntaId=$('preguntaID').value
	var elRequest = new Request({
						url		: '../librerias/actualizar_pregunta.php', 
						method  :'post',
						onSuccess: function(html) {
							RecorrerRespuestasEd();
							//RecargarLista();
							//LimpiaForm();
						},
						onFailure: function() {
							$('mensaje').set('html', 'Error de conexi&oacute;n');
						}
					});
	
	
	elRequest.send(	"preguntaId=" 		        + preguntaId + 
					"&preguntaTexto="			+ preguntaTexto+
					"&mensajePositivo="			+ mensajePositivo+
					"&mensajeNegativo="			+ mensajeNegativo
					);

}
	
function RecorrerRespuestasEd(){
	var capsulaId=<? echo $capsula_id; ?>;
	var capsulaVersion=<? echo $capsulaVersion;?>;
	var preguntaId=$('preguntaID').value;
	//request de Limpieza:
	
	var elRequest = new Request({
		url		: '../librerias/limpiar_respuestas_pregunta.php', 
		method  :'post',
		link: 'chain',
		onSuccess: function(html2) {
			
			var largo=tabla.rows.length;	
			cantidadAlternativas=largo;
			for (i=1;i<largo;i++){
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
					
				GuardarRespuestas(preguntaId,capsulaId,capsulaVersion,texto,resp_correcta,i+1);
				
			}
			RecargarLista();
			LimpiaForm();
		},
		//Si Falla
		onFailure: function() {
			$('ErrorListado').set('html', 'Error de conexi&oacute;n');
		}
	   });
		elRequest.send(	
					"preguntaId=" 	        + preguntaId 
					);
}	

function Muestra(){
var div1=$('Creacion');
var div2=$('previa');
display1=div1.getStyle('display');
	if(display1=='block'){
		div1.setStyle('display', 'none');
		div2.setStyle('display', 'block');
		//bloquea los botones para la vista previa:
		$('cancela').disabled=true;
		$('guarda').disabled=true;
		$('siguiente').disabled=true;
		$('anterior').disabled=true;
		$('Lvprevia').set('text','Volver')
	}else{
		div1.setStyle('display', 'block');
		div2.setStyle('display', 'none');
		$('cancela').disabled=false;
		$('guarda').disabled=false;
		$('siguiente').disabled=false;
		$('anterior').disabled=false;
		$('Lvprevia').set('text','Vista Previa');
	}
}

function recargarVistaPrevia(){
		capsulaId=$('capsula').get('value');
		capsulaVersion=$('version').get('value');
		var elRequest = new Request({
						url		: '../librerias/vista_previa_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							$('Vprevia').set('html',html);
						},
						//Si Falla
						onFailure: function() {
							$('Vprevia').set('html', '');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion
									);
}

function Cerrar111(){
if(confirm('Desea Cerrar el Asistente?')==true){
	CerrarWizard();
	parent.searchBoxPpal.close();
	}
}
	
function CancelarCreacionCapsula(){

/*
Pregunto si es nulo el control donde guardo el capsulaId y la versi�n,
porque en la primera pantalla del wizard aun no se han asignado esos 
valores ni creado los controles, por lo tanto no es necesario ir a la 
base de datos con el Request.
*/

	capsulaId=0;
	capsulaVersion =0;
	
	if( $('capsula_id')!=null){
	capsulaId=$('capsula_id').get('value');
	}
	
	if($('capsula_ver')!=null){
	capsulaVersion = $('capsula_ver').get('value');
	}
	
	
	if(capsulaId != 0 && capsulaVersion!= 0){
	/*
	Este Request va a la Base de Datos y elimina todos los datos ingresados de la c�psula.
	Adem�s limpia las variables de sesi�n donde se guarda el capsulaId y la versi�n
	*/
		if(capsulaVersion!=1){

		var elRequest = new Request({
						url		: '../librerias/cancelar_creacion_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							
						},
						//Si Falla
						onFailure: function() {
							//alert('error al conectarse a la base de datos');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion
									);
		}
	}
	
	//window.location='adm_capsulas.php';
	parent.searchBoxPpal.close();
	
}
function anterior(){
	window.location='wizard_capsula_2_ed.php';
}
</script>



