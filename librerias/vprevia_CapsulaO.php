<?
session_start();
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
include("../default.php");

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

            <link rel="stylesheet" type="text/css" href="../css/capsula.css" media="screen" />
            <script type="text/javascript" src="../scripts/jwplayer.js"></script> 
            <script type="text/javascript" src="../scripts/mootools-1.2.5-core.js"></script>
            <script type="text/javascript" src="../scripts/Observer.js"></script>
            <script type="text/javascript" src="../scripts/Autocompleter.js"></script>
            <script type="text/javascript" src="../scripts/Autocompleter.Request.js"></script>
            <script type="text/javascript" src="../scripts/Funciones.js"></script>					

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
                    border: 1px solid #CCC;                                 
                    color: #5A5655; 
                    -webkit-border-radius: 5px;                                                    
                }

            </style>

        </head>
        <body>
            <form id="evaluacion" method="POST" action="capsula_prueba.php">
                <input name="hash" type="hidden" value="<? echo $_GET['hash']; ?>" />

                <div id="divCentral">

                    <center>

                        <div style="background: #FFFFFF; width:1100px; padding:10px; margin-bottom: 20px;">

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
                                            <img id="temaUrl" src='<? echo "../" . htmlentities($encabezado); ?>' border='0' height='150px' width="700px">
                                        </td>                                
                                    <? } ?>                                                                                                                                                                                                                                
                                    <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                        <img src='../css/imagenes/headerFooter/logo.png' border='0'>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 100px" colspan="2" align="left" >
                                        <h3 style="font-size:14px; padding-top: 15px">Bienvenido(a)</h3>
                                        <b style="font-size:12px; padding-top: 15px">Nombre de usuario</b>

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

                            <br/>

                            <div style="background-color: #FFFFFF; width:990px;">

                                <?PHP
                                $queryCapsula = "exec dbo.AcapSeleccionarElementos " . $capsulaId . "," . $capsulaVersion . "";
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
                                                <img src='<? echo "../" . htmlentities($contenidoUrl); ?>' style="max-width:980px;"/>
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
                                                            $queryPr = "EXEC capVistaPreguntaRespuestas " . $cliente_id . "," . $capsulaId . "," . $capsulaVersion . "," . $preguntaId;
                                                            //echo "queryPr  $queryPr <br/>";
                                                            $resultPr = $base_datos->sql_query($queryPr);
                                                            while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                                                                $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                                                                $rUsuario = trim(strtolower($rowPr['respuestaUsuario']));
                                                                ?>

                                                                <tr style="height:18px">
                                                                    <td valign="top">
                                                                        <input class="respuesta" type='radio' name='Respuesta_<? echo $preguntaId ?>'  DISABLED  <?
                                                                        if (strtoupper($rCorrecta) == "SI" && $tipo == 1) {
                                                                            echo 'CHECKED';
                                                                        }
                                                                        ?> /></td>                   
                                                                    <td valign="middle"><? echo trim(htmlentities($rowPr['alternativa'])); ?>)</td>
                                                                    <td valign="middle" style="padding-left:2px;"><? echo htmlentities($rowPr['respuestaTexto']); ?>
                                                                        <?PHP if (strtoupper($rCorrecta) == "SI") { ?>
                                                                            <img src="../skins/saam/img/ok_small.png" style="visibility:visible;" width="10px"/>
                                                                        <? } ?>
                                                                    </td>
                                                                </tr>

                                                                <?
                                                            }
                                                            ?></table><?
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
                                                    <? }
                                                    ?>  

                                                    <br/>

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
                                                        <td id="tdComentarioTitulo" style="font-size:12px;">
                                                            <? echo $descripcion; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" colspan="2">  
                                                            <div class="divComentarioTexto">

                                                                <textarea maxlength='1000'></textarea>                                                                
                                                            </div>                                                                                                                                                                                        
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="2">
                                                            <br/>
                                                            <input class="validar" type='button' value='Guardar' style='width:100px;text-align:center;'/> 
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
                                ?>

                            </div>

                        </div>

                    </center>

                </div>

                <br/>

                <a href="#" onclick="parent.searchBoxPpal.close();">Cerrar</a>

            </form>
        </body>
    </html>





    <?
}
?>