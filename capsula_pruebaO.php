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

        <link rel="stylesheet" type="text/css" href="css/capsula.css" media="screen" />
        <script type="text/javascript" src="scripts/jwplayer.js"></script> 
        <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script>
        <script type="text/javascript" src="scripts/Observer.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.Request.js"></script>
        <script type="text/javascript" src="scripts/Funciones.js"></script>					

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
                width:99%; 
                margin: 0px auto;
                min-height:600px; 
                border: 1px solid #CCC;                                 
                color: #5A5655; 
                -webkit-border-radius: 5px;                                
            }

        </style>


        <script type="text/javascript">

            window.addEvent('domready', function () {

                if ($$(".guardarComentario")) {

                    $$(".guardarComentario").addEvent('click', function () {
<? if ($tipo != 2) { ?>
                            alert("Su información ha sido guardada satisfactoriamente.");
<? } ?>
                    });
                }
            })

        </script>


        <?PHP if ($tipo == 1) { ?>
            <script type="text/javascript">

                window.addEvent('domready', function () {


                    if ($$(".validar"))
                        $$(".validar").addEvent('click', function (el) {
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
                                url: 'validar_respuestasPrueba.php',
                                onComplete: function (jsonObj) {
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
                                data: {// our demo runner and jsfiddle will return this exact data as a JSON string
                                    json: viewData
                                }
                            }).send();

                        });

                });

            </script>
        <?PHP } ?>
        <?PHP if ($tipo == 2) { ?>
            <script type="text/javascript">

                window.addEvent('domready', function () {


                    $("Validar").addEvent('click', function () {
                        var validar = true;		// valida si todas las preguntas fueron respondidas
                        var validar2 = true;		// valida si todas los comentarios fueron respondidos
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


                        var elementosTexArea = $$("textarea");
                        for (var i = 0; i < elementosTexArea.length; i++) {
                            var tipoTa = elementosTexArea[i].get("class");
                            var valorTa = elementosTexArea[i].get("value");

                            if (tipoTa == "textAreaObligatorio" && valorTa.trim() == "") {
                                validar2 = false;
                            }
                        }


                        if (validar == false && validar2 == false) {
                            alert("Debe responder todas las preguntas y completar todos los campos.");
                            return false;
                        }
                        else if (validar == false) {
                            alert("Debe responder todas las preguntas.");
                            return false;
                        }
                        else if (validar2 == false) {
                            alert("Debe completar todos los campos.");
                            return false;
                        }
                        else {
                            alert("Su encuesta ha sido enviada. Gracias");
                            //alert("submit");
                            //$("evaluacion").submit();
                        }

                    });

                })

                function contarCaracteres() {
                    var numero = 0;
                    numero = $('Comentario').value.length;
                    var numfinal = 1000 - numero;
                    $('numeroCarac').set('text', 'Ud tiene disponibles ' + numfinal + ' caracteres.');
                }

                function alpha(e) {
                    var k;
                    document.all ? k = e.keyCode : k = e.which;
                    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || k == 44 || k == 46 || (k >= 48 && k <= 57));
                }

            </script>
        <?PHP } ?>
    </head>
    <body>
        <form id="evaluacion" method="POST" action="capsula_prueba.php">
            <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

            <div id="divCentral">

                <center>

                    <div style="background: #FFFFFF; width:1100px; padding:10px; margin-bottom: 20px">

                        <table class="tablaTema" CELLSPACING='0' CELLSPADDING='0'>
                            <tr>                                
                                <? if (trim($encabezado) == "") { ?>
                                    <td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                        <span style='font-weight:bolder;font-size:20px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                    </td>
                                    <?
                                } else {
                                    ?>
                                    <td id="tdCabeceraTema2" class="tdCabeceraTemaB">
                                        <img id="temaUrl" src='<? echo htmlentities($encabezado); ?>' border='0' height='150px' width="700px">
                                    </td>                                
                                <? } ?>                                                                                                                                                                                                                                
                                <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                    <img src='css/imagenes/headerFooter/logo.png' border='0'>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 100px" colspan="2" align="left">
                                    <h3>Bienvenido(a)</h3>
                                    <b>Nombre de usuario</b>

                                    <br/><br/>

                                    <input type='hidden' id='envioId' name='envioId' value='<? echo $envio; ?>'/>
                                    <input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid; ?>'/>
                                    <input type='hidden' id='versionId' name='versionId' value='<? echo $version; ?>'/>

                                </td>
                            </tr>
                            <tr>
                                <td id="tdTituloCapsula" align="center" colspan="2">
                                    <? if ($tipo != 3) { ?>
                                        <h1><? echo htmlentities(stripslashes($Capnombre)); ?></h1>
                                    <? } ?>
                                </td>
                            </tr>
                        </table>

                        <br/>

                        <div style="background-color: #FFFFFF; width:990px;">

                            <?PHP
                            $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaid . "," . $version . "";
//echo "queryCapsula $queryCapsula <br/>";
                            $resultCapsula = $base_datos->sql_query($queryCapsula);

                            $i = 1;

                            while ($rowCapsula = $base_datos->sql_fetch_assoc($resultCapsula)) {
                                $elementoTipo = $rowCapsula['elementoTipo'] ? $rowCapsula['elementoTipo'] : "";
                                $preguntaId = $rowCapsula['preguntaId'] ? $rowCapsula['preguntaId'] : 0;
                                $contenidoDescripcion = $rowCapsula['contenidoDescripcion'] ? $rowCapsula['contenidoDescripcion'] : "";
                                $contenidoUrl = $rowCapsula['contenidoUrl'] ? $rowCapsula['contenidoUrl'] : "";
                                $preguntaTexto = $rowCapsula['preguntaTexto'] ? $rowCapsula['preguntaTexto'] : "";
                                $contenidoObligatorio = $rowCapsula['contenidoObligatorio'] ? $rowCapsula['contenidoObligatorio'] : "";

                                if ($contenido == "") {
                                    $contenidoUrl = substr($contenidoUrl, 3, strlen($contenidoUrl));
                                }
                                switch ($elementoTipo) {
                                    case "imagen" :
                                        ?>
                                        <br/>
                                        <div align='center'>
                                            <img src='<? echo htmlentities($contenidoUrl); ?>' style="max-width:980px;"/>
                                            <br/>
                                            <span style="font-size:12px;"><i><? echo htmlentities($contenidoDescripcion) ?></i></span>
                                        </div>                                        
                                        <br/>
                                        <?
                                        break;

                                    case "pregunta":
                                        ?>
                                        <div class="divContenidoUsuario">
                                            <div id="Pregunta_<? echo $preguntaId; ?>" class="Capsula_<? echo $capsulaid; ?>">
                                                <input type='hidden' class='PreguntaId' value='<? echo $preguntaId; ?>'/>
                                                <div class="pregunta">
                                                    <b>Pregunta <? echo $i++; ?>:</b>
                                                    <br/>
                                                    <? echo stripslashes(mb_convert_encoding($preguntaTexto, "UTF-8", "ISO-8859-1")); ?>
                                                </div>
                                                <br/>
                                                <div class="alternativas">
                                                    <table cellspacing="0" cellpadding="0" style="color: #28292a; font: 12px Verdana,Arial,Helvetica,sans-serif;">
                                                        <?
                                                        $queryPr = "EXEC capListarRespuestasPreguntaPrueba " . $capsulaid . "," . $version . "," . $envio . "," . $preguntaId . "," . $tipo . "";
                                                        //echo "queryPr  $queryPr <br/>";
                                                        $resultPr = $base_datos->sql_query($queryPr);
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

                                                            if ($rUsuario == strtolower($rTexto)) {
                                                                $respuestausuario = 1;
                                                            }
                                                            ?><tr style="height:18px"><?
                                                                if ($rUsuario == '') {
                                                                    ?>
                                                                    <td valign="top">
                                                                        <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>' value='<? echo $rId; ?>'/></td>
                                                                    <td valign="middle"><? echo trim(htmlentities($alternativa)); ?>)</td>
                                                                    <td valign="middle" style="padding-left:2px;"><? echo htmlentities($rTexto); ?>
                                                                        <img src="skins/saam/img/ok_small.png" style="visibility:hidden;" width="10px"/>
                                                                    </td>
                                                                    <?
                                                                } else {
                                                                    ?>
                                                                    <td valign="top">
                                                                    <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>'  DISABLED  <?
                                                                    if ($respuestausuario == 1) {
                                                                        echo 'CHECKED';
                                                                    }
                                                                    ?> /></td>                                                                        
                                                                        <td valign="middle" style="padding-left:2px;"><? echo htmlentities($rTexto); ?>

                                                                    <?PHP if ($escorrecta == 1 && $respuestausuario == 1) { ?>

                                                                        <img src="skins/saam/img/ok_small.png" style="visibility:visible;" width="10px"/>
                                                                    <? } ?>
                                                                        </td>
                                                                    <?
                                                                }
                                                                ?></tr><?
                                                        }
                                                        ?>
                                                    </table><?
                                                    if ($tipo == 1) {
                                                        ?>

                                                        <br/>
                                                        <input class="validar" type='button' id='Validar<? echo $preguntaId ?>' value='Validar' style='width:100px;text-align:center;' 
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

                                                <br/><br/>
                                            </div>
                                        </div>                                        

                                        <?PHP
                                        break;
                                    case "texto":
                                        $descripcion = mb_convert_encoding($contenidoDescripcion, "UTF-8", "ISO-8859-1");
                                        ?>                                           
                                        <div class="divContenidoUsuario">
                                            <? echo $descripcion; ?>
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

                                            <table style="width:970px; margin: 0 auto; margin-top: 10px">                            
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
                                                                echo "class='textAreaObligatorio'";
                                                            }
                                                            ?> ></textarea>
                                                        </div>                                                                                                                                                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="right" colspan="2">
                                                        <br/>

                                                        <? if ($tipo == 2) { ?>                                                        
                                                            <input class="guardarComentario" type='button' value='Guardar' style='width:100px;text-align:center;display:none'/>                                                            
                                                        <? } else { ?>
                                                            <input class="guardarComentario" type='button' value='Guardar' style='width:100px;text-align:center;'/> 
                                                        <? } ?>
                                                        <br/><br/>
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
                                    <input type='button' id='Validar' value='Enviar Encuesta' style='width:150px;text-align:center;' <?
                                    if (trim($rUsuario) != '') {
                                        echo 'disabled=disabled';
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

                    </div>

                </center>

            </div>                                       
        </form>
    </body>
</html>