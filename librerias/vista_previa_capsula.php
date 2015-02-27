<?
include ("../librerias/conexion.php");
include ("../librerias/crypt.php");
session_start();
?>
<?
$mostrarmensaje = false;

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId'] : $_GET['capsulaId'];
$capsulaVersion = $_POST['capsulaVersion'] ? $_POST['capsulaVersion'] : $_GET['capsulaVersion'];

$i4 = enc_dec($encrypted);


$arreglo1 = explode("&", $i4);




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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="expires" content="0">




                    <title>C&aacute;psulas de Conocimiento</title>

                    <script type="text/javascript" src="scripts/jwplayer.js"></script> 
                    <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script>
                    <script type="text/javascript" src="scripts/Observer.js"></script>
                    <script type="text/javascript" src="scripts/Autocompleter.js"></script>
                    <script type="text/javascript" src="scripts/Autocompleter.Request.js"></script>
                    <script type="text/javascript" src="scripts/Funciones.js"></script>					

                    <style>
                        ul {
                            list-style-type: disc;
                            list-style-position:inside;
                        }
                        ul li
                        {
                            list-style-type: disc;
                            list-style-position:inside;
                        }
                        p{font-size:14px;}
                    </style>

                    <?PHP if ($tipo == 1) { ?>
                        <script type="text/javascript">
        			
                            window.addEvent('domready', function () {
        				
        				
                                if ($$(".validar"))
                                    $$(".validar").addEvent('click', function (el){
                                        var boton = this;
                                        var elemento = this.getParent().getParent();
                                        var viewData = [];
                                        var respuestas = "";
                                        var columnName = "";
        						
                                        var alternativas = this.getParent().getElements("input[class=respuesta]");
        						
                                        var pregunta = this.getParent().getParent().getElement("input[class=PreguntaId]");
                                        preguntavalor = pregunta.get("value");
        						
                                        alternativas.each(function (el, i) {							
                                            viewData.push({preguntaid: preguntavalor, alternativa: el.get("value"), respuesta: el.get("checked")});
                                        });
        						
                                    var checkeado = false;
                                    elemento.getElements("input[type=radio]").each(function (el) {
                                        if (el.get("checked") == true)
                                            checkeado = true;
                                    });
        						
                                    if (checkeado == false) {
                                        alert("Debe seleecionar una alternativa");
                                        return false;
                                    }
        					
                                    var request = new Request.JSON({
                                        url: 'validar_respuestas.php',
                                        onComplete: function(jsonObj) {
                                            elemento.getElements("input[type=radio]").set('disabled', true);
        								
                                            if (jsonObj.tipo == "positivo") {
                                                elemento.getElements("span[class=mensajePositivo]").set('html', jsonObj.mensaje);
                                                elemento.getElements("span[class=mensajePositivo]").set('html', elemento.getElements("span[class=mensajePositivo]").get('text'));
                                                elemento.getElements("div[class=positivo]").set('style', "");
                                            }
                                            else {
                                                elemento.getElements("span[class=mensajeNegativo]").set('html', jsonObj.mensaje);
                                                elemento.getElements("div[class=negativo]").set('style', "");
                                                elemento.getElements("span[class=mensajeNegativo]").set('html', elemento.getElements("span[class=mensajeNegativo]").get('text'));
                                            }
                                            boton.set('disabled', true);
                                        },
                                        data: { // our demo runner and jsfiddle will return this exact data as a JSON string
                                            json: viewData
                                        }
                                    }).send();
        					
                                });
        					
                            });
        			
                            function contarCaracteres(){
                                var numero = 0;
                                numero = $('Comentario').value.length;
                                var numfinal = 1000 - numero;
                                $('numeroCarac').set('text','Ud tiene disponibles '+numfinal+' caracteres.');
                            }
        				
                            function alpha(e) {
                                var k;
                                document.all ? k = e.keyCode : k = e.which;
                                return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || k == 44 || k == 46 || (k >= 48 && k <= 57));
                            }
                        </script>
                    <?PHP } ?>
                    <?PHP if ($tipo == 2) { ?>
                        <script type="text/javascript">
        			
                            window.addEvent('domready', function(){
                                var cerrada=$('cerrada').value;
                                if(cerrada==1){
                                    elementos = $$("input");
                                    for (i=0; i<elementos.length; i++){
                                        elementos[i].disabled=true;
                                    }

                                    if( $('Comentario')!=null){
                                        $('Comentario').disabled=true;
                                    }
                                }
        				
                                $("Validar").addEvent('click', function (){
                                    var validar = true;		// valida si todas las preguntas fueron respondidas
                                    var checkeado = false;	// valida si se checkeo alguna de las opciones de cada pregunta
                                    $$(".alternativas").getElements("input[type=radio]").each(function (el, index) {
                                        checkeado = false;
                                        el.each(function (elradio, indexradio) {
                                            if (elradio.get("checked") == true) {
                                                checkeado = true;
                                            }
        							
                                        });
        						
                                        if (checkeado == false) {
                                            validar = false;
                                        }
                                    });
                                    if (validar == false) {
                                        alert("Debe responder todas las preguntas");
                                        return false;
                                    }
                                    else {
                                        //alert("submit");
                                        $("evaluacion").submit();
                                    }
        					
                                });
        				
                            })
        																		
        			
                        </script>
                    <?PHP } ?>
                    </head>
                    <body style="background-color:#BFD1E5">
                        <form id="evaluacion" method="POST" action="capsula.php">
                            <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />
                            <table border="0" width="100%" style="background-color:#FFFFFF">
                                <tr>
                                    <td>
                                        <table border="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    <table border="0" style='border:1px solid #4B6C9F;margin:0px;border-collapse:collapse;' CELLSPACING='0' CELLSPADDING='0'>
                                                        <tr>
                                                            <? if (trim($encabezado) == "") { ?>
                                                                <td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:700px;border-bottom:1px solid #4B6C9F;text-align:center;height:150px;' height='75px'>
                                                                    <span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                                                </td>
                                                            <? } else { ?>
                                                                <td style="width:700px;height:150px;border:1px solid #4B6C9F;"><img src="<? echo '/CAP20' . $encabezado;   ?>" style="width:700px;"/></td>
                                                            <? } ?>    
                                                            <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                                                <img src='../<? echo $logo; ?>' border='0' height='90%'>
                                                            </td>
                                                        </tr>			
                                                    </table>


                                                    <br/>

                                                    <div id='FX'  style="text-align:justify;word-wrap: break-word">
                                                        <h3>Bienvenido(a)</h3>

                                                        <h3><? echo htmlentities($nombre); ?></h3></br>

                                                        <input type='hidden' id='envioId' name='envioId' value='<? echo $envio; ?>'/>
                                                        <input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaId; ?>'/>
                                                        <input type='hidden' id='versionId' name='versionId' value='<? echo $capsulaVersion; ?>'/>
                                                        <input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuario; ?>'/>
                                                        <input type="hidden" id='cerrada' value='<? echo $cerrada; ?>'/>

                                                        <br/>
                                                        <br/> 
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <? if ($tipo != 3) { ?>
                                                        <h1 class="nombreCapsula"><? echo htmlentities(stripslashes($Capnombre)); ?></h1>
                                                        <hr>
                                                        <? } ?>
                                                </td>
                                            </tr>

                                            <!-- contenido de la capsula -->
                                            <tr>
                                                <td>
                                                    <?PHP
                                                    $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaId . "," . $capsulaVersion . "";
//echo "queryCapsula $queryCapsula <br/>";
                                                    $resultCapsula = $base_datos->sql_query($queryCapsula);
                                                    while ($rowCapsula = $base_datos->sql_fetch_assoc($resultCapsula)) {
                                                        $capsulaId = $rowCapsula['capsulaId'] ? $rowCapsula['capsulaId'] : 0;
                                                        $capsulaVersion = $rowCapsula['capsulaVersion'] ? $rowCapsula['capsulaVersion'] : 0;
                                                        $elementoTipo = $rowCapsula['elementoTipo'] ? $rowCapsula['elementoTipo'] : "";
                                                        $contenidoId = $rowCapsula['contenidoId'] ? $rowCapsula['contenidoId'] : 0;
                                                        $preguntaId = $rowCapsula['preguntaId'] ? $rowCapsula['preguntaId'] : 0;
                                                        $orden = $rowCapsula['orden'] ? $rowCapsula['orden'] : 0;
                                                        $contenidoDescripcion = $rowCapsula['contenidoDescripcion'] ? $rowCapsula['contenidoDescripcion'] : "";
                                                        $contenidoUrl = $rowCapsula['contenidoUrl'] ? $rowCapsula['contenidoUrl'] : "";
                                                        $preguntaTexto = $rowCapsula['preguntaTexto'] ? $rowCapsula['preguntaTexto'] : "";
                                                        $mensajePositivo = $rowCapsula['mensajePositivo'] ? $rowCapsula['mensajePositivo'] : "";
                                                        $mensajeNegativo = $rowCapsula['mensajeNegativo'] ? $rowCapsula['mensajeNegativo'] : "";
                                                        $capsulaTipo = $rowCapsula['capsulaTipo'] ? $rowCapsula['capsulaTipo'] : "";

                                                        if ($contenido == "") {
                                                            $contenidoUrl = substr($contenidoUrl, 3, strlen($contenidoUrl));
                                                        }
                                                        switch ($elementoTipo) {
                                                            case "imagen" :
                                                                ?>
                                                                <div align='center'>
                                                                    <img src='../<? echo htmlentities($contenidoUrl); ?>' />
                                                                    <br/>
                                                                    <span style="font-size:12px;"><i><? echo htmlentities($preguntaTexto) ?></i></span>
                                                                </div>
                                                                <p>&nbsp;</br>
                                                                    <?
                                                                    break;

                                                                case "pregunta":
                                                                    ?>
                                                                    <div align="center">
                                                                        <div id="Pregunta_<? echo $preguntaId; ?>" class="Capsula_<? echo $capsulaId; ?>">
                                                                            <input type='hidden' class='PreguntaId' value='<? echo $preguntaId; ?>'>
                                                                                <div class="pregunta">
                                                                                    <b>Pregunta <? echo $i; ?>:</b>
                                                                                    <br/>
                                                                                    <? echo stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8", "ISO-8859-1")); ?>
                                                                                </div>
                                                                                <div class="alternativas">
                                                                                    <?
                                                                                    $queryPr = "EXEC capVistaPreguntaRespuestas " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . "," . $preguntaId;
                                                                                    //echo "queryPr  $queryPr <br/>";
                                                                                    $resultPr = $base_datos->sql_query($queryPr);
                                                                                    while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                                                                                        $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                                                                                        $rUsuario = trim(strtolower($rowPr['respuestaUsuario']));
                                                                                        $rTexto = trim($rowPr['respuestaTexto']);
                                                                                        $rId = trim($rowPr['respuestaId']);
                                                                                        $escorrecta = 0;
                                                                                        $respuestausuario = "";
                                                                                        if ($rCorrecta == strtolower($rTexto)) {
                                                                                            $escorrecta = 1;
                                                                                        }

                                                                                        if ($rUsuario == strtolower($rTexto)) {
                                                                                            $respuestausuario = 1;
                                                                                        }
                                                                                        ?>
                                                                                        <? if ($rUsuario == '') { ?>
                                                                                            <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>' value='<? echo $rId; ?>'>
                                                                                                <? echo htmlentities($rTexto); ?>
                                                                                                <img src="../skins/saam/img/ok_small.png" style="visibility:hidden;"/>
                                                                                                </br>
                                                                                                <?
                                                                                            } else {
                                                                                                ?>
                                                                                                <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>'  DISABLED  <?
                                                                            if ($respuestausuario == 1) {
                                                                                echo 'CHECKED';
                                                                            }
                                                                                                ?> ><? echo htmlentities($rowPr['respuestaTexto']); ?>

                                                                                                    <?PHP if ($escorrecta == 1 && $respuestausuario == 1) { ?>

                                                                                                        <img src="../skins/saam/img/ok_small.png" style="visibility:visible;"/>
                                                                                                    <? } ?>
                                                                                                    </br>
                                                                                                <? } ?>																			
                                                                                                <?
                                                                                            }
                                                                                            if ($tipo == 1) {
                                                                                                ?>
                                                                                                <input class="
                                                                                                       " type='button' id='Validar<? echo $preguntaId ?>' value='Validar' style='width:150px;text-align:center;' 
                                                                                                       <?
                                                                                                       if ($rUsuario != '') {
                                                                                                           echo 'disabled=disabled';
                                                                                                       }
                                                                                                       ?> />
                                                                                                <div class="mensajeValidacion">
                                                                                                    &nbsp;
                                                                                                </div>
                                                                                                </div>
                                                                                                <?
                                                                                            }
                                                                                            ?>
                                                                                            <br/>
                                                                                            <div class="positivo" style="display:block">
                                                                                                <table border="0">
                                                                                                    <tr>
                                                                                                        <td valign="top" style="padding:10px;">
                                                                                                            <img src="../skins/saam/img/ok.png"/> 
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <? echo stripslashes(mb_convert_encoding($rowCapsula['mensajePositivo'], "UTF-8", "ISO-8859-1")); ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>

                                                                                            <div class="negativo" style="display:block">
                                                                                                <table border="0">
                                                                                                    <tr>
                                                                                                        <td valign="top" style="padding:10px;">
                                                                                                            <img src="../skins/saam/img/no.png"/> 
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <? echo stripslashes(mb_convert_encoding($rowCapsula['mensajeNegativo'], "UTF-8", "ISO-8859-1")); ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                            </div>
                                                                                            </div>
                                                                                            </div>
                                                                                            <p>&nbsp;</br>


                                                                                                <?PHP
                                                                                                break;
                                                                                            case "texto":
                                                                                                $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");
                                                                                                ?>

                                                                                                <h3><? echo stripslashes(htmlentities($contenidoTitulo)); ?></h3>
                                                                                                <div align="center">
                                                                                                    <div style="text-align:justify;width:760px;word-wrap: break-word;font-size:14px;"><? echo $descripcion; ?></div>
                                                                                                </div>
                                                                                                <p>&nbsp;</p>
                                                                                                <?PHP
                                                                                                break;
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <div align="center">
                                                                                        <?
                                                                                        if ($tipo == 2) {

                                                                                            //$queryCom 	= "EXEC capTraeComentarioUsuario ".$envio.",".$capsulaId.",".$capsulaVersion.",".$usuario."";
                                                                                            //echo "queryCom $queryCom<br/>";
                                                                                            //$resultCom = $base_datos->sql_query($queryCom);
                                                                                            //$rowCom	= $base_datos->sql_fetch_assoc($resultCom);
                                                                                            //$comentario=$rowCom['ComentarioUsuario'] ? $rowCom['ComentarioUsuario']:'';
                                                                                            ?>
                                                                                            <br/><br/>
                                                                                            <h3>Por favor indique sus comentarios:</h3>
                                                                                            <span id="numeroCarac">Ud tiene disponibles 1000 caracteres.</span>
                                                                                            <br/>
                                                                                            <textarea name="Comentario" id="Comentario" rows="4" cols="40" <?
                                                                                        if ($rUsuario != '') {
                                                                                            echo 'DISABLED';
                                                                                        }
                                                                                        ?> style="border:solid 1px #135293;" onkeypress="return alpha(event)" onkeyup="contarCaracteres();"><? echo $comentario; ?></textarea>



                                                                                            <br/>
                                                                                            <br/>

                                                                                        </div>

                                                                                        <br/>
                                                                                        <div align="center">
                                                                                            <div align="center" class="positivo" style="" id="Positivo<? echo $rowCu['preguntaId']; ?>">
                                                                                                Muchas gracias por responder esta encuesta
                                                                                            </div>
                                                                                        </div>

<? } ?>

                                                                                    <br/>
                                                                                    <br/>

                                                                                    </td>
                                                                                    </tr>
                                                                                    </table>

                                                                                    <td>
                                                                                        </tr>
                                                                                        </table>
                                                                                        </form>
                                                                                        </body>
                                                                                        </html>