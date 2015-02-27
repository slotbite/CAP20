<?
session_start();
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$administradorId = $_SESSION['administradorId'] ? $_SESSION['administradorId'] : '0';
$perfilId = $_SESSION['perfilId'] ? $_SESSION['perfilId'] : '0';


$plantilla->setTemplate("header_3");
//


echo $plantilla->show();?>

<script type="text/javascript" src="../scripts/Funciones.js"></script>

<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
 <link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<script type="text/javascript" language="javascript">
	var searchBox;
	window.addEvent('domready', function(){
		searchBox = new multiBox({
		mbClass: '.mb',//class you need to add links that you want to trigger multiBox with (remember and update CSS files)
		container: $('mainDiv'),//where to inject multiBox
		showNumbers: false,
		descClassName: 'multiBoxDesc',//the class name of the description divs
		path: './Files/',//path to mp3 and flv players
		useOverlay: true,//use a semi-transparent background. default: false;
		maxSize: {w:600, h:400},//max dimensions (width,height) - set to null to disable resizing
		addDownload: false,//do you want the files to be downloadable?
		pathToDownloadScript: './Scripts/forceDownload.asp',//if above is true, specify path to download script (classicASP and ASP.NET versions included)
		addRollover: true,//add rollover fade to each multibox link
		addOverlayIcon: true,//adds overlay icons to images within multibox links
		addChain: true,//cycle through all images fading them out then in
		recalcTop: true,//subtract the height of controls panel from top position
		addTips: false,//adds MooTools built in 'Tips' class to each element (see: http://mootools.net/docs/Plugins/Tips)
		autoOpen: 0//to auto open a multiBox element on page load change to (1, 2, or 3 etc)
	});
	});
</script>

<script type="text/javascript" type="text/javascript">


function guardar1(){
valido=true;
nombretema=$('nombre_tema').get('value');
temaid=$('tema_id').get('value');
clienteid=$('cliente_id').get('value');
nombrecapsula=$('nombre_capsula').get('value');
descripcion=$('desc_capsula').get('value');

tipo=$$('input[name=TipoCapsulaG]:checked').get('value');

	if(nombrecapsula==""){
		valido=false;
		$('ErrorNombre').set('html', "<span style='color:red'><b>Debe ingresar un nombre de C&aacutepsula</b></span>");
	}
	if(nombretema==""){
		valido=false;
		$('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema</b></span>");
	}
	if(temaid==""){
		valido=false;
		$('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema Existente</b></span>");
	}
	
	
	if(valido==true){
		//guardar en base de datos:
			
		var elRequest = new Request({
						url		: '../librerias/insertar_capsula.php', 
						method  :'post',
						onComplete: function(html) {
							$('id_capsula_2').value=html;
							$('ErrorNombre').set('html', '');
							$('btnsiguiente').disabled=false;
							alert('Los Datos de la C\u00e1psula han sido guardados Exitosamente');
							$('mb14').dispose();
							$('nombre_tema').disabled=true;
							//$('btncrearTemaFake').setStyle('display', 'block');
							$('lupaTema').removeEvents();
							$('TipoCapsulaG1').disabled=true;
							$('TipoCapsulaG2').disabled=true;
							$('TipoCapsulaG3').disabled=true;
							$('nombre_capsula').removeEvents();
							
									$('nombre_capsula').addEvent('change', function(){
									var elRequest = new Request({
													url		: '../librerias/verificar_nombre_capsula_ed.php', 
													method  :'post',
													onSuccess: function(html) {
														//Limpiar el Div 
														$('ErrorNombre').set('html', '');
														$('ErrorNombre').set('html', html);
													},
													//Si Falla
													onFailure: function() {
														$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
													}
												});
												elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
																"&cliente_id=" 	+ $('cliente_id').get('value')+
																"&capsula_id=" + $('id_capsula_2').get('value')
																
																);
									});
							
							
							$('guarda').removeEvents();
							
							$('guarda').addEvent('click', function(){
								guardar2();
							});
							
							
							
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"cliente_id=" 		+ clienteid + 
									"&tema_id=" 	    + temaid +
									"&nombre_capsula=" 	+ nombrecapsula +
									"&descripcion=" 	+ descripcion +
									"&tipo=" 	        + tipo 
									);
		
		
	}
	
}

function siguiente(){
$('irwizard2').click();
}

function cancelar(){
	if (confirm('Perder\u00e1 todos los cambios realizados a la C\u00e1psula\nEst\u00e1 Seguro?')==true){
	   CancelarCreacionCapsula();
	}
}


function guardaysale(){
if(confirm('Desea Cerrar el Asistente?')==true){
	CerrarWizard();
	parent.searchBoxPpal.close();
	}
}

</script>

<?

$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{

$plantilla->setTemplate("wizard_capsula_1");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"CLIENTE"	=>	"$Cliente_id"
							));
echo $plantilla->show();

}
?>

<?
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
?>

<script type="text/javascript">
window.addEvent('domready', function (){
	
        var clienteid=$('cliente_id').get('value');	        
        
        new Autocompleter.Request.HTML($('nombre_tema'), '../librerias/autocompleter_temas.php', {
		// class added to the input during request
		'indicatorClass': 'autocompleter-loading', 
		'minLength': 1,
		'overflow': true,
		'selectMode': 'type-ahead',

		'postData': {
			'nombre_id'		:   'temaId',
			'nombre_campo' 		:   'temaNombre',
			'nombre_tabla' 		:   'temas',
			'nombre_estado'		:   'temaEstado',
			'clienteId'		:   clienteid,
                        'administradorId'       :   <? echo $administradorId ?>,
                        'perfilId'              :   <? echo $perfilId ?>
		}
                
});



	$('TipoCapsulaG1').addEvent('click', function(){
		$('cuestionario').setStyle('display', 'block');
		$('encuesta').setStyle('display', 'none');
		$('contenido').setStyle('display', 'none');
	});

	$('TipoCapsulaG2').addEvent('click', function(){
		$('cuestionario').setStyle('display', 'none');
		$('encuesta').setStyle('display', 'block');
		$('contenido').setStyle('display', 'none');
		
	});

	$('TipoCapsulaG3').addEvent('click', function(){
		$('cuestionario').setStyle('display', 'none');
		$('encuesta').setStyle('display', 'none');
		$('contenido').setStyle('display', 'block');
	});

	$('nombre_capsula').addEvent('change', function(){
		var elRequest = new Request({
						url		: '../librerias/verificar_nombre_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							//Limpiar el Div 
							$('ErrorNombre').set('html', '');
							$('ErrorNombre').set('html', html);
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
									"&cliente_id=" 	+ $('cliente_id').get('value'));
	});
	
	$('nombre_tema').addEvent('blur', function(){
		var elRequest = new Request({
						url		: '../librerias/traer_id_tema.php', 
						method  :'post',
						onSuccess: function(html) {
							//Limpiar el Div 
							$('ErrorTema').set('html', "");
							
							if(html=='0'){
								$('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema Existente</b></span>");
							}else{
								$('tema_id').set('value',html);
							}
							
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"nombre_tema=" + $('nombre_tema').get('value') +
                                                        "&cliente_id=" + $('cliente_id').get('value') + 
                                                        "&administrador_id=" + <? echo $administradorId ?> + 
                                                        "&perfil_id=" + <? echo $perfilId ?>);
	});
	
	
	$('mBoxContainerTema').setStyle('opacity', '0');



	$('btnCancelarTema').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerTema').tween(property, to);

        });
	
	$('lupaTema').addEvent('click',function(){
		SeleccionarTemas();
	});
	
	$('guarda').addEvent('click',function(){
		guardar1();
	});
	
});


function SeleccionarTemas(){
            
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : '../librerias/FuncionBuscarTemas.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentTema').set('html',datos);                                                            
                $('mBoxContainerTema').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
        }
    });

    elRequest.send();  
}

function SeleccionarTema(){
        
    tabla = $('tablaListaTemas');                
    filas = tabla.rows.length;
    
    for(i = 0; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1].innerHTML;            
            $('nombre_tema').set('value', objInput1);   // nombre del input tema
			$('nombre_tema').focus();
			$('nombre_capsula').focus();
            i = filas;
        }           
    }
        
    $('mBoxContainerTema').tween("opacity", 0);                            
    
}

function CancelarCreacionCapsula(){

/*
Pregunto si es nulo el control donde guardo el capsulaId y la versi�n,
porque en la primera pantalla del wizard aun no se han asignado esos 
valores ni creado los controles, por lo tanto no es necesario ir a la 
base de datos con el Request.
*/

	capsulaId=0;
	capsulaVersion =1;
	
	if( $('id_capsula_2')!=null){
	capsulaId=$('id_capsula_2').get('value');
	}
	
	// if($('version')!=null){
	// capsulaVersion = $('version').get('value');
	// }
	
	
	if(capsulaId != 0 && capsulaVersion!= 0){
	
	/*
	Este Request va a la Base de Datos y elimina todos los datos ingresados de la c�psula.
	Adem�s limpia las variables de sesi�n donde se guarda el capsulaId y la versi�n
	*/
	
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
	
	//window.location='adm_capsulas.php';
	parent.searchBoxPpal.close();
	
}

function guardar2(){
valido=true;
nombretema=$('nombre_tema').get('value');
temaid=$('tema_id').get('value');
clienteid=$('cliente_id').get('value');
nombrecapsula=$('nombre_capsula').get('value');
descripcion=$('desc_capsula').get('value');

tipo=$$('input[name=TipoCapsulaG]:checked').get('value');

	if(nombrecapsula==""){
		valido=false;
		$('ErrorNombre').set('html', "<span style='color:red'><b>Debe ingresar un nombre de C&aacutepsula</b></span>");
	}
	
	var errores=$('ErrorNombre').get('html');
	
	if(errores!=''){
		valido=false;
	}
	
	if(valido==true){
		//guardar en base de datos:
		//y de ahi hacer el redirect a la siguiente pantalla
		
		
		
		
		
		var elRequest = new Request({
						url		: '../librerias/editar_capsula.php', 
						method  :'post',
						onSuccess: function(html) {
							$('ErrorNombre').set('html', '');
							alert('Los Datos de la C\u00e1psula han sido guardados Exitosamente');
						},
						//Si Falla
						onFailure: function() {
							$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	"cliente_id=" 		+ clienteid + 
									"&nombre_capsula=" 	+ nombrecapsula +
									"&descripcion=" 	+ descripcion +
									"&capsula_id="      + $('id_capsula_2').get('value')+
									"&capsula_ver=1"    
									);
		
	}
	
}



window.addEvent('domready', function (){
$('id_capsula_2').value=<? echo $_SESSION['capsulaId2']? $_SESSION['capsulaId2']:0?>;
	if($('id_capsula_2').value!=0){
	$('btnsiguiente').disabled=false;
	$('mb14').dispose();
	$('nombre_tema').disabled=true;
	//$('btncrearTemaFake').setStyle('display', 'block');
	$('lupaTema').removeEvents();
	$('TipoCapsulaG1').disabled=true;
	$('TipoCapsulaG2').disabled=true;
	$('TipoCapsulaG3').disabled=true;
	$('nombre_capsula').removeEvents();

			$('nombre_capsula').addEvent('change', function(){
			var elRequest = new Request({
							url		: '../librerias/verificar_nombre_capsula_ed.php', 
							method  :'post',
							onSuccess: function(html) {
								//Limpiar el Div 
								$('ErrorNombre').set('html', '');
								$('ErrorNombre').set('html', html);
							},
							//Si Falla
							onFailure: function() {
								$('ErrorNombre').set('html', 'Error de conexi&oacute;n');
							}
						});
						elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
										"&cliente_id=" 	+ $('cliente_id').get('value')+
										"&capsula_id=" + $('id_capsula_2').get('value')
										
										);
			});


		$('guarda').removeEvents();

		$('guarda').addEvent('click', function(){
			guardar2();
		});
	}
});
</script>
<?	
	$CapsulaIdNueva=$_SESSION['capsulaId2'] ? $_SESSION['capsulaId2'] :0;
	if($CapsulaIdNueva!=0){
		$capsula_id=$CapsulaIdNueva;
		$queryTipo 	= "EXEC capVerCapsula ".$cliente_id.",".$capsula_id.",1 ";
		//echo $queryTipo;
		$result = $base_datos->sql_query($queryTipo);
		$row	= $base_datos->sql_fetch_assoc($result);
		$Capnombre = mb_convert_encoding($row["capsulaNombre"],"UTF-8","ISO-8859-1");
		$capsulaTipo = $row["capsulaTipo"];
		$temaNombre = mb_convert_encoding($row["temaNombre"],"UTF-8","ISO-8859-1");
		$temaId=$row["temaId"];
		$desc=mb_convert_encoding($row["capsulaDescripcion"],"UTF-8","ISO-8859-1");
		//echo $desc;
		//quiere decir que la c�psula ya existe, o sea viene del boton "Anterior"
		echo "<script>$('nombre_capsula').value='$Capnombre';</script>";
		echo "<script>$('nombre_tema').value='$temaNombre';</script>";
		echo "<script>$('desc_capsula').value='$desc';</script>";
		echo "<script>$('tema_id').value='$temaId';</script>";

	}
?>

