<?
include ("librerias/conexion.php");
include ("librerias/crypt.php");
session_start();
//echo $_SESSION['$usuarioId'];

$envio = $_SESSION["cap_envio"];
$capsulaid = $_SESSION["cap_capsulaid"];
$version = $_SESSION["cap_version"];
$clienteid = $_SESSION["cap_clienteid"];
$usuarioId = $_SESSION["cap_usuarioId"];
$comentario = $_POST['comentario'] ? $_POST['comentario'] : '';
$DISABLED = FALSE ;   // VALIDA UNA COMPORTAMIENTO PARA LOS BOTONES DE LOS FEEDDBACK

$encrypted = urldecode($_GET['hash']) ? urldecode($_GET['hash']) : urldecode($_POST['hash']);
$iv = "brains12";

$i4 = enc_dec($encrypted);

$arreglo1 = explode("&", $i4);
if (count($arreglo1) == 4) {
    $capsulaid1 = explode("=", $arreglo1[0]);
    $capsulaid = $capsulaid1[1];

    $version1 = explode("=", $arreglo1[1]);
    $version = $version1[1];

    $usuario1 = explode("=", $arreglo1[2]);
    $usuarioId = $usuario1[1];

    $envio1 = explode("=", $arreglo1[3]);
    $envio = $envio1[1];

//$vars Se envian por ajax ...validar encuesta.js
    $_SESSION["cap_envio"] 		= $envio;
    $_SESSION["cap_usuarioId"] 	= $usuarioId;
    $_SESSION["cap_capsulaid"] 	= $capsulaid;
    $_SESSION["cap_version"] 	= $version;

    //echo "capsulaid($capsulaid)) && (version($version)) && (version($usuarioId)) && (envio($envio)";
    // valida parametros de ingreso
    if ((is_numeric($capsulaid)) && (is_numeric($version)) && (is_numeric($usuarioId)) && (is_numeric($envio))) {

        $queryEX = "EXEC CapVerificaEnvio " . $envio . "," . $capsulaid . "," . $usuarioId . "";
        $resultEX = $base_datos->sql_query($queryEX);
        $rowEX = $base_datos->sql_fetch_assoc($resultEX);

        $existe = $rowEX['existe'] ? $rowEX['existe'] : 0;
        if ($existe == 0) {
            // HTML - No existe capsula
            $mensaje = "Error: El envio ya no esta disponible";
        } else {

            $queryL = "EXEC envTraeLogoUsuario " . $usuarioId . " ";
            //echo "queryL $queryL<br/>";
            $resultL = $base_datos->sql_query($queryL);
            $rowL = $base_datos->sql_fetch_assoc($resultL);
            $logo = $rowL['organizacionLogo'] ? $rowL['organizacionLogo'] : '';
            $nombreOrg = $rowL['organizacionNombre'] ? $rowL['organizacionNombre'] : '';

            $logo = substr($logo, 3, strlen($logo));

            $query2 = "EXEC envTraeDatosUsuario " . $usuarioId . " ";
            $result2 = $base_datos->sql_query($query2);
            $row2 = $base_datos->sql_fetch_assoc($result2);
            $nombre = $row2['nombres'] ? $row2['nombres'] : '';
            $clienteId = $row2['clienteId'] ? $row2['clienteId'] : '';
            $_SESSION["cap_clienteid"] = $clienteId;

            $queryTipo = "EXEC capVerCapsula " . $clienteId . "," . $capsulaid . "," . $version . " ";
            $result = $base_datos->sql_query($queryTipo);
            $row = $base_datos->sql_fetch_assoc($result);
            $Capnombre = $row["capsulaNombre"];
            $tipo = $row["capsulaTipo"];
            $_session["cap_tipo"] = $tipo;
            $encabezado = $row["temaImagen"];


            //$comentario = $row["capsulaComentario"];
            $comentario = 1;
            $clienteId = $row["clienteId"];
            $estadoCapsula = $row["capsulaEstado"];
            //echo "envio:".$envio;
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT"/>
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <title>C&aacute;psulas de Conocimiento</title>


		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<script src="scripts/jquery-mobile/jquery-1.8.3.min.js"></script>
		<script src="scripts/jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
		<link  href="scripts/jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet">




        <link rel="stylesheet" type="text/css" href="css/capsula.css" media="screen" />
        <script type="text/javascript" src="scripts/jwplayer.js"></script> 
		
<!-- 	
        <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script> 
        <script type="text/javascript" src="scripts/Observer.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.Request.js"></script> -->
		
		
        <script type="text/javascript" src="scripts/Funciones.js"></script>
		
        <link rel="stylesheet" type="text/css" href="skins/saam/Autocompleter.css" />

<style>
	body{
		margin:1px;
		width: auto;
		height: auto;
	}

	ul {
		list-style-type: disc;
		list-style-position:inside;
	}
	ul li
	{
		list-style-type: disc;
		list-style-position:inside;
	}            
	#divCentral{
		background-color: #F8F8F8;
		width:98%;
		margin: 0px auto;
		
		border: 1px solid #CCC;                                 
		color: #5A5655; 
		-webkit-border-radius: 5px;                                
	}
	.ui-popup-container {
		width: 100%;
	}        

</style>


<style>
 /*http://html-generator.weebly.com/css-textbox-style.html   divFeedback-> preguntas  */
.textbox {
    height: 25px;
    
    border: solid 1px #E5E5E5;
	border-radius: 5px;
    outline: 0;
    font: normal 13px/100% Verdana, Tahoma, sans-serif;
    background: #FFFFFF url('bg_form.png') left top repeat-x;
    background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
    background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
    box-shadow: rgba(0, 0, 0, 0.1) 0 0 8px;
    -moz-box-shadow: rgba(0, 0, 0, 0.1) 0 0 8px;
    -webkit-box-shadow: rgba(0, 0, 0, 0.1) 0 0 8px;
}

.textbox:focus {
    border-color: #C9C9C9;
    -webkit-box-shadow: rgba(0, 0, 0, 0.15) 0 0 8px;
}   

	
	.Feed, .Feed1 {
		-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
		-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
		box-shadow:inset 0px 1px 0px 0px #ffffff;
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
		background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
		background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
		background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
		background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
		background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);
		background-color:#f9f9f9;
		border:1px solid #dcdcdc;
		/*display:inline-block;*/
		cursor:pointer;
		color:#666666;
		padding:5px 5px;
		text-decoration:none;
		text-shadow:0px 1px 0px #ffffff;
		width: 60px;
		height: 14px;
		text-align:center;
		font-size:12px;
	}
	.Feed:hover {
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));
		background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
		background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
		background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
		background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
		background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);
		background-color:#e9e9e9;
	}
	.Feed:active {
		position:relative;
		top:1px;
	}
	input:hover{
		cursor:pointer;
	}
	
	.F1 { border-spacing: 0;}
	.F1 tr:first-child td:nth-child(4) {border-top-left-radius: 6px;}
	.F1 tr:first-child td:last-child  {border-top-right-radius: 6px;}
	.F1 tr:last-child td:nth-child(4)  {border-bottom-left-radius: 6px;}
	.F1 tr:last-child td:last-child   {border-bottom-right-radius: 6px;}
	
	
	/*controla el largo de los capos cuantificativos del Feedback*/
	input[type="text"] { 
		width: 60px;
		min-width: 60px;
		max-width: 510px;
		transition: width 0.15s;    
		text-align: center;
	}
	
</style>
		<!-- <script type="text/javascript" src="scripts/Submit_Capsula.js"></script>  --> 
		<script type="text/javascript" >
$(document).ready(function(){ 
$(function(){
	/*  
		-- Dasabilita entradas de datos si el  plazo de la capsula se termino 
		-- Guarda los comentarios de la encuesta
	*/
	
	if ($('#cerrada').length > 0) {
		var cerrada = $('#cerrada').val();

		if (cerrada == "1") {
		
			//desabilita comentarios
			var elementosTexArea = $('#divCentral').find('textarea') ;
			console.log(elementosTexArea);
			elementosTexArea.each(function(i, v) {
				//var $TexArea = $(this);
					$(this).attr('readonly','readonly');
					$(this).css("background-color",'#d2d2d2');
			});

/* 			var elementosDiv = $(".divComentarioTexto");
			for (i = 0; i < elementosDiv.length; i++) {
				elementosDiv[i].css("background-color",'#d2d2d2');
			} */
			
			//desabilita todas las alternativas
			var Alternativas = $('#divCentral').find('option') ;
			Alternativas.each(function(i, v) {
				
				$(this).attr('disabled','disabled')
				
			});
			
			

		}
	}
	

	
	$('.guardarComentario').on('click', function() {
				
		var $btn=$(this);
		var ID = $btn.parent().parent().parent().parent().find('.contenidoComentarioId').attr("value");
		var Comentario=$btn.parent().parent().parent().parent().find('textarea').val();
/* 		
		console.log(ID+Comentario);
		console.log('<? echo $envio;  ?>'+'<? echo $usuarioId; ?>'+'<? echo $capsulaid;  ?>'+'<? echo $version;  ?>'	);       
		E=<? echo $envio;  ?>;
		U=<? echo $usuarioId; ?>;
		C=<? echo $capsulaid;  ?>;
		V=<? echo $version;  ?>;
		console.log(E, U, C, V); 
*/
		
		
		// Envio de datos
		$.ajax({
				url: 'http://192.168.74.106:81/CAP20/guardar_comentario.php', 				// LOCALHOST
				//url: 'http://186.67.146.11/CAP20/guardar_comentario.php',					//  BRAINS
				dataType: 'jsonp',
				type: "POST",
				jsonp: 'jsoncallback',
				crossDomain: true,
				data : {	ID			: ID,
							Comentario	: Comentario ,
							envio		:	<? echo $envio;  		?>,
							usuarioId	:	<? echo $usuarioId; 	?>,
							capsulaid	:	<? echo $capsulaid;  	?>,
							version		:	<? echo $version;  		?>  							
						} , 
				timeout: 5000,
				success: function(data){
					
					//console.log(data); // datos recividos
					//alert("Gracias por responder esta capsula.");
					
				},
				error: function (request, status, error) {
					alert(request.responseText);
					console.log('Error en el envio  ($.ajax)'+error);
				}
		});
		
	});
	

	});
});
		</script> 

        <? if ($tipo == 1) { ?>
		
			<!-- <script type="text/javascript" src="scripts/Validar_Cuestionario.js"></script> -->
			
			<script type="text/javascript" >
$(document).ready(function(){ 
$(function(){

/*  
	-- Valida las entradas de los datos
	-- Envia las  respuestas del cuaetionario por medio de un JSON en ajax
*/
	$(".validar").click(function(e) {
		e.preventDefault();
		var boton = $(this);
		var AlternativasJson= [];
		var respuestas = "";
		var columnName = "";
		
		//rescatar el ID de la pregunta 
		var ID_pregunta = $(this).parent().parent().parent().parent().parent().parent().find('.PreguntaId').attr("value"); // imput hiden con el ID de la pregunta 
		
		//selector de  mensajes de respuesta 
		var positivo = $(this).parent().parent().parent().parent().parent().parent().find('.positivo');
		var mensaje_P = positivo.find('.mensajePositivo');
		
		var negativo = $(this).parent().parent().parent().parent().parent().parent().find('.negativo');
		var mensaje_N = negativo.find('.mensajeNegativo');
		
		//busca el select de la pregunta con las alternativas 
		var Alternativas = $(this).parent().parent().find('.alternativas').eq(1); // el hermano sup del div boton, con elemento select .alternativas
		
		//Validando la seleccion de alternativas ......
		var checkeado = false;
		Alternativas.each(function(i, v) {
			
			var $el = $(this),
			//busca la opcion seleccionada 
			$selected = $el.find('option:selected');    
			
			// mientras no sea la por defecto "Alternativas"
			if( $selected.val() != 'default'){ 			
			
				//alternativa seleccionada
				console.log('Alternativa:'+$selected.val()+', Pregunta ID :'+ID_pregunta+', Respuesta :'+$selected.text());
				checkeado = true;
			/*}else{
				console.log('no se ha seleccionado nada ');	 */
			}
		});
		
		if (checkeado == false) {
			alert("Selecciona una alternativa para continuar ");
			return false;
		}

		//Se crea un ArrayJson con los datos de la capsula
		//busca las opciones seleccionadas 
		//Formato json//AlternativasJson+='[';

		var flag =0 ;
		Alternativas.find("option").each(function(){	
		
			// if (flag !=0){
				// AlternativasJson+=',';
			// }
			
			//mientras opcion no sea pleaceholder
			if( $(this).val() != 'default'){ 		
			
				// seleccionada o no 
				if( $(this).attr('selected')  ){ 
				
					//AlternativasJson+='{"preguntaid":'+ID_pregunta+',"alternativa":'+$(this).val()+',"respuesta":"true"}'; //checked
						AlternativasJson.push({preguntaid:ID_pregunta,alternativa:$(this).val(),respuesta:true}); //checked
						flag=1;
				}else{
					//AlternativasJson+='{"preguntaid":'+ID_pregunta+',"alternativa":'+$(this).val()+',"respuesta":"false"}'; //unchecked
						AlternativasJson.push({preguntaid:ID_pregunta,alternativa:$(this).val(),respuesta:false}); //unchecked
						flag=1;
				}
			}
		});	
		//AlternativasJson+=']';
		//console.log(AlternativasJson);
		// Envio de datos
		$.ajax({
				url: 'http://192.168.74.106:81/CAP20/validar_respuestas.php', // LOCALHOST
				//url: 'http://186.67.146.11/CAP20/validar_respuestas.php',		//BRAINS PRUEBAS
				dataType: 'jsonp',
				type: "GET",
				jsonp: 'jsoncallback',
				crossDomain: true,
				//data : { json : [{"preguntaid":151,"alternativa":427,"respuesta":"false"},{"preguntaid":151,"alternativa":428,"respuesta":"true"}] } , //tipo string
				data : { 	json 			:    AlternativasJson 		  ,
							cap_envio		:	<? echo $envio;  		?>,
							cap_capsulaid	:	<? echo $capsulaid; 	?>,														
							cap_version		:	<? echo $version; 		?>,                                                     
							cap_clienteid	:	<? echo $clienteid;  	?>, 	
							cap_usuarioId	:	<? echo $usuarioId;  	?>  												
						},  // AlternativasJson sin tratar...      Resto de variables que no se rescatan por _session           
				timeout: 5000,                                                                                            
				success: function(data){

					//desabilita todas las opciones
					Alternativas.find('option').each(function(i, v) {

						$(this).attr('disabled','disabled')
						
					});
					Alternativas.selectmenu('refresh', true);
					
					//muestra el mensaje de respuesta
				   if (data.tipo == "positivo") {
						mensaje_P.text(data.mensaje);
						positivo.css("display", "block"); 
					}
					if (data.tipo == "negativo") {
						mensaje_N.text(data.mensaje);
						negativo.css("display", "block");				
					}
					// desabilita y recarga el boton
					boton.prop('disabled', true);
					boton.button("refresh");
				},
				error: function (request, status, error) {
					alert(request.responseText);
					console.log('Error al validar la respuesta'+error);
				}
		});
	});
});
});
			</script>
			

        <? } ?>
        <? if ($tipo == 2) { ?>
		
		<!-- <script type="text/javascript" src="scripts/Validar_Encuesta.js"></script> -->
				
		<script type="text/javascript">
		
$(document).ready(function(){ 
$(function(){

/*  
	-- Envia las  respuestas de la encuesta por medio de un JSON en ajax
	-- Valida las entradas de los datos
	-- Guarda los comentarios de la encuesta
*/
	//Boton Enviar encuesta (ID)
	$("#Enviar_Encuesta").click(function(e) {
		e.preventDefault();
		
		var boton = $(this);
		var validar 	= true;			// valida si todas las preguntas fueron respondidas
		var validar2 	= true;			// valida si todas los comentarios fueron respondidos
		var checkeado 	= false;		// valida si se checkeo alguna de las opciones de cada pregunta
		var f_checkeado = false;	// valida si se checkeo alguna de las preguntas de cada feedback
		//Validando preguntas......
		
		// lista de elementos 
		preguntas = $('#divCentral').find('select');
		//por cada pregunta
		preguntas.each(function(i, v) {
			
			checkeado = false;
			var $alternativa = $(this);
			
			//busca la opcion seleccionada 
			$seleccionada = $alternativa.find('option:selected'); 
			
			// mientras no sea la por defecto "Alternativas" (placeholder) ; que siempre estara seleccionada.
			if( $seleccionada.val() != 'default'){ 				
				//el usuario selecciono una alternativa
				checkeado = true;
			}
			if ( checkeado == false ) {
				validar = false;
			}
		});																					//OK

		// validando cometarios....
		
		//lista de elementos 
		Comentarios = $('#divCentral').find('textarea') ;
		//por cada comentario
		Comentarios.each(function(i, v) {
			
			var $texto = $(this);
			var id 		= $texto.attr('id');
			var text 	= $texto.val();
				text 	= $.trim(text);
			// valida campo vacio
			if ( id == "textAreaObligatorio" && text == ''){
				validar2 = false;
			}
		});																					//OK
		
		// validando feedback....
		
		
		 var checkboxs = $('#divCentral').find('input[type=radio]');
		
		f_checkeado = false;
		checkboxs.each( function(p, q ) {

			// valida campo vacio
			if ( $(this).is(':checked')   ){
				f_checkeado = true;
			}		

		});
		

																				//OK
		
//ALERTS-----------------------------------------------------------------------------------------------
		
		if ( validar == false && validar2 == false && f_checkeado == false) {
			alert("Debes responder todas las preguntas y completar todos los campos.");
			return false;
		}
		else if (validar == false) {
			alert("Debes responder todas las preguntas.");
			return false;
		}
		else if (validar2 == false) {
			alert("Debes completar todos los campos.");
			return false;
			
		
		}else if (f_checkeado == false) {
			alert("Debes responder todos los Feedback.");
			return false;
			
		
		}else{																					//OK
			//TODO OK SE MANDAN LOS DATOS 
			
			
			
			//ENVIANDO LOS COMENTARIOS 
			Lista_btns= $('#divCentral').find('.guardarComentario');
			Lista_btns.each(function(i, v) {
				$(this).click();
			});
			//-------------------------------------------------------------------------------- --\\
			
			// SE RECORREN LAS ALTERNATIVAS SELECCIONADAS != DEFAULT
			var Alternativas = $('#divCentral').find('option:selected') ;
			//console.log(Alternativas);
			Alternativas.each(function(i, v) {
				
				var id_a = $(this).val(); 		// id de la alternativa
				var id_p	=$(this).attr('id'); 	// id de la pregunta 
				
				// Envio de datos
				
				
				$.ajax({
					url		: 'http://192.168.74.106:81/CAP20/validar_encuesta.php', // verificar URL validar_respuesta 192.168.74.106
					//url			: 'http://186.67.146.11/CAP20/validar_encuesta.php',		//BRAINS PRUEBAS
					dataType	: 'jsonp',
					type		: "GET",
					jsonp		: 'jsoncallback',
					crossDomain	: true,
					timeout		: 5000,   
					data	 	:{ 	Pregunta		:	id_p,
									Alternativa		:	id_a,
									capsulaId		:	<? echo $capsulaid; ?>,
									envioId			:	<? echo $envio; 	?>,														                                         
									usuarioId		:	<? echo $usuarioId; ?>, 	
									versionId		:	<? echo $version;  	?>  												
								 }, 	
																											 
					success		:function(data){									
									console.log('data'+data.mostrarmensaje);
									
									alert('Gracias por responder esta encuesta');
								},
								
					error		:function (request, status, error) {
									
									console.log('La encuesta no se ha enviado'+error);
									return false;
								}
				});
				
				
			});
			//-------------------------------------------------------------------------------- \\

			
			// SE ENVIAN LOS FEEDBACK
			var AlternativasFeedbackJson= [];
			var T_Feedback = $('#divCentral').find('table[class=F1]').find('tr') ;
			//console.log(Alternativas);
			T_Feedback.each(function(i) {
				
				//console.log($(this));
					
				if ( $(this).attr('id') != 'first' ){
					// eq 0 vacio
					// var td1 = $(this).find("td").eq(1);
					// var td2 = $(this).find("td").eq(2);
					var input = $(this).find('input[type=radio]');
					
					input.each(function(i,v) {
						if ( $(this).prop("checked")){
							// console.log('-v-'+$(this).val());
							// console.log('-n-'+$(this).attr('name'));
							// console.log('-p-'+$(this).data('pos')); 
							AlternativasFeedbackJson.push({
															feedbackId			:$(this).val() ,
															alternativaId		:$(this).attr('name'),
															posicion			:$(this).data('pos')
															});
						}
						
					});
					//console.log(AlternativasFeedbackJson);
					$.ajax({
							url: 'http://192.168.74.106:81/CAP20/validar_AlternativasFeedback.php', // LOCALHOST
							//url: 'http://186.67.146.11/CAP20/validar_AlternativasFeedback.php',		//BRAINS PRUEBAS
							dataType: 'jsonp',
							type: "GET",
							jsonp: 'jsoncallback',
							crossDomain: true,
							//data : { json : [{"feedbackId":151,"id_alternativa":427,"posicion":"1"},{"preguntaid":151,"alternativa":428,"respuesta":"true"}] } , //tipo string
							data : { 	json 			:    AlternativasFeedbackJson 		  ,
										cap_envio		:	<? echo $envio;  		?>,
										cap_capsulaid	:	<? echo $capsulaid; 	?>,														
										cap_version		:	<? echo $version; 		?>,                                                     
										cap_clienteid	:	<? echo $clienteid;  	?>, 	
										cap_usuarioId	:	<? echo $usuarioId;  	?>  												
									},             
							timeout: 5000,                                                                                            
							success: function(data){
										
								alert(data["estado"]);
										
								//desabilita todas las opciones
								 $('#divCentral').find('input:radio').attr('disabled','disabled') ;

							},
							error: function (request, status, error) {
								alert(request.responseText);
								console.log('Error al validar las alternativas feedback'+error);
							}
					});
				}
			});	
			//-------------------------------------------------------------------------------- \\
			
		
 			// Desabilitando alternativas
			var  op = $('#divCentral').find('option');

			op.each(function(i, v) {
				$(this).attr('disabled','disabled')
				selec = $(this).parent();
				selec.selectmenu('refresh', true);
			});	
		
			// Preguntas respondida
			boton.prop('disabled', true);
			boton.button('refresh');
			
			
			//$('#Estado_encuesta').css("display", "block");		
			//parent.location.reload();
			
		}
});
	
	
	
	
	
	
	

	
	
	
	
	
});
});
		
		</script>
		
<script type="text/javascript">

			
			
function validarFeedback(){
	
	
	
	// SE RECORREN LOS FEEDBACK
	var AlternativasFeedbackJson= [];
	var T_Feedback = $('#divCentral').find('table[class=F1]').find('tr') ;
	//console.log(Alternativas);
	T_Feedback.each(function(i) {
		
		//console.log($(this));
			
		if ( $(this).attr('id') != 'first' ){
			// eq 0 vacio
			// var td1 = $(this).find("td").eq(1);
			// var td2 = $(this).find("td").eq(2);
			var input = $(this).find('input[type=radio]');
			
			input.each(function(i,v) {
				if ( $(this).prop("checked")){
					// console.log('-v-'+$(this).val());
					// console.log('-n-'+$(this).attr('name'));
					// console.log('-p-'+$(this).data('pos')); 
					AlternativasFeedbackJson.push({
													feedbackId			:$(this).val() ,
													alternativaId		:$(this).attr('name'),
													posicion			:$(this).data('pos')
													});
				}
				
			});
			//console.log(AlternativasFeedbackJson);
			$.ajax({
					url: 'http://192.168.74.106:81/CAP20/validar_AlternativasFeedback.php', // LOCALHOST
					//url: 'http://186.67.146.11/CAP20/validar_AlternativasFeedback.php',		//BRAINS PRUEBAS
					dataType: 'jsonp',
					type: "GET",
					jsonp: 'jsoncallback',
					crossDomain: true,
					//data : { json : [{"feedbackId":151,"id_alternativa":427,"posicion":"1"},{"preguntaid":151,"alternativa":428,"respuesta":"true"}] } , //tipo string
					data : { 	json 			:    AlternativasFeedbackJson 		  ,
								cap_envio		:	<? echo $envio;  		?>,
								cap_capsulaid	:	<? echo $capsulaid; 	?>,														
								cap_version		:	<? echo $version; 		?>,                                                     
								cap_clienteid	:	<? echo $clienteid;  	?>, 	
								cap_usuarioId	:	<? echo $usuarioId;  	?>  												
							},             
					timeout: 5000,                                                                                            
					success: function(data){
								
						alert(data["estado"]);
								
						//desabilita todas las opciones
						 $('#divCentral').find('input:radio').attr('disabled','disabled') ;

					},
					error: function (request, status, error) {
						alert(request.responseText);
						console.log('Error al validar las alternativas feedback'+error);
					}
			});
		}
	});	



}	
			

</script>
        <? } ?>
		
		
		
		
		
		
    </head>
    <body>
        <form id="evaluacion" method="POST" action="capsula.php">
            <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

            <div id="divCentral">

                <center>

                    <div style="background: #FFFFFF;  padding:10px; margin-bottom: 20px;">

                        <? if ($existe == 0) { ?>
                            <table border="1"  CELLSPACING='0' CELLPADDING='0' Width="60%" height="0 auto" align="center" style="font-size:14px; ;">
                                <tr>
                                    <td style="background-color:#4B6C9F;color:white;vertical-align:middle;">
                                        <img src="skins/saam/img/delete.png" border='0' style="padding-top:4px;padding-right:5px;"/><b>Error</b>
                                        <br/><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="height:200px;">Error: El envio ya no esta disponible</td>
                                </tr>
                            </table>  
                        <? } else {
                            ?>
                            <table class="tablaTema" CELLSPACING='0' CELLPADDING='0' style=" max-width: 100%; ">
                                <tr>                                
                                    <? if (trim($encabezado) == "") { ?>
									
									
									
									<td id="tdCabeceraTema1"  style="border:1px solid #4B6C9F;">
                                             <img id="temaUrl" style="width:100%;" src="/CAP20/mantenedores/encabezados/1_9.jpg" border="0"> 
                                        </td>
									
                                        <!-- <td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                            <span style='font-weight:bolder;font-size:20px; '>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                        </td> -->
                                        <?
                                    } else {
                                        ?>
										 
                                        <td id="tdCabeceraTema2"  style="border:1px solid #4B6C9F;">
                                            <img id="temaUrl" style="width:100%;"   src='<? echo htmlentities("/CAP20" . $encabezado); ?>' border='0'  >
                                        </td>                                
                                    <? } ?>    
										<td style="border:1px solid #4B6C9F; align='center'">
											<img src='<? echo $logo; ?>' border='0' width="100%" >
										</td>
									<!--
                                    <td style="border:1px solid #4B6C9F; ">
                                        <img src='' border="0" width="190px " >
                                    </td>
									-->
                                </tr>
                                <tr>
                                    <td id='FX' style="padding-left: 25px" colspan="2" align="center" align="center" >
                                        <h3 >Bienvenido(a) </h3>
                                        <h3><? echo htmlentities($nombre); ?></h3>
                                        <br/><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdTituloCapsula" align="center" colspan="2"  style=" word-wrap: break-word ">
                                        <? if ($tipo != '') { ?>
                                            <h3><? echo htmlentities(stripslashes($Capnombre)); ?></h3>
                                        <? } ?>
                                    </td>
                                </tr>
                            </table>

                            <?PHP
                            // Si la c�psula no est� anulada, se verifica el cierre
                            $queryDur = "EXEC capVerificaCierre " . $usuarioId . "," . $capsulaid . "," . $version . "," . $envio . "";
                            $resultD = $base_datos->sql_query($queryDur);
                            $rowD = $base_datos->sql_fetch_assoc($resultD);
                            $plazo = $rowD["plazo"];
                            ?>

                            <? if ($tipo != 3) { ?>

                                <br/>
                                <div  id="Alerta"  >
                                    <?
                                    // Advertencia - Se muestra el plazo para el cierre de la capsula
                                    if ($plazo >= 0) {                                                                                

                                        if ($plazo == 0) {
                                            echo "<b>Recuerde:</b> Usted tiene sólo hoy para responder esta C&aacute;psula";
                                        }

                                        if ($plazo == 1) {
                                            echo "<b>Recuerde:</b> Usted tiene 1 d&iacute;a para responder esta C&aacute;psula";
                                        }

                                        if ($plazo > 1) {
                                            echo "<b>Recuerde:</b> Usted tiene <b>$plazo</b> d&iacute;as para responder esta C&aacute;psula";
                                        }

                                        $cerrada = 0;
                                    } else {
                                        echo "<b>ATENCI&Oacute;N:</b> Esta C&aacute;psula ya ha sido cerrada";
                                        $cerrada = 1;
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>                       
                            <br/>

                            

                                <input type='hidden' id='envioId' name='envioId' value='<? echo $envio; ?>'/>
                                <input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid; ?>'/>
                                <input type='hidden' id='versionId' name='versionId' value='<? echo $version; ?>'/>
                                <input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuarioId; ?>'/>
                                <input type="hidden" id='cerrada' value='<? echo $cerrada; ?>'/>
								
								
								
								
								
						<!--    Capsula   .... jQueryMobile    -->
						<div data-role="collapsible-set"  data-theme="b" data-content-theme="b">

							<div data-role="collapsible" data-collapsed="false">
							

                                <!--    Título de la capsula    -->
								<? // comprueba que la capsula tenga un nombre 
								   if( htmlentities(stripslashes($Capnombre)) != ''){ ?>

                                          <?  if($tipo == 1){ ?>

                                                <h1>Cuestionario 

                                           <? }elseif ($tipo == 2 ) { ?>

                                                <h1>Encuesta  

                                           <? }elseif ($tipo == 3 ) { ?>

                                                <h1> Contenido 

                                        <? } echo htmlentities(stripslashes($Capnombre));?>   </h1>
										
								<? }else { ?>	
								
										<h1>C&aacute;psulas de conocimiento</h1>
								
								<? } ?>	
								

                                <?PHP
                                $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaid . "," . $version . "";
                                //echo "queryCapsula $queryCapsula <br/>";
                                $resultCapsula = $base_datos->sql_query($queryCapsula);

                                $i = 1;
								$f = 1;
								$num = 0;
								$cont_F=0;
								$TR ='';
                                while ($rowCapsula = $base_datos->sql_fetch_assoc($resultCapsula)) {
									$elementoTipo 			= $rowCapsula['elementoTipo'] ? $rowCapsula['elementoTipo'] : "";
									$preguntaId 			= $rowCapsula['preguntaId'] ? $rowCapsula['preguntaId'] : 0;
									$feedbackId 			= $rowCapsula['feedbackId'] ? $rowCapsula['feedbackId'] : 0;
									$contenidoDescripcion 	= $rowCapsula['contenidoDescripcion'] ? $rowCapsula['contenidoDescripcion'] : "";
									$contenidoUrl 			= $rowCapsula['contenidoUrl'] ? $rowCapsula['contenidoUrl'] : "";
									$preguntaTexto 			= $rowCapsula['preguntaTexto'] ? $rowCapsula['preguntaTexto'] : "";
									$contenidoObligatorio 	= $rowCapsula['contenidoObligatorio'] ? $rowCapsula['contenidoObligatorio'] : "";
									$opcion_1				= $rowCapsula['o1'] ? $rowCapsula['o1'] : "";
									$opcion_2				= $rowCapsula['o2'] ? $rowCapsula['o2'] : "";
									$opcion_3				= $rowCapsula['o3'] ? $rowCapsula['o3'] : "";
									$opcion_4				= $rowCapsula['o4'] ? $rowCapsula['o4'] : "";
									$opcion_5				= $rowCapsula['o5'] ? $rowCapsula['o5'] : "";

                                    if ($contenido == "") {
                                        $contenidoUrl = substr($contenidoUrl, 3, strlen($contenidoUrl));
                                    }
                                    switch ($elementoTipo) {
                                        case "imagen" :
                                            ?>
                                            <br/>
                                            <div align='center'>
												<img src='<? echo htmlentities($contenidoUrl); ?>' style=" border-radius :5px; max-width: 100%;box-shadow: 0 5px 10px rgba(0,0,0,.2);border: 1px solid rgba(192, 192, 192, 1); "/>
                                                <br/>
                                                <span style="font-size:12px;"><i><? echo htmlentities($contenidoDescripcion) ?></i></span>
                                            </div>
                                            <br/>
                                        <?
                                        break;
                                    case "feedback":
                                        ?>	
										<div  class='divContenidoUsuario' >
                                            <div id="Feedback_<? echo $feedbackId; ?>" class="Capsula_<? echo $capsulaid; ?>">
                                                <input type='hidden' class='FeedbackId' value='<? echo $feedbackId; ?>'/>
												
												<div data-role="collapsible-set" >
													<div data-role="collapsible" data-collapsed="true" data-theme="c">
														<!--    Titulo del Feddback   -->
														<h3>Feedback <? echo $f++; ?></h3>
														<p>Responde las siguientes preguntas</p>													
														<br/>
														<!-- Contenido del Feedback -->
										
<?  													$queryPr = "EXEC capListarAlternativasFeedback " . $capsulaid . "," . $version . "," . $clienteId . "," . $feedbackId .  "," . $usuarioId .  "";
														//$queryPr = "EXEC capVistaFeedbackAlternativas " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . "," . $feedbackId; //THIS
														//echo "queryPr  $queryPr <br/>";
														//echo '<script> console.log("'.$queryPr.'");</script>';
														$resultPr = $base_datos->sql_query($queryPr);


														//TR de las opciones 
														$TR1 = "<tr id='first'><td></td><td></td><td style='width: 520px;'>  </td>";
														
														if ($opcion_1  != '') { $num++;  $TR1 =$TR1."<td class='Feed' >".$opcion_1."</td>";}								  
														if ($opcion_2  != '') { $num++;  $TR1 =$TR1."<td class='Feed' >".$opcion_2."</td>";}									  
														if ($opcion_3  != '') { $num++;  $TR1 =$TR1."<td class='Feed' >".$opcion_3."</td>";}									  
														if ($opcion_4  != '') { $num++;  $TR1 =$TR1."<td class='Feed' >".$opcion_4."</td>";}									  
														if ($opcion_5  != '') { $num++;  $TR1 =$TR1."<td class='Feed' >".$opcion_5."</td>";}

														$TR1 = $TR1. "</tr>";

														$TR ='';
														while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
														
															$cont_F++;
															$contenidoAlternativa 	= trim($rowPr['contenidoAlternativa']);
															$pos 					= trim($rowPr['textoRespuesta']);
															$id_alternativa 		= trim($rowPr['id_alternativa']);
															//echo '<script>console.log('.$contenidoAlternativa.$id_alternativa.$pos.');</script>';
															$TR='';
															
															if ($pos != null ){ 
															
															$DISABLED= true;
															$out='disabled';
															}
															$pos1=''; 
															$pos2=''; 
															$pos3=''; 
															$pos4=''; 
															$pos5=''; 
																													
															if ($pos == '1' ) {   $pos1='checked'; } 
															if ($pos == '2' ) {   $pos2='checked'; } 
															if ($pos == '3' ) {   $pos3='checked'; } 
															if ($pos == '4' ) {   $pos4='checked'; } 
															if ($pos == '5' ) {   $pos5='checked'; } 

															
															
															
															//tr de las alternativas
															//el atrubuto compuesto data-pos='*' indica el parametro escogido (jQuery) se accede por $().data('nombre') o $().attr('data-<nombre>')
															//  $().data('pos'); o $().attr('data-pos');
															
															
															$TR = "<tr ><td></td><td>".$cont_F.")</td><td style='text-align: left; border-bottom: 1px gainsboro solid;'>".$contenidoAlternativa."</td>";
															if ($opcion_1  != '') {$TR = $TR."<td class='Feed'><input type='radio' data-pos='1' ".$out." ".$pos1."  value='".$feedbackId."' name='".$id_alternativa."'/></td>";} 
															if ($opcion_2  != '') {$TR = $TR."<td class='Feed'><input type='radio' data-pos='2' ".$out." ".$pos2."  value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                           
															if ($opcion_3  != '') {$TR = $TR."<td class='Feed'><input type='radio' data-pos='3' ".$out." ".$pos3."  value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                              
															if ($opcion_4  != '') {$TR = $TR."<td class='Feed'><input type='radio' data-pos='4' ".$out." ".$pos4."  value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                             
															if ($opcion_5  != '') {$TR = $TR."<td class='Feed'><input type='radio' data-pos='5' ".$out." ".$pos5."  value='".$feedbackId."' name='".$id_alternativa."'/></td>";}              
															$TR = $TR."</tr>";
															//echo '<script>  console.log( "'.$contenidoAlternativa.'"  );    </script>';	
															
															$TR2 = $TR2.$TR;
															$TR='';
														}
														
														
														$TABLA = "<table cellpadding='0' cellspacing='0' class='F1' style='display: table; margin: 0 auto;'><tbody>".$TR1.$TR2."</tbody></table>";	
														echo $TABLA;
																									
?>  

													  <?//}?>
													</div>
												</div>
												
											</div>
										</div>
                                        <?
                                        break;
                                        case "pregunta":
                                            ?>
                                            <div class="divContenidoUsuario">
                                                <div id="Pregunta_<? echo $preguntaId; ?>" class="Capsula_<? echo $capsulaid; ?>" >
                                                    <input type='hidden' class='PreguntaId' value='<? echo $preguntaId; ?>'/>
													
													<div data-role="collapsible-set" >
                                                        <div data-role="collapsible" data-collapsed="true" data-theme="c">
                                                            <!--    Titulo de la pregunta   -->
                                                            <h3>Pregunta <? echo $i++; ?></h3>
                                                            <p><? echo stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8", "ISO-8859-1")); ?></p>													

													
													
													
													
                                                    <br/>
                                                    
													
													<div data-role="fieldcontain">                          
														<select class="alternativas" name="Alternativa_<? echo $preguntaId; ?>" id="Alternativa_<? echo $preguntaId; ?>" data-native-menu="false"  data-icon="check" >
                                                            <?
                                                            $queryPr = "EXEC capListarRespuestasPregunta " . $capsulaid . "," . $version . "," . $clienteId . "," . $preguntaId . "," . $usuarioId . "";
                                                            //echo "queryPr  $queryPr <br/>";
                                                            $resultPr = $base_datos->sql_query($queryPr);
															echo '<option data-placeholder="true"  value="default" >Alternativas...</option>';
															
                                                            while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                                                                $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                                                                $rUsuario = trim(strtolower($rowPr['respuestaUsuario']));
                                                                $rTexto = trim($rowPr['respuestaTexto']);
                                                                $rId = trim($rowPr['respuestaId']);
                                                                $alternativa = trim($rowPr['alternativa']);
                                                                $escorrecta = 0;
                                                                $respuestausuario = "";
                                                                if ($rCorrecta == strtolower($rTexto)) {
                                                                    $escorrecta = 1;
                                                                }

                                                                if ($rUsuario == strtolower($rTexto)) {
                                                                    $respuestausuario = 1;
                                                                }
                                                                ?>
                                                                
																
                                                                    <?
                                                                    if ($rUsuario == '') {
                                                                        ?>
																		
																		<option id="<? echo $preguntaId ?>" value='<? echo $rId; ?>' > <? echo htmlentities($rTexto); ?></option>';
                                                                        
																		<?
                                                                    } else {

                                                                        $respuesta = "SI";
                                                                        ?>
																		
                                                                        <option id="<? echo $preguntaId ?>" value='<? echo $rId; ?>'  disabled="disabled"  <? 
                                                                                if($respuestausuario == 1){
                                                                                    echo 'selected="selected" data-icon="home"';
                                                                                }?> > <? 
																				
																				// para tipo 1 se muestra un mensaje con la alternativa correcta.... omite imagen
																				if ($escorrecta == 1 && $respuestausuario == 1 && $tipo == 1) {
																					echo htmlentities($rTexto.' ( Correcta ) '); 
																				}else{
																					echo htmlentities($rTexto); 
																				}	
																	  ?></option>
																				
                                                                        
                                                                <?  }?>
                                                              
                                                          <?}?>    
                                                                                                    
														</select >  
													</div>

                                                        <?
                                                    if ($tipo == 1) {
                                                            ?>
                                                            <br/>
                                                            <input class="validar" type='button'  data-inline="true" value='Validar'
                                                            <?
                                                            if ($rUsuario != '') {
                                                                echo 'disabled="disabled""';
                                                            }
                                                            ?> />
                                                            <div class="mensajeValidacion">
                                                                &nbsp;
                                                            </div>                                                        
                                                    
                                                        <?
                                                        if ($rUsuario == '') {
                                                            ?>
                                                            <div class="positivo" style="display:none;" id="Positivo<? echo $rowCapsula['preguntaId']; ?>">
                                                                <table border="0">
                                                                    <tr>
                                                                        <td valign="top" style="padding:10px;">
                                                                            <img src="skins/saam/img/ok.png"/> 
                                                                        </td>
                                                                        <td>
                                                                            <span class="mensajePositivo"></span><? //echo stripslashes(mb_convert_encoding($rowCapsula['mensajePositivo'], "UTF-8", "ISO-8859-1"));?>
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                            </div>

                                                            <div class="negativo" style="display:none;" id="Negativo<? echo $rowCapsula['preguntaId']; ?>">
                                                                <table border="0">
                                                                    <tr>
                                                                        <td valign="top" style="padding:10px;">
                                                                            <img src="skins/saam/img/no.png"/> 
                                                                        </td>
                                                                        <td>
                                                                            <span class="mensajeNegativo"></span><? //echo stripslashes(mb_convert_encoding($rowCapsula['mensajeNegativo'], "UTF-8", "ISO-8859-1"));                               ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        <? } else { ?>
                                                            <div class="positivo" style="<?
                                                            if ($rCorrecta == $rUsuario) {
                                                                echo 'display:block';
                                                            } else {
                                                                echo 'display:none';
                                                            }
                                                            ?>">
                                                                <table border="0">
                                                                    <tr>
                                                                        <td valign="top" style="padding:10px;">
                                                                            <img src="skins/saam/img/ok.png"/> 
                                                                        </td>
                                                                        <td>
                                                                             <? echo stripslashes(mb_convert_encoding($rowCapsula['mensajePositivo'], "UTF-8", "ISO-8859-1")); ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>

                                                            <div class="negativo" style="<?
                                                            if ($rCorrecta != $rUsuario) {
                                                                echo 'display:block';
                                                            } else {
                                                                echo 'display:none';
                                                            }
                                                            ?>">
                                                                <table border="0">
                                                                    <tr>
                                                                        <td valign="top" style="padding:10px;">
                                                                            <img src="skins/saam/img/no.png"/> 
                                                                        </td>
                                                                        <td>
                                                                             <? echo stripslashes(mb_convert_encoding($rowCapsula['mensajeNegativo'], "UTF-8", "ISO-8859-1")); ?> 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        <?
                                                        }
                                                    }
                                                    ?>

                                                    <br/><br/>
													
													
													
													</div>     
													</div><!--    Fin pregunta widget    -->
                                                </div>
                                            </div>

                                            <?PHP
                                            break;
                                        case "texto":
                                            $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");
                                            ?>

                                            <div class="divContenidoUsuario" data-role="content">
												<p><? echo $descripcion; // LIMPIA EL STRING DE ETIQUETAS html y php strip_tags($descripcion); ?><p>
												
                                            </div>                                        
                                            <?PHP
                                            break;

                                        case "comentario":
                                            $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");

                                            $queryComentario = "EXEC AcapSeleccionarComentarioUsuario " . $envio . "," . $capsulaid . "," . $version . "," . $contenidoId . "," . $usuarioId . "";
                                            //echo $queryComentario$usuarioId
                                            $result = $base_datos->sql_query($queryComentario);
                                            $resultRow = $base_datos->sql_fetch_assoc($result);
                                            $descripcionUsuario = $resultRow['comentarioUsuario'] ? $resultRow['comentarioUsuario'] : "";
                                            ?>

                                            <div class="divContenidoUsuario" align="center">                                                                                                

                                                <table >                            
                                                    <tr>
                                                        <td width="5px" height="18px" valign="top">
                                                            <input type='hidden' class='contenidoComentarioId' value='<? echo $contenidoId; ?>'/>
                                                            <? if ($contenidoObligatorio == 1) { ?>
                                                                <label id="lbComentarioTitulo" title="Este campo es obligatorio">*</label>
                <? } ?>
                                                        </td>
                                                        <td id="tdComentarioTitulo">
															<? echo $descripcion; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" colspan="2">  
                                                            <div class="divComentarioTexto">
                                                                <textarea cols="2" rows="10" maxlength='1000'  <?
                                                                if ($contenidoObligatorio == 1) {
                                                                    echo "id='textAreaObligatorio'";
																	echo "placeholder='Escribir aquí. ( Obligatorio )'";
																}else{ 
																	echo "placeholder='Escribir aquí.'";    
																}        
                                                                ?> ><? echo trim(htmlentities($descripcionUsuario)); ?></textarea>
																
                                                            </div>                                                                                                                                                                                        
                                                        </td>
                                                    </tr>
													
													
                                                    <tr>
                                                        <td align="right" colspan="2">
                                                            <? if ($tipo == 2) { ?> 
																												
                                                                <input class="guardarComentario" data-inline="true" type='hidden' value='Guardar' />                                                            
                                                            <? } else { ?>
                                                                <input class="guardarComentario" data-inline="true"  type='button' value='Guardar' /> 
														<? } ?>   
                                                        </td>
                                                    </tr>

                                                </table>

                                            </div>                                        
                                            <?PHP
                                            break;
                                    }
                                }

                                if ($tipo == 2) {
                                    ?>
                                    <center>
                                        <input type='button' id='Enviar_Encuesta' data-inline="true" value='Enviar Encuesta'  <?
                                        if (trim($rUsuario) != '') {
                                            echo 'disabled="disabled"';
                                        }
                                        ?> />
                                    </center>
                                    <?
                                }

                                if ($tipo == 2) {
                    //                if ($mostrarmensaje == true) {
                                        ?>

                                        <!-- <script type="text/javascript">
                                            alert("Su encuesta ha sido enviada. Gracias PHP");
                                        </script>  -->

                                        <?
                     //               }
                                    if ( $respuesta == "SI") {
                                        ?>
                                        <br/>
                                        <div align="center">
                                            <div align="center" id="Estado_encuesta" class="positivo"  id="Positivo<? echo $rowCu['preguntaId']; ?>">
                                                Muchas gracias por responder esta encuesta
                                            </div>
                                        </div>
											
                                        <script type="text/javascript">
											//desabilita comentarios cuando se completan todas las alternativas
											var elementosTexArea = $('#divCentral').find('textarea');
											elementosTexArea.each(function(i, v) {
												$(this).attr('readonly','readonly');
											});
										</script>

                                        <?PHP
                                    }
                                }
                                ?>

								
								
							</div>
						</div><!--    Fin Capsula  jQueryMobile    -->
                            
<? } ?>
                    </div>
                </center>
            </div>                                                            
        </form>
    </body>
	
	
<?if ($DISABLED == false) {   echo $DISABLED; ?>
<script>
$(document).ready(function(){

	//aplia el area area de feedback
	$('.Feed').click(function(){

		$(this).find('input').prop('checked', true);

	});

});
</script>		
<? 	}?>	
	
	
</html>