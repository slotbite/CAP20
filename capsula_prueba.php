<?
include ("librerias/conexion.php");
include ("librerias/crypt.php");
session_start();

$mostrarmensaje = false;

$encrypted = urldecode($_GET['hash']) ? urldecode($_GET['hash']) : urldecode($_POST['hash']);
$iv = "brains12";

$i4 = enc_dec($encrypted);


$arreglo1 = explode("&", $i4);
if (count($arreglo1) == 4) {
    $capsulaid1 = explode("=", $arreglo1[0]);
    $capsulaid = $capsulaid1[1];
    $_SESSION["cap_capsulaid"] = $capsulaid;

    $version1 = explode("=", $arreglo1[1]);
    $version = $version1[1];
    $_SESSION["cap_version"] = $version;

    $envio1 = explode("=", $arreglo1[2]);
    $envio = $envio1[1];
    $_SESSION["cap_envio"] = $envio;

    $tipo1 = explode("=", $arreglo1[3]);
    $tipo = $tipo1[1];
    $_SESSION["cap_tipo"] = $tipo;


    //echo "capsulaid($capsulaid)) && (version($version)) && (version($usuarioId)) && (envio($envio)";
    // valida parametros de ingreso
    if ((is_numeric($capsulaid)) && (is_numeric($version)) && (is_numeric($envio))) {


        $logo = substr($logo, 3, strlen($logo));


        $queryTipo = "EXEC capVerCapsulaPrueba " . $envio . "," . $capsulaid . "," . $version . ", " . $tipo;
        //echo $queryTipo;
        $result = $base_datos->sql_query($queryTipo);
        $row = $base_datos->sql_fetch_assoc($result);
        $Capnombre = $row["capsulaNombre"];
        $_session["cap_tipo"] = $tipo;
        $encabezado = $row["temaImagen"];
        $encabezado = substr($encabezado, 1, strlen($encabezado));

        //$comentario = $row["capsulaComentario"];
        $comentario = "";
        $estadoCapsula = $row["capsulaEstado"];
        //echo "envio:".$envio;
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
		
		
        <!-- <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script>
        <script type="text/javascript" src="scripts/Observer.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.Request.js"></script> -->
 		
		 <script type="text/javascript" src="scripts/Funciones.js"></script>
			    
		<link rel="stylesheet" type="text/css" href="skins/saam/Autocompleter.css" />
		

        <style>

            body{
                margin:1px;
                width: 100%;
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




        <?PHP if ($tipo == 1) { ?>
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
		console.log(AlternativasJson);
		// Envio de datos
		$.ajax({
				url: 'http://192.168.74.106/CAP20/validar_respuestasPrueba.php', 		// LOCALHOST
				//url: 'http://186.67.146.11/CAP20/validar_respuestasPrueba.php',		//BRAINS PRUEBAS
				dataType: 'jsonp',
				type: "GET",
				jsonp: 'jsoncallback',
				crossDomain: true,
				//data : { json : [{"preguntaid":151,"alternativa":427,"respuesta":"false"},{"preguntaid":151,"alternativa":428,"respuesta":"true"}] } , //tipo string
				data : { 	json 			:    AlternativasJson 		  ,
							cap_envio		:	<? echo $envio;  		?>,
							cap_capsulaid	:	<? echo $capsulaid; 	?>,														
							cap_version		:	<? echo $version; 		?>
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
        <?PHP } ?>
        <?PHP if ($tipo == 2) { ?>
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
		
//ALERTS-----------------------------------------------------------------------------------------------
		
		if ( validar == false && validar2 == false) {
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
			
		}else{		

			//desabilita comentarios
			var elementosTexArea = $('#divCentral').find('textarea') ;
			console.log(elementosTexArea);
			elementosTexArea.each(function(i, v) {
				//var $TexArea = $(this);
					$(this).attr('readonly','readonly');
					$(this).css("background-color",'#d2d2d2');
			});	
			//desabilita todas las alternativas
			var Alternativas = $('#divCentral').find('option') ;
			Alternativas.each(function(i, v) {
				$(this).attr('disabled','disabled')
				$(this).parent().selectmenu('refresh', true); //recarga el widguet 
			});
			// desabilita y recarga el boton
			boton.prop('disabled', true);
			boton.button("refresh");

			//OK
			alert("Su encuesta ha sido enviada. Gracias");
		}
});

});
});
		
		</script>
		
<script>
$(document).ready(function(){


//aplia el area area de feedback
$('.Feed').click(function(){

	$(this).find('input').prop('checked', true);
	
});

});
</script>		
		
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
		
        <?PHP } ?>
    </head>
    <body>
        <form id="evaluacion" method="POST" action="capsula_prueba.php">
            <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

            <div id="divCentral">

                <center>

                    <div style="background: #FFFFFF; padding:10px; margin-bottom: 20px">

                        <table class="tablaTema" CELLSPACING='0' CELLPADDING='0' style=" max-width: 100%; ">
                            <tr>                                
                                <? if (trim($encabezado) == "") { ?>
									<td id="tdCabeceraTema1"  style="border:1px solid #4B6C9F;">
                                             <img id="temaUrl" style="width:100%;" src="/CAP20/mantenedores/encabezados/1_9.jpg" border="0"> 
                                        </td>
                                    <?
                                } else {
                                    ?>
                                    <td id="tdCabeceraTema2"  style="border:1px solid #4B6C9F;">
                                        <img id="temaUrl" style="width:100%;" src='<? echo htmlentities($encabezado); ?>' border='0'>
                                    </td>                                
                                <? } ?>                                                                                                                                                                                                                                
                                <td style="border:1px solid #4B6C9F; align='center'">
                                    <img src='css/imagenes/headerFooter/logo.png' border='0' width="100%">
                                </td>
                            </tr>
                            <tr>
								<td id='FX'  style="padding-left: 25px" colspan="2" align="center" align="center" >
									<h3 >Bienvenido(a) </h3>
									<h3 >Nombre de usuario</h3 >
									
                                    <input type='hidden' id='envioId' name='envioId' value='<? echo $envio; ?>'/>
                                    <input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid; ?>'/>
                                    <input type='hidden' id='versionId' name='versionId' value='<? echo $version; ?>'/>

                                </td>
                            </tr>
                            <tr>
                                <td id="tdTituloCapsula" align="center" colspan="2"  style=" word-wrap: break-word ">
                                    <? if ($tipo != 3) { ?>
                                        <h3><? echo htmlentities(stripslashes($Capnombre)); ?></h3>
                                    <? } ?>
                                </td>
                            </tr>
                        </table>

                        <br/>

                       
						
						
						<!--    Capsula   .... jQueryMobile    -->
						<div data-role="collapsible-set"  data-theme="b" data-content-theme="b">
							<div data-role="collapsible" data-collapsed="false">
							

                                <!--    Título de la capsula    -->
								<? // comprueba que la capsula tenga un nombre 
								   if( htmlentities(stripslashes($Capnombre)) != ''){ ?>

                                          <?  if($tipo == 1){ ?>

                                                <h2>Cuestionario 

                                           <? }elseif ($tipo == 2 ) { ?>

                                                <h2>Encuesta  

                                           <? }elseif ($tipo == 3 ) { ?>

                                                <h2> Contenido 

                                        <? } echo htmlentities(stripslashes($Capnombre));?>   </h2>
										
								<? }else { ?>	
								
										<h2>C&aacute;psulas de conocimiento</h2>
								
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
                                        <div  align='center' data-role="content">
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
										
<?  

														$queryPr = "EXEC capListarAlternativasFeedbackPrueba " . $capsulaid . "," . $version . "," .  $envio . "," .  $feedbackId . "," . $tipo . "";
														//$queryPr = "EXEC capListarAlternativasFeedbackPrueba " . $capsulaid . "," . $version . "," . $envio . "," . $feedbackId . ",2";
														//echo "queryPr  $queryPr <br/>";
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
															$alternativaEscogida 	= trim($rowPr['alternativaEscogida']);
															$id_alternativa 		= trim($rowPr['id_alternativa']);
															$TR='';
															//tr de las alternativas
															$TR = "<tr ><td></td><td>".$cont_F.")</td><td style='text-align: left; border-bottom: 1px gainsboro solid;'>".$contenidoAlternativa."</td>";
															if ($opcion_1  != '') {$TR = $TR."<td class='Feed'><input type='radio' disabled value='".$feedbackId."' name='".$id_alternativa."'/></td>";} 
															if ($opcion_2  != '') {$TR = $TR."<td class='Feed'><input type='radio' disabled value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                           
															if ($opcion_3  != '') {$TR = $TR."<td class='Feed'><input type='radio' disabled value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                              
															if ($opcion_4  != '') {$TR = $TR."<td class='Feed'><input type='radio' disabled value='".$feedbackId."' name='".$id_alternativa."'/></td>";}                             
															if ($opcion_5  != '') {$TR = $TR."<td class='Feed'><input type='radio' disabled value='".$feedbackId."' name='".$id_alternativa."'/></td>";}              
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
                                            <div id="Pregunta_<? echo $preguntaId; ?>" class="Capsula_<? echo $capsulaid; ?>">
                                                <input type='hidden' class='PreguntaId' value='<? echo $preguntaId; ?>'/>
										<div data-role="collapsible-set" >
											<div data-role="collapsible" data-collapsed="true" data-theme="c">
												<!--    Titulo de la pregunta   -->
												<h3>Pregunta <? echo $i++; ?></h3>
												<p><? echo  stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8", "ISO-8859-1")); ?></p>													

                                                <br/>
												<div data-role="fieldcontain">                          
													<select class="alternativas" name="Alternativa_<? echo $preguntaId; ?>" id="Alternativa_<? echo $preguntaId; ?>" data-native-menu="false"  data-icon="check" >
                                                            
                                                     <? $queryPr = "EXEC capListarRespuestasPreguntaPrueba " . $capsulaid . "," . $version . "," . $envio . "," . $preguntaId . "," . $tipo . "";
                                                        //$queryPr = "EXEC capListarRespuestasPreguntaPrueba  200, 1,64,451, 1 ";
														//echo "queryPr  $queryPr <br/>";
                                                        $resultPr = $base_datos->sql_query($queryPr);
														
														echo '<option data-placeholder="true"  value="default" >Alternativas...</option>';
                                                        while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                                                            $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                                                            $rUsuario = trim(strtolower($rowPr['respuestaUsuario']));
                                                            $rTexto = trim($rowPr['respuestaTexto']);
                                                            $alternativa = trim($rowPr['alternativa']);
                                                            $rId = trim($rowPr['respuestaId']);
                                                            $escorrecta = 0;
                                                            $respuestausuario = "";
                                                            if ($rCorrecta == strtolower($rTexto)) {
                                                                $escorrecta = 1;
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
                                                                                    echo 'selected="selected" ';
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
                                                                        <span class="mensajePositivo"></span><? //echo stripslashes(mb_convert_encoding($rowCapsula['mensajePositivo'], "UTF-8", "ISO-8859-1"));                               ?>
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
                                                    <? } else {
                                                        ?>
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
                                            <p><? echo $descripcion; ?><p>
                                        </div>                                            
                                        <?PHP
                                        break;

                                    case "comentario":
                                        $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");

//                                            if($contenidoObligatorio == 1){
//                                                
//                                            }
                                        ?>                                           
                                        <div class="divContenidoUsuario">                                                                                                

                                            <table style=" margin: 0 auto; margin-top: 10px" >                            
                                                <tr>
                                                    <td width="5px" height="18px" valign="top">
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
                                                            <textarea maxlength='1000' <?
                                                                if ($contenidoObligatorio == 1) {
                                                                    echo "id='textAreaObligatorio'";
																	echo "placeholder='Escribir aquí. ( Obligatorio )'";
																}else{ 
																	echo "placeholder='Escribir aquí.'";    
																}  
                                                            ?> ></textarea>
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


                            if ($mostrarmensaje == true) {
                                ?>
                                <br/>
                                <div align="center">
                                    <div align="center" class="positivo" style="" id="Positivo<? echo $rowCu['preguntaId']; ?>">
                                        Muchas gracias por responder esta encuesta
                                    </div>
                                </div>
                                <?PHP
                            }
                            ?>

							</div>
						</div><!--    Fin Capsula  jQueryMobile    -->
					</div>
                </center>

            </div>                                       
        </form>
    </body>
</html>