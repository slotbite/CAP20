<?

session_start();
include("../default.php");
include ("../librerias/crypt.php");
$plantilla->setPath('../skins/saam/plantillas/');

ini_set('post_max_size','30M');
ini_set('upload_max_filesize','30M');

$nusuario       = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$plantilla->setTemplate("header_3");
//
echo $plantilla->show();


$queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsula_id.",".$capsulaVersion." ";
$result = $base_datos->sql_query($queryTipo);
$row	= $base_datos->sql_fetch_assoc($result);
$tipo = $row["capsulaTipo"];
$tema=$row["temaId"];

$queryPermiso 	= "EXEC capTraerPermisosCliente ".$clienteId." ";
$resultPermiso = $base_datos->sql_query($queryPermiso);
$rowPermiso	= $base_datos->sql_fetch_assoc($resultPermiso);
$permisos = $rowPermiso["PERMISO"];

$permisosNew=enc_dec($permisos);

$ret =@eval($permisosNew);
if($ret=false){

}
?>


<script type="text/javascript" type="text/javascript">
var myMooEditableInstance;
	function siguiente(){
	var tipocapsula=$('tipoCapsula').value;
	var elementos = $$("li");
	var cantidad=0;
		for (i=0; i<elementos.length; i++){
				cantidad=cantidad+1;
			}
		if (cantidad==0){
			$('ErrorListado').set('html', "<span style='color:red'><b>No ha agregado Contenido a la C&aacute;psula</b></span>");
		}else{
			ReOrdenaContenido();
			if(tipocapsula==3){
					
				var capsulaId=$('capsula').get('value');
				var capsulaVersion=$('version').get('value');
				
				var elRequest1 = new Request({
						url		: '../librerias/anular_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
						
						$('IrWizard3').action='wizard_capsula_finEd.php'
						$('IrWizard3').submit();
						
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
			
					
			}else{
				if(tipocapsula==1){
				$('IrWizard3').submit();	
				}else{
					$('IrWizard3').action='wizard_capsula_3b_ed.php'
					$('IrWizard3').submit();
				}
			}
		}
	
	}
	
	
	function cancelar(){
		if (confirm('Perder\u00e1 todos los cambios realizados a la C\u00e1psula\nEst\u00e1 Seguro?')==true){
			CancelarCreacionCapsula();
		}
	}
	

</script>
<div>
<?
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

$plantilla->setTemplate("wizard_capsula_2_ed");
$plantilla->setVars(array(	"CAPSULA_ID"		=>"$capsula_id",
							"CAPSULA_VER"       =>"$capsulaVersion",
							"TIPO"              =>"$tipo"
));

echo $plantilla->show();

}
?>
<script type="text/javascript" type="text/javascript">
window.addEvent('domready', function(){
var example1 = $('ListaContenido');
							new Sortables(example1,{revert:true,
							onComplete: function(){
								ReOrdenaContenido();
								recargarVistaPrevia();
							}
});
});

</script>
</div>
<?
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
?>
 <link rel="stylesheet" href="../scripts/mooeditable/css/MooEditable.css" />

 <script type="text/javascript" src="../scripts/mooeditable/js/MooEditable.js"></script>

<script type="text/javascript" type="text/javascript">
window.addEvent('domready', function (){
	//estilos para mis "TABS":
	$('Texto').setStyle('display', 'block');
	$('Imagen').setStyle('display', 'none');
	/*$('TipoTexto').setStyle('background-color','#003366');
	$('TipoTexto').setStyle('color','white');
	
	$('TipoImagen').setStyle('background-color','white');
	$('TipoImagen').setStyle('color','black');
	
	$('TipoVideo').setStyle('background-color','white');
	$('TipoVideo').setStyle('color','black');
	
	$('TipoAudio').setStyle('background-color','white');
	$('TipoAudio').setStyle('color','black');
	*/
	
	$('TipoTexto').setStyle('cursor','default');
	$('TipoImagen').setStyle('cursor','default');
	$('TipoVideo').setStyle('cursor','default');
	$('TipoAudio').setStyle('cursor','default');
		
	//funciones para los "TABS":
    $('TipoTexto').addEvent('click', function(){
		$('Texto').setStyle('display', 'block');
		$('Imagen').setStyle('display', 'none');
		$('Video').setStyle('display', 'none');
		$('Audio').setStyle('display', 'none');
		/*$('TipoTexto').setStyle('background-color','#003366');
		$('TipoTexto').setStyle('color','white');
		$('TipoImagen').setStyle('background-color','white');
		$('TipoImagen').setStyle('color','black');
		$('TipoVideo').setStyle('background-color','white');
		$('TipoVideo').setStyle('color','black');
		$('TipoAudio').setStyle('background-color','white');
		$('TipoAudio').setStyle('color','black');*/
		recargarListaContenido();
	});

	$('TipoImagen').addEvent('click', function(){
		$('Texto').setStyle('display', 'none');
		$('Imagen').setStyle('display', 'block');
		$('Video').setStyle('display', 'none');
		$('Audio').setStyle('display', 'none');
		/*$('TipoImagen').setStyle('background-color','#003366');
		$('TipoImagen').setStyle('color','white');
		$('TipoTexto').setStyle('background-color','white');
		$('TipoTexto').setStyle('color','black');
		$('TipoVideo').setStyle('background-color','white');
		$('TipoVideo').setStyle('color','black');
		$('TipoAudio').setStyle('background-color','white');
		$('TipoAudio').setStyle('color','black');*/
		recargarListaContenido();
	});
	
	$('TipoVideo').addEvent('click', function(){
		$('Texto').setStyle('display', 'none');
		$('Imagen').setStyle('display', 'none');
		$('Video').setStyle('display', 'block');
		$('Audio').setStyle('display', 'none');
		/*$('TipoVideo').setStyle('background-color','#003366');
		$('TipoVideo').setStyle('color','white');
		$('TipoTexto').setStyle('background-color','white');
		$('TipoTexto').setStyle('color','black');
		$('TipoImagen').setStyle('background-color','white');
		$('TipoImagen').setStyle('color','black');
		$('TipoAudio').setStyle('background-color','white');
		$('TipoAudio').setStyle('color','black');*/
		recargarListaContenido();
	});
	
	$('TipoAudio').addEvent('click', function(){
		$('Texto').setStyle('display', 'none');
		$('Imagen').setStyle('display', 'none');
		$('Video').setStyle('display', 'none');
		$('Audio').setStyle('display', 'block');
		/*$('TipoAudio').setStyle('background-color','#003366');
		$('TipoAudio').setStyle('color','white');
		$('TipoTexto').setStyle('background-color','white');
		$('TipoTexto').setStyle('color','black');
		$('TipoImagen').setStyle('background-color','white');
		$('TipoImagen').setStyle('color','black');
		$('TipoVideo').setStyle('background-color','white');
		$('TipoVideo').setStyle('color','black');*/
		recargarListaContenido();
	});
	
	<? if($activarmedios==false){?>
		$('TipoVideo').setStyle('display', 'none');
		$('TipoAudio').setStyle('display', 'none');
	<?}?>
	
	
});


    window.addEvent('domready', function(){
	recargarVistaPrevia();
	recargarListaContenido();
	 var textarea1=$('content')
	 myMooEditableInstance = new MooEditable(textarea1,{actions:'bold italic underline | insertunorderedlist insertorderedlist indent outdent |'
														,extraCSS:'body{font-family: sans-serif;font-size:9pt;}'
	 });
	 tipocapsula=$('tipoCapsula').value;
	 if(tipocapsula==3){
		$('siguiente').value='Siguiente';
		}
    })

	
function AgregaTexto(){
	var titulo=$('tituloTexto').get('value');
	var texto=$('content').retrieve('MooEditable').getContent(); 
	var capsulaId=$('capsula').get('value');
	var capsulaVersion=$('version').get('value');
	var contenidoTitulo=titulo;
	var contenidoDescripcion=texto;
	
	$('ErrorMsg').set('html','');
	if(titulo==''||texto==''){
		$('ErrorMsg').set('html', "<span style='color:red'><b>Falta ingresar el t&iacute;tulo o el Texto para agregar el contenido</b></span>");
	}else{
		//guardo en base de datos:
		contenidoDescripcion=(texto.replace(/&/g,"||"));
		contenidoDescripcion=(contenidoDescripcion.replace(/\u002b/g,'--'));
		var elRequest = new Request({
						url		: '../librerias/insertar_contenido_texto.php', 
						method  :'post',
						urlEncoded :'true',
						onSuccess: function(html) {
							//Limpiar el Div y recargar la lista!!!
							$('formTexto').reset();
							myMooEditableInstance.setContent('');
							recargarListaContenido();
							recargarVistaPrevia()
						},
						//Si Falla
						onFailure: function() {
							$('ErrorMsg').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion +
									"&contenidoTitulo="         + contenidoTitulo +
									"&contenidoDescripcion=" 	+ contenidoDescripcion
									);
	
	}
	
	
}

function recargarListaContenido(){
		capsulaId=$('capsula').get('value');
		capsulaVersion=$('version').get('value');
		var elRequest = new Request({
						url		: '../librerias/listar_contenido_capsula_edicion.php', 
						method  :'post',
						onSuccess: function(html) {
							$('ListaContenido').set('html',html);
								var example1 = $('ListaContenido');
								new Sortables(example1,{revert:true,
								onComplete: function(){
									ReOrdenaContenido();
									recargarVistaPrevia();
								}
								});
						},
						//Si Falla
						onFailure: function() {
							$('ListaContenido').set('html', '');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion
									);
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

	
function ValidarAgregarImagen(){
	   archivo1=$('direccionImagen');
	   titulo=$('tituloImagen');
	   $('ErrorMsg').set('html','');
	   if(archivo1.value==''||titulo.value==''){
			$('ErrorMsg').set('html', "<span style='color:red'><b>Seleccione una Imagen para subir y no olvide agregar un t&iacute;tulo</b></span>");
		}else{
			fullName=archivo1.value;
			splitName = fullName.split(".");
			fileType = splitName[1];
			fileType = fileType.toLowerCase();
			if(fileType!='gif'&&fileType!='jpg'&&fileType!='png'){
				$('ErrorMsg').set('html', "<span style='color:red'><b>Solo se permiten imagenes GIF, JPG y PNG</b></span>");
			}else{
			//submit!!!
				document.getElementById('AgregarImagenReal').click();
				}
		}
}

function ValidarAgregarVideo(){
	   archivo1=$('direccionVideo');
	    titulo=$('tituloVideo');
	   $('ErrorMsg').set('html','');
	   if(archivo1.value==''||titulo.value==''){
			$('ErrorMsg').set('html', "<span style='color:red'><b>Seleccione un Video para subir y no olvide agregar un t&iacute;tulo</b></span>");
		}else{
			fullName=archivo1.value;
			splitName = fullName.split(".");
			fileType = splitName[1];
			fileType = fileType.toLowerCase();
			if(fileType!='wmv'&&fileType!='avi'&&fileType!='mp4'){
				$('ErrorMsg').set('html', "<span style='color:red'><b>Solo se permiten videos WMV, AVI y MP4</b></span>");
			}else{
			//submit!!!
				document.getElementById('AgregarVideoReal').click();
				}
		}
}

function ValidarAgregarAudio(){
	   archivo1=$('file1');
	    titulo=$('tituloAudio');
	   $('ErrorMsg').set('html','');
	   if(archivo1.value==''||titulo.value==''){
			$('ErrorMsg').set('html', "<span style='color:red'><b>Seleccione un Audio para subir y no olvide agregar un t&iacute;tulo</b></span>");
		}else{
			fullName=archivo1.value;
			splitName = fullName.split(".");
			fileType = splitName[1];
			fileType = fileType.toLowerCase();
			if(fileType!='wma'&&fileType!='wav'&&fileType!='mp3'){
				$('ErrorMsg').set('html', "<span style='color:red'><b>Solo se permiten audios WMA, WAV y MP3</b></span>");
			}else{
			//submit!!!
				document.getElementById('AgregarAudioReal').click();
				}
		}
}

function FuncionesLista(elemento){
	if(elemento.hasClass('seleccionado')){
		elemento.removeProperty('class');
		elemento.addClass('noseleccionado');
	}else{
		elemento.removeProperty('class');
	    elemento.addClass('seleccionado');
	}
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
			if(confirm('Desea Eliminar el/los elemento(s) seleccionado(s)?')==true){
				for (i=0; i<elementos.length; i++){
				   if(elementos[i].getElementsByTagName('input')[1].checked==true){
						valor=elementos[i].getElementsByTagName('input')[0].value;
						capsulaId=$('capsula').value;
						version=$('version').value;
							EliminaContenido(capsulaId,version,valor);
				   }
				}
				recargarListaContenido();
				recargarVistaPrevia();
			}
	}else{
		$('ErrorListado').set('html', "<span style='color:red'><b>No ha seleccionado ning&uacute;n elemento</b></span>");
	}
}

function EliminaContenido(capsula,version,contenido_id){
var elRequest = new Request({
						url		: '../librerias/eliminar_contenido.php', 
						method  :'post',
						link: 'chain',
						onSuccess: function(html) {
							recargarListaContenido();
							recargarVistaPrevia();
						},
						//Si Falla
						onFailure: function() {
							$('ErrorListado').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsula + 
									"&capsulaVersion=" 	        + version +
									"&contenido_id=" 	        + contenido_id 
									);
}

function ReOrdenaContenido(){
//var elementos = $$("li");
var elementos = document.getElementsByTagName("li");
	for (i=0; i<elementos.length; i++){
			valor=elementos[i].getElementsByTagName('input')[0].value;

			var capsulaId=$('capsula').value;
			var version=$('version').value;
			var orden=i+1;
			Reordena(capsulaId,version,valor,orden);
	}
}

function Reordena(capsula_id,version_id,contenido_id,orden){
var elRequest = new Request({
						url		: '../librerias/ordenar_contenido.php', 
						method  :'post',
						onSuccess: function(html) {
							
						},
						//Si Falla
						onFailure: function() {
							$('ErrorListado').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsula_id + 
									"&capsulaVersion=" 	        + version_id +
									"&contenido_id=" 	        + contenido_id +
									"&orden="					+orden
									);
}

function SeleccionarTodo(valor){
var chequeado=valor.checked;
var elementos = $$("li");
	for (i=0; i<elementos.length; i++){
		elementos[i].getElementsByTagName('input')[1].checked=chequeado;
	}
}



function EditarContenido(contenido_id,tipo){
	var titulo='';
	var capsula=$('capsula').value;
	var version=$('version').value;
	
	var elRequest = new Request({
						url		: '../librerias/traer_contenido_titulo.php', 
						method  :'post',
						link: 'chain',
						onSuccess: function(html) {
							titulo=html;
							
							if(tipo==1){
								$('agregaT').disabled=true;
								$('guardaT').disabled=false;
								$('tituloTexto').value=titulo;
									
									var elRequest2 = new Request({
									url		: '../librerias/traer_contenido_descripcion.php', 
									method  :'post',
									link: 'chain',
									onSuccess: function(html2) {
										$('contenidoId').value=contenido_id;
										myMooEditableInstance.setContent(html2);
									},
									//Si Falla
									onFailure: function() {
										$('ErrorListado').set('html', 'Error de conexi&oacute;n');
									}
								   });
								elRequest2.send(	"capsulaId=" 		        + capsula + 
												"&capsulaVersion=" 	        + version +
												"&contenido_id=" 	        + contenido_id 
												);
							}
							
														
						},
						//Si Falla
						onFailure: function() {
							$('ErrorListado').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsula + 
									"&capsulaVersion=" 	        + version +
									"&contenido_id=" 	        + contenido_id 
									);
	
	
	
		

}
function GuardaTexto(){
var contenidoId=$('contenidoId').value;

	var titulo=$('tituloTexto').get('value');
	var texto=$('content').retrieve('MooEditable').getContent(); 
	var capsulaId=$('capsula').get('value');
	var capsulaVersion=$('version').get('value');
	var contenidoTitulo=titulo;
	var contenidoDescripcion=texto;
	
	$('ErrorMsg').set('html','');
	if(titulo==''||texto.stripTags().length<=1){
		$('ErrorMsg').set('html', "<span style='color:red'><b>Falta ingresar el t&iacute;tulo o el Texto para agregar el contenido</b></span>");
	}else{
		//guardo en base de datos:
		contenidoDescripcion=(texto.replace(/&/g,"||"));
		contenidoDescripcion=(contenidoDescripcion.replace(/\u002b/g,'--'));
		var elRequest = new Request({
						url		: '../librerias/editar_contenido_texto.php', 
						method  :'post',
						urlEncoded :'true',
						onSuccess: function(html) {
							//Limpiar el Div y recargar la lista!!!
							$('formTexto').reset();
							myMooEditableInstance.setContent('');
							$('contenidoId').value='';
							$('agregaT').disabled=false;
							$('guardaT').disabled=true;
							recargarListaContenido();
							recargarVistaPrevia();
							
						},
						//Si Falla
						onFailure: function() {
							$('ErrorMsg').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"capsulaId=" 		        + capsulaId + 
									"&capsulaVersion=" 	        + capsulaVersion +
									"&contenidoTitulo="         + contenidoTitulo +
									"&contenidoDescripcion=" 	+ contenidoDescripcion+
									"&contenidoId="				+ contenidoId
									);
	
	}

}

function Reset(){
	$('formTexto').reset();
	$('formImagen').reset();
	$('formVideo').reset();
	$('formAudio').reset();
	myMooEditableInstance.setContent('');
}

// window.addEvent('domready', function(){
  // //new Fx.Accordion($('accordion'), '#accordion .toggler', '#accordion .content',{initialDisplayFx:false});
    
// });

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
function Cerrar111(){
if(confirm('Desea Cerrar el Asistente?')==true){
	CerrarWizard();
	parent.searchBoxPpal.close();
	}
}
function anterior(){
	window.location='wizard_capsula_1_ed.php';
}
function CancelarCreacionCapsula(){

/*
Pregunto si es nulo el control donde guardo el capsulaId y la versión,
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
	Este Request va a la Base de Datos y elimina todos los datos ingresados de la cápsula.
	Además limpia las variables de sesión donde se guarda el capsulaId y la versión
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
</script>