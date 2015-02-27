<?
session_start();
include("../default.php");
?>

<script type="text/javascript">

    editorPregunta = new MooEditable('tEditorPregunta', {
        actions: 'bold italic underline strikethrough | justifyleft justifycenter justifyright justifyfull | toggleview'
    });
    editorRespuestaPositiva = new MooEditable('tEditorRespuestaPositiva', {
        actions: 'bold italic underline strikethrough | justifyleft justifycenter justifyright justifyfull | toggleview'
    });

    editorRespuestaNegativa = new MooEditable('tEditorRespuestaNegativa', {
        actions: 'bold italic underline strikethrough | justifyleft justifycenter justifyright justifyfull | toggleview'
    });
        
</script>


<!--<input id="preguntaId" type="hidden" value="0">-->
<table class="tablaEstructura">
    <tr>
        <td valign="top" style="border-bottom: 1px solid #CDCDCD; font-size: 14px; height: 20px">
            <img src="../css/imagenes/asistente/preguntas.png" height="20" width="20">&nbsp;<b>Contenido: Pregunta</b>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <div id="divPreguntaScroll" style="height:420px; width:1135px; overflow-y: scroll; ">
                <table>
                    <tr>
                        <td style="height: 15px">
                            <br>Pregunta:<label id="lbPregunta" style="display:none">*</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 100px">
                            <input id="estadoEditorPregunta" type="hidden" value="0">
                            <textarea id="tEditorPregunta"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 15px">
                            <br>Alternativas (marque la alternativa correcta):<label id="lbAlternativas" style="display:none">*</label>
                        </td>
                    </tr>
                    <tr>
                        <td id="tdAlternativas" style="height: 130px" valign="top">
                            <table id="tablaAlternativas" cellpadding="0" cellspacing="0">
                                <tr><td><input type="radio" name="correcta"/></td><td>a.- </td><td style="width:1040px"><input type="text" class="inputText" placeHolder="Texto de alternativa a" style="width:1020px" maxLength="1000"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                                <tr><td><input type="radio" name="correcta"/></td><td>b.- </td><td style="width:1040px"><input type="text" class="inputText" placeHolder="Texto de alternativa b" style="width:1020px" maxLength="1000"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                                <tr class="trAlternativa"><td><input type="radio" name="correcta"/><td>c.- </td><td style="width:1040px"><input type="text" class="inputText" placeHolder="Texto de alternativa c" style="width:1020px" maxLength="1000" onFocus="agregarAlternativa(this)" onBlur="agregarAlternativaB(this)"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="trCuestionario">
                        <td style="height: 15px">
                            <br>Respuesta positiva:<label id="lbRespuestaPositiva" style="display:none">*</label>
                        </td>
                    </tr>
                    <tr class="trCuestionario">
                        <td style="height: 100px">
                            <textarea id="tEditorRespuestaPositiva"></textarea>
                        </td>
                    </tr>
                    <tr class="trCuestionario">
                        <td style="height: 15px">
                            <br>Respuesta Negativa:<label id="lbRespuestaNegativa" style="display:none">*</label>
                        </td>
                    </tr>
                    <tr class="trCuestionario">
                        <td style="height: 100px">
                            <textarea id="tEditorRespuestaNegativa"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>

        </td>
    </tr>
    <tr>
        <td valign="bottom" align="right">
            <input type="button" class="btn cerrarPopUp" value="Cancelar"> &nbsp
            <input type="button" class="btn cafe" value="Guardar" onClick="capGuardarPregunta()">
        </td>
    </tr>
</table>
