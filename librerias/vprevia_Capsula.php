<?
include("../default.php");

session_start();
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;


$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$administradorId = $_SESSION['administradorId'] ? $_SESSION['administradorId'] : '0';
$perfilId = $_SESSION['perfilId'] ? $_SESSION['perfilId'] : '0';


$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$capsulaId = $_GET['capsulaId'] ? $_GET['capsulaId'] : 0;
$capsulaVersion = $_GET['version'] ? $_GET['version'] : 0;


if ($capsulaId != '' && $capsulaVersion != '') {

    // valida parametros de ingreso
    if (is_numeric($capsulaId) && is_numeric($capsulaVersion)) {


        $logo = substr($logo, 3, strlen($logo));


        $query = "EXEC capVerCapsula " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . " ";
        //echo $query;
        $result = $base_datos->sql_query($query);
        $row = $base_datos->sql_fetch_assoc($result);
        $Capnombre = $row["capsulaNombre"];
        $tipo = $row["capsulaTipo"];

        //echo $tipo;

        $encabezado = $row["temaImagen"];


        //$comentario = $row["capsulaComentario"];
        $comentario = "";
        $estadoCapsula = $row["capsulaEstado"];
        //echo "envio:".$envio;
    }
}





if ($capsulaId != 0 && $capsulaVersion != 0) {
    ?>

    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT"/>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache"/>
            <meta http-equiv="expires" content="0"/>
            <title>C&aacute;psulas de Conocimiento</title>




       <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            
			<script src="../scripts/jquery-mobile/jquery-1.8.3.min.js"></script>
            <script src="../scripts/jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
            <link  href="../scripts/jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet">  
			
			
<!--    <link rel="stylesheet"  href="http://demos.jquerymobile.com/1.4.5/css/themes/default/jquery.mobile-1.4.5.min.css" />  
        <script type="text/javascript" src="http://demos.jquerymobile.com/1.4.5/js/jquery.js"></script>
        <script type="text/javascript" src="http://demos.jquerymobile.com/1.4.5/js/jquery.mobile-1.4.5.min.js"></script>  -->
			
<!-- 		<script src="../scripts/jquery-mobile/jquery.mobile-1.4.5.js"></script>
            <script src="../scripts/jquery-mobile/jquery.mobile-1.4.5.min.js"></script>
            <link  href="../scripts/jquery-mobile/jquery.mobile-1.4.5.min.css" rel="stylesheet"> -->



            <link rel="stylesheet" type="text/css" href="../css/capsula.css" media="screen" />
            <script type="text/javascript" src="../scripts/jwplayer.js"></script> 
           <!--  <script type="text/javascript" src="../scripts/mootools-1.2.5-core.js"></script>
            <script type="text/javascript" src="../scripts/Observer.js"></script>
            <script type="text/javascript" src="../scripts/Autocompleter.js"></script>
            <script type="text/javascript" src="../scripts/Autocompleter.Request.js"></script> -->
			<script type="text/javascript" src="../scripts/Widgets.js"></script>  		
			
            <script type="text/javascript" src="../scripts/Funciones.js"></script>				    
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
                    width:99%; 
                    margin: 0px auto;                    
                    border: 1px solid #CCC;                                 
                    color: #5A5655; 
                    -webkit-border-radius: 5px;                                                    
                }
				.ui-popup-container {
					width: 100%;
				}  
            </style>
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
        </head>
        <body>
            <form id="evaluacion" method="POST" action="capsula_prueba.php">
                <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

                <div id="divCentral">

                    <center>

                        <div style="background: #FFFFFF;  padding:10px; margin-bottom: 20px;">
                            <!--    TABLA DE PRECENTACION    -->
                            <table class="tablaTema" CELLSPACING='0' CELLPADDING='0'  style=" max-width: 100%; ">
                                <tr>                                
                                    <? if (trim($encabezado) == "") { ?>
									
                                        <td id="tdCabeceraTema1"  style="border:1px solid #4B6C9F;">
                                             <img id="temaUrl" style="width:100%;" src="/CAP20/mantenedores/encabezados/1_9.jpg" border="0"> 
                                        </td>    
                                        <!--<td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                            <span style='font-weight:bolder;font-size:20px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                        </td>
										-->
                                        <?
                                    } else {
                                        ?>
                                        <td id="tdCabeceraTema2" style="border:1px solid #4B6C9F;">
                                            <img id="temaUrl" src='<? echo "../" . htmlentities($encabezado); ?>' border='0' style="width:100%;">
                                        </td>                                
                                    <? } ?>                                                                                                                                                                                                                                
                                    <td style="border:1px solid #4B6C9F; align='center'">
                                        <img src='../css/imagenes/headerFooter/logo.png' border='0' width="100%" >
                                    </td>
                                </tr>
                                <tr>
                                    <td id='FX'  colspan="2" align="center" align="center" >
                                        <h3 >Bienvenido(a)</h3>
                                        <h3 >Nombre de usuario</h3>
										<br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdTituloCapsula" align="center" colspan="2" style=" word-wrap: break-word ">

                                          <?  if($tipo == 1){ ?>

                                                <h1>Cuestionario <? echo htmlentities(stripslashes($Capnombre)); ?></h1>

                                           <? }elseif ($tipo == 2 ) { ?>

                                                <h1>Encuesta <? echo htmlentities(stripslashes($Capnombre)); ?></h1>

                                           <? }elseif ($tipo == 3 ) { ?>

                                                <h1> Contenido <? echo htmlentities(stripslashes($Capnombre)); ?></h1

                                        <? } ?>
                                    </td>
                                </tr>
                            </table>
                            <br/>

                            <br/>

							
							
							
                           		
                            <!--    Capsula   .... jQueryMobile    -->
                            <div data-role="collapsible-set"  data-theme="b" data-content-theme="b">

                                <div data-role="collapsible" data-collapsed="false">
                                

                                <!--    Titulo de la capsula    -->
								
								
								
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
                                $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaId . "," . $capsulaVersion . "";
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
                                            <div align='center' style=' weight:auto;  '>
                                                <img src='<? echo "../" . htmlentities($contenidoUrl); ?>' style=" border-radius :5px; max-width: 100%; box-shadow: 0 5px 10px rgba(0,0,0,.2); " />
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
														$queryPr = "EXEC capVistaFeedbackAlternativas " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . "," . $feedbackId; //THIS
														echo "queryPr  $queryPr <br/>";
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
                                            <div class="divContenidoUsuario"  >
                                                <div id="Pregunta_<? echo $preguntaId; ?>" class="Capsula_<? echo $capsulaid; ?>">
                                                    <input type='hidden' class='PreguntaId' value='<? echo $preguntaId; ?>'/>
                                                    
                                                    <div data-role="collapsible-set" >
                                                        <div data-role="collapsible" data-collapsed="true" data-theme="c">
                                                            <!--    Titulo de la pregunta   -->
                                                            <h3>Pregunta <? echo $i++; ?></h3>
                                                            <p><? echo stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8", "ISO-8859-1")); ?></p>

                                                   
                                                        
                                                            <br/>


															<div class="alternativas" id="Alternativas">
															
                                                            <div data-role="fieldcontain"  >                          
                                                                <select name="Alternativa_<? echo $preguntaId; ?>" id="Alternativa_<? echo $preguntaId; ?>"data-native-menu="false" data-icon="check" data-inline="false"  >  

                                                                
                                                                <?  $queryPr = "EXEC capVistaPreguntaRespuestas " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . "," . $preguntaId;
                                                                    //echo "queryPr  $queryPr <br/>";
                                                                    $resultPr = $base_datos->sql_query($queryPr);
                                                                    while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                                                                        $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                                                                        $rUsuario = trim(strtolower($rowPr['respuestaUsuario']));

                                                                        // seleccion de radioimputs para la alternativa correcta // no se muestra corectamente
                                                                        // if (strtoupper($rCorrecta) == "SI" && $tipo == 1) {
                                                                            // echo  '<input type="radio" name="Respuesta_'.$preguntaId.'" id="radio-choice-1" value="choice-1" checked="checked"  />';
                                                                        // }else{
                                                                            // echo  '<input type="radio" name="Respuesta_'.$preguntaId.'" id="radio-choice-1" value="choice-1" />';
                                                                        // }
                                                                        // echo  '<label for="Respuesta_'.$preguntaId.'"> '.htmlentities($rowPr['respuestaTexto']) .' </label>';
                                                                         
                                                                        //seleccion de botones para la alternativa correcta 
                                                                        
                                                                        //echo  '<input type="radio" name="Respuesta_'.$preguntaId.'" id="radio-choice-1" value="choice-1" />';
                                                                        //echo  '<label for="Respuesta_'.$preguntaId.'"> '.htmlentities($rowPr['respuestaTexto']) .' </label>';

                                                                        // si es correcta y es encuesta...
                                                                        ?>
                    
                                                                        <option value="Respuesta_<? echo $preguntaId ?>"  disabled="disabled"  <? 
                                                                                if(strtoupper($rCorrecta) == "SI"){
                                                                                    echo 'selected="selected"';
                                                                                }elseif(strtoupper($rCorrecta) == "SI" && $tipo == 1){
                                                                                    echo 'selected="selected"';

                                                                                }?> > <? echo htmlentities($rowPr['respuestaTexto']); ?></option>';
                                                                            
                                                                     <?} ?>




                                                                </select >  
                                                            </div>



																	<?  if ($tipo == 1) { ?>
																		
																	
																	<input class="validar" type='button' data-inline="true"  id='Validar<? echo $preguntaId ?>' value='Validar'
																	<?
																	if ($rUsuario != '') {
																		echo 'disabled="disabled"';
																	}
																	?> />
																	<div class="mensajeValidacion">
																		&nbsp;
																	</div>
															</div>
																<?  }?>  
														

														

														<? if ($tipo == 1) { ?>
															<div class="positivo">

																<table border="0">
																	<tr>
																		<td valign="top" style="padding:10px;">
																			<img src="../skins/saam/img/ok.png"/> 
																		</td>
																		<td style="font-size:12px">
																			<? echo stripslashes(mb_convert_encoding($rowCapsula['mensajePositivo'], "UTF-8", "ISO-8859-1")); ?>
																		</td>
																	</tr>
																</table>
															</div>
															<br>

															<div class="negativo">
																<table border="0">
																	<tr>
																		<td valign="top" style="padding:10px;">
																			<img src="../skins/saam/img/no.png"/> 
																		</td>
																		<td style="font-size:12px">
																			<? echo stripslashes(mb_convert_encoding($rowCapsula['mensajeNegativo'], "UTF-8", "ISO-8859-1")); ?>
																		</td>
																	</tr>
																</table>
															</div>    

															<? } ?>

															
															
															
														</div>
													</div>
                                                </div>
                                            </div>                                    

                                            <?PHP
                                            break;
                                        case "texto":
                                            $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");
                                            ?>                                           

                                            <div class="divContenidoUsuario">

                                                <p><? echo strip_tags($descripcion); // LIMPIA EL STRING DE ETIQUETAS html y php ?><p>
												
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

                                                <table style=" margin: 0 auto; margin-top: 10px">                            
                                                    <tr>
                                                        <td width="5px" height="18px" valign="top">
                                                            <? if ($contenidoObligatorio == 1) { ?>
                                                                <label id="lbComentarioTitulo" title="Este campo es obligatorio">*</label>
                                                            <? } ?>
                                                        </td>
                                                        <td id="tdComentarioTitulo" style="font-size:12px;">
                                                            <? echo $descripcion; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" colspan="2">  
                                                            <div class="divComentarioTexto">
                                                                <textarea  cols="2" rows="10" maxlength='1000' placeholder="Escribir aquí " ></textarea>                                                        
                                                            </div>                                                                                                                                                                                        
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="2">
                                                            <input class="validar" type='button' value='Guardar' data-inline="true" data-mini="true"   /> 
                                                            
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>                                            
                                            <?PHP
                                            break;
                                    } // end switch
                                } // end while ?>

                                <? if ($tipo == 2) {
                                    ?>
                                    <center>
                                        <input type='button' id='Validar' value='Enviar Encuesta' data-inline="true"   <?
                                               if (trim($rUsuario) != '') {
                                                   echo 'disabled="disabled"';
                                               }
                                               ?> />
                                    </center>
                                    <?}  ?>


								</div>
							</div><!--    Fin pregunta widget    -->
							      
								     
									 
							<br/>

							<p align="center">
									<a href="#"  data-role="button" data-theme="b" data-inline="true"   onclick="parent.searchBoxPpal.close();" >Cerrar</a>
							</p> 
					
						</div>
					</center>	
				</div>
            </form>
        </body>
    </html>





    <?
}
?>