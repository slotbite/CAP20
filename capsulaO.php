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

$mostrarmensaje = false;
$respuesta = "";
// se encarga de los datos recividos del cuestionario 
if ($_POST) {
	
	// valida campos (imputs ? )
    if (($capsulaid != "") && ($version != "")) {
		
        // Obtiene los IDs de los checkbox (_SELECT_) seleccionados elimina el valor despues del _ (Respuesta_123 = Respuesta) para guardar los datos 
        foreach ($_REQUEST as $k => $v) {

            if (strpos($k, 'Respuesta') !== false) {
                $parts = explode('_', $k);
                $id = $parts[0];
                $id2 = $parts[1];

                $preguntaId = $id2;
                $respuestaUsuario = $_POST["Respuesta_" . $id2] ? $_POST["Respuesta_" . $id2] : 0;

                $query = "EXEC capGuardaRespuestaEncuesta " . $envio . "," . $capsulaid . "," . $version . "," . $usuarioId . "," . $id2 . "," . $respuestaUsuario . " ";
				echo "query $query<br/>";
//                $result = $base_datos->sql_query($query);
                $mostrarmensaje = true;
            }
        }
		
        $query = "EXEC capGuardaComentarioEncuesta " . $envio . "," . $capsulaid . "," . $version . "," . $usuarioId . ",'" . $comentario . "' ";
        //echo "query $query <br/>";
        $result = $base_datos->sql_query($query);
    }
} 
// SE PROCESA EL HASH PARA ENVIAR LOS DATOS DE LA BASE DE DATOS 
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

    $_SESSION["cap_envio"] = $envio;
    $_SESSION["cap_usuarioId"] = $usuarioId;
    $_SESSION["cap_capsulaid"] = $capsulaid;
    $_SESSION["cap_version"] = $version;

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

        <link rel="stylesheet" type="text/css" href="css/capsula.css" media="screen" />
        <script type="text/javascript" src="scripts/jwplayer.js"></script> 
        <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script>
        <script type="text/javascript" src="scripts/Observer.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.js"></script>
        <script type="text/javascript" src="scripts/Autocompleter.Request.js"></script>
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
                min-height:600px; 
                border: 1px solid #CCC;                                 
                color: #5A5655; 
                -webkit-border-radius: 5px;                                
            }            
        </style>


        <script type="text/javascript">

            window.addEvent('domready', function () {

                if ($('cerrada')) {

                    var cerrada = $('cerrada').value;

                    if (cerrada == "1") {
                        var elementosTexArea = $$("textarea");
                        for (i = 0; i < elementosTexArea.length; i++) {
                            elementosTexArea[i].setAttribute('readonly', 'readonly');
                            elementosTexArea[i].style.backgroundColor = "#F0F0F0";
                        }

                        var elementosDiv = $$(".divComentarioTexto");
                        for (i = 0; i < elementosDiv.length; i++) {
                            elementosDiv[i].style.backgroundColor = "#F0F0F0";
                        }

                        var elementos = $$("input");
                        for (i = 0; i < elementos.length; i++) {
                            elementos[i].disabled = true;
                        }

                    }
                }

                if ($$(".guardarComentario")) {

                    $$(".guardarComentario").addEvent('click', function (el) {
                        var elemento = this.getParent().getParent().getParent().getParent();

                        var inputId = elemento.getElements("input[class=contenidoComentarioId]");
                        var textarea = elemento.getElements("textarea");

                        var viewData = [];
                        viewData.push({contenidoId: inputId.get("value"), comentario: textarea.get("value")});

                        var request = new Request.JSON({
                            url: 'guardar_comentarioO.php',
                            async: false,
                            onComplete: function (mensaje) {

<? if ($tipo != 2) { ?>
                                    alert("Su información ha sido guardada satisfactoriamente.");
<? } ?>
                            },
                            data: {// our demo runner and jsfiddle will return this exact data as a JSON string
                                json: viewData
                            }

                        }).send();
                    });
                }
            })

        </script>

        <?PHP if ($tipo == 1) { ?>
            <script type="text/javascript">

                window.addEvent('domready', function () {

                    if ($$(".validar")) {
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
                                url: 'validar_respuestasO.php',
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
                    }

                });

            </script>
        <?PHP } ?>
        <?PHP if ($tipo == 2) { ?>
            <script type="text/javascript">

                window.addEvent('domready', function () {

                    $("Validar").addEvent('click', function () {
						$("evaluacion").submit();    //DEBUG
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
                            //alert("submit");

                            var btnsGuardar = $$(".guardarComentario");

                            for (var i = 0; i < btnsGuardar.length; i++) {
                                btnsGuardar[i].click();
                            }



   //DEBUG                         $("evaluacion").submit();
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
        <form id="evaluacion" method="POST" action="capsulaO.php">
            <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

            <div id="divCentral">

                <center>

                    <div style="background: #FFFFFF; width:1100px; padding:10px; margin-bottom: 20px; min-height: 300px">

                        <? if ($existe == 0) { ?>
                            <table border="1"  CELLSPACING='0' CELLSPADDING='0' Width="60%" align="center" style="font-size:14px;">
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
                                            <img id="temaUrl" src='<? echo htmlentities("/CAP20" . $encabezado); ?>' border='0' height='150px' width="700px">
                                        </td>                                
                                    <? } ?>                                                                                                                                                                                                                                
                                    <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                        <img src='<? echo $logo; ?>' border='0' width="75%" height="75%">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 100px" colspan="2" align="left">
                                        <h3>Bienvenido(a)</h3>
                                        <b><? echo htmlentities($nombre); ?></b>

                                        <br/><br/>

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

                            <?PHP
                            // Si la c�psula no est� anulada, se verifica el cierre
                            $queryDur = "EXEC capVerificaCierre " . $usuarioId . "," . $capsulaid . "," . $version . "," . $envio . "";
                            $resultD = $base_datos->sql_query($queryDur);
                            $rowD = $base_datos->sql_fetch_assoc($resultD);
                            $plazo = $rowD["plazo"];
                            ?>

                            <? if ($tipo != 3) { ?>

                                <br/>
                                <div style='width:98%;height:20px;padding-top:4px;padding-left:5px;font-size:16px; border: 1px solid #CCC' >
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

                            <div style="background-color: #FFFFFF; width:990px;">

                                <input type='hidden' id='envioId' name='envioId' value='<? echo $envio; ?>'/>
                                <input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid; ?>'/>
                                <input type='hidden' id='versionId' name='versionId' value='<? echo $version; ?>'/>
                                <input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuarioId; ?>'/>
                                <input type="hidden" id='cerrada' value='<? echo $cerrada; ?>'/>

                                <?PHP
                                $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaid . "," . $version . "";
                                //echo "queryCapsula $queryCapsula <br/>";
                                $resultCapsula = $base_datos->sql_query($queryCapsula);

                                $i = 1;

                                while ($rowCapsula = $base_datos->sql_fetch_assoc($resultCapsula)) {
                                    $elementoTipo = $rowCapsula['elementoTipo'] ? $rowCapsula['elementoTipo'] : "";
                                    $contenidoId = $rowCapsula['contenidoId'] ? $rowCapsula['contenidoId'] : 0;
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
                                            <div align='center'><img src='<? echo htmlentities($contenidoUrl); ?>' style="max-width:980px;"/>
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
                                                            $queryPr = "EXEC capListarRespuestasPregunta " . $capsulaid . "," . $version . "," . $clienteId . "," . $preguntaId . "," . $usuarioId . "";
                                                            //echo "queryPr  $queryPr <br/>";
                                                            $resultPr = $base_datos->sql_query($queryPr);
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
                                                                <tr style="height:18px">
                                                                    <?
                                                                    if ($rUsuario == '') {
                                                                        ?>
                                                                        <td valign="top">
                                                                            <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>' value='<? echo $rId; ?>'/>                                                               
                                                                        </td>
                                                                        <td valign="middle"><? echo trim(htmlentities($alternativa)); ?>)</td>
                                                                        <td valign="middle" style="padding-left:2px;"><? echo htmlentities($rTexto); ?>
                                                                            <img src="skins/saam/img/ok_small.png" style="visibility:hidden;" width="10px"/>
                                                                        </td>
                                                                        <?
                                                                    } else {

                                                                        $respuesta = "SI";
                                                                        ?>
                                                                        <td valign="top">
                                                                            <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>'  DISABLED  
                                                                            <?
                                                                            if ($respuestausuario == 1) {
                                                                                echo 'CHECKED';
                                                                            }
                                                                            ?> />

                                                                        </td>
                                                                        <td valign="middle"><? echo trim(htmlentities($alternativa)); ?>)</td>
                                                                        <td valign="middle" style="padding-left:2px;"><? echo htmlentities($rTexto); ?>

                                                                            <?PHP if ($escorrecta == 1 && $respuestausuario == 1 && $tipo == 1) { ?>
                                                                                <img src="skins/saam/img/ok_small.png" style="visibility:visible;" width="10px"/>
                                                                            <? } ?>
                                                                        </td>
                                                                        <?
                                                                    }
                                                                    ?></tr><?
                                                            }
                                                            ?>                                                        
                                                        </table>

                                                        <?
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

                                            $queryComentario = "EXEC AcapSeleccionarComentarioUsuario " . $envio . "," . $capsulaid . "," . $version . "," . $contenidoId . "," . $usuarioId . "";
                                            //echo $queryComentario$usuarioId
                                            $result = $base_datos->sql_query($queryComentario);
                                            $resultRow = $base_datos->sql_fetch_assoc($result);
                                            $descripcionUsuario = $resultRow['comentarioUsuario'] ? $resultRow['comentarioUsuario'] : "";
                                            ?>

                                            <div class="divContenidoUsuario">                                                                                                

                                                <table style="width:970px; margin: 0 auto; margin-top: 10px">                            
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
                                                                <textarea maxlength='1000' <?
                                                                if ($contenidoObligatorio == 1) {
                                                                    echo "class='textAreaObligatorio'";
                                                                }
                                                                ?> ><? echo trim(htmlentities($descripcionUsuario)); ?></textarea>
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

                                if ($tipo == 2) {
                                    if ($mostrarmensaje == true) {
                                        ?>

                                        <script type="text/javascript">
                                            alert("Su encuesta ha sido enviada. Gracias");
                                        </script> 

                                        <?
                                    }
                                    if ($respuesta == "SI") {
                                        ?>
                                        <br/>
                                        <div align="center">
                                            <div align="center" class="positivo" style="" id="Positivo<? echo $rowCu['preguntaId']; ?>">
                                                Muchas gracias por responder esta encuesta
                                            </div>
                                        </div>

                                        <script type="text/javascript">

                                            var elementosTexArea = $$("textarea");
                                            for (var i = 0; i < elementosTexArea.length; i++) {
                                                elementosTexArea[i].readOnly = true;
                                            }

                                        </script>

                                        <?PHP
                                    }
                                }
                                ?>

                            </div>
<? } ?>
                    </div>
                </center>
            </div>                                                            
        </form>
    </body>
</html>