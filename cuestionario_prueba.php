<?
$queryCu = "EXEC capListarPreguntasCapsulaPrueba " . $capsulaid . "," . $version . "," . $envio . "," . $tipo . "";
//echo $queryCu;
$resultCu = $base_datos->sql_query($queryCu);
$i = 0;
while ($rowCu = $base_datos->sql_fetch_assoc($resultCu)) {
    $i = $i + 1;
    ?>
    <br/>
    <br/>
    <br/>
    <DIV ALIGN="CENTER">
        <div class="pregunta">
            <b>Pregunta <? echo $i; ?>:</b><br><? echo stripslashes(mb_convert_encoding($rowCu['preguntaTexto'], "UTF-8", "ISO-8859-1")); ?>
        </div>


        <div class="alternativas">
            <?
            $queryPr = "EXEC capListarRespuestasPreguntaPrueba " . $capsulaid . "," . $version . "," . $envio . "," . $rowCu['preguntaId'] . "," . $tipo . "";
            //echo $queryPr;
            $resultPr = $base_datos->sql_query($queryPr);
            while ($rowPr = $base_datos->sql_fetch_assoc($resultPr)) {
                $rCorrecta = strtolower($rowPr['respuestaCorrecta']);
                $rUsuario = strtolower($rowPr['respuestaUsuario']);
                $rTexto = $rowPr['respuestaTexto'];
                $escorrecta = 0;
                $rId = trim($rowPr['respuestaId']);
                if ($rCorrecta == strtolower($rTexto)) {
                    $escorrecta = 1;
                }
                ?>
                <? if ($rUsuario == ' ') { ?>
                    <input type='radio' name='Respuesta<? echo $rowCu['preguntaId']; ?>' class='Respuesta<? echo $rowCu['preguntaId']; ?>' value='<? echo $rId; ?>'><? echo htmlentities($rTexto); ?><input id="respuesta_<? echo $rowCu['preguntaId']; ?>" type="hidden" value="<? echo $escorrecta; ?>">
                    <?
                    if ($escorrecta == 1) {
                        ?>
                        <img src="skins/saam/img/ok_small.png" id="RespuestaCorrecta<? echo $rowCu['preguntaId']; ?>" style="visibility:hidden;"/>
                        <?
                    }
                    ?>

                    </br>
                <? } else { ?>
                    <input type='radio' name='Respuesta<? echo $rowCu['preguntaId']; ?>'  DISABLED  <? if (strtolower($rTexto) == $rUsuario) {
                echo 'CHECKED';
            } ?> ><? echo htmlentities($rowPr['respuestaTexto']); ?>
                    <?
                    if ($escorrecta == 1) {
                        ?>
                        <img src="skins/saam/img/ok_small.png" id="RespuestaCorrecta<? echo $rowCu['preguntaId']; ?>" style="visibility:visible;"/>
                        <?
                    }
                    ?>
                    </br>
        <? } ?>
    <? } ?>

            <input type='button' id='Validar<? echo $rowCu['preguntaId']; ?>' value='Validar' style='width:150px;text-align:center;' <? if ($rUsuario != ' ') {
        echo 'disabled=disabled';
    } ?> onclick="ValidarC('Respuesta<? echo $rowCu['preguntaId']; ?>',<? echo $rowCu['preguntaId']; ?>)"/>
        </div>


    <? if ($rUsuario == ' ') { ?>
            <div class="positivo" style="display:none;" id="Positivo<? echo $rowCu['preguntaId']; ?>">
                <table border="0">
                    <tr>
                        <td valign="top" style="padding:10px;">
                            <img src="skins/saam/img/ok.png"/> 
                        </td>
                        <td>
        <? echo mb_convert_encoding($rowCu['mensajePositivo'], "UTF-8", "ISO-8859-1"); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="negativo" style="display:none;" id="Negativo<? echo $rowCu['preguntaId']; ?>">
                <table border="0">
                    <tr>
                        <td valign="top" style="padding:10px;">
                            <img src="skins/saam/img/no.png"/> 
                        </td>
                        <td>
            <? echo mb_convert_encoding($rowCu['mensajeNegativo'], "UTF-8", "ISO-8859-1"); ?>
                        </td>
                    </tr>
                </table>
            </div>
    <? } else { ?>
            <div class="positivo" style="<? if ($rCorrecta == $rUsuario) {
            echo 'display:block';
        } else {
            echo 'display:none';
        } ?>">
                <table border="0">
                    <tr>
                        <td valign="top" style="padding:10px;">
                            <img src="skins/saam/img/ok.png"/> 
                        </td>
                        <td>
        <? echo mb_convert_encoding($rowCu['mensajePositivo'], "UTF-8", "ISO-8859-1"); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="negativo" style="<? if ($rCorrecta != $rUsuario) {
            echo 'display:block';
        } else {
            echo 'display:none';
        } ?>">
                <table border="0">
                    <tr>
                        <td valign="top" style="padding:10px;">
                            <img src="skins/saam/img/no.png"/> 
                        </td>
                        <td>
        <? echo mb_convert_encoding($rowCu['mensajeNegativo'], "UTF-8", "ISO-8859-1"); ?>
                        </td>
                    </tr>
                </table>
            </div>
    <? } ?>	
    </DIV>		
    <?
}
?>
