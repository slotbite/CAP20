<?
//$clienteId = $_SESSION['clienteId'];
//$administradorId = $_SESSION['administradorId'];
//
//$capsulaId = mb_convert_encoding(trim($_POST['capsulaId']), "ISO-8859-1", "UTF-8");
//$capsulaVersion = mb_convert_encoding(trim($_POST['capsulaVersion']), "ISO-8859-1", "UTF-8");
$resultado = $objCapsula->capSeleccionarElementos($capsulaId, $capsulaVersion);

$html = "";

if ($resultado) {
    while ($elementos = $base_datos->sql_fetch_assoc($resultado)) {

        if ($elementos["elementoTipo"] == "texto") {
            
            $descripcion = mb_convert_encoding(trim($elementos['contenidoDescripcion']), "UTF-8", "ISO-8859-1");                        
            
            $descripcion = str_replace("\'", "'", $descripcion);
            $descripcion = str_replace('\"', '"', $descripcion);

            $html = $html . "<li id=\"texto-" . (string) $capsulaId . "-" . (string) $capsulaVersion . "-" . $elementos["contenidoId"] . "\" class=\"ui-state-default\" style=\"\">
                <div class=\"divContenido\">
                    <table width=\"100%\">
                        <tbody>
                            <tr>
                                <td width=\"97%\"></td>
                                <td>
                                    <img onclick=\"capEditarTexto(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/editar.png\">
                                </td>
                                <td>
                                    <img onclick=\"capEliminarTexto(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/borrar.png\">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    " . $descripcion . "
                </div>
            </li>";
        }

        if ($elementos["elementoTipo"] == "imagen") {
            
            $descripcion = mb_convert_encoding(trim($elementos['contenidoDescripcion']), "UTF-8", "ISO-8859-1");
            
            $descripcion = str_replace("\'", "'", $descripcion);
            $descripcion = str_replace('\"', '"', $descripcion);

            $html = $html . "<li id=\"imagen-" . (string) $capsulaId . "-" . (string) $capsulaVersion . "-" . $elementos["contenidoId"] . "\" class=\"ui-state-default\" style=\"\">
                <div class=\"divContenido\">
                    <table width=\"100%\">
                        <tbody>
                            <tr>
                                <td width=\"97%\"></td>
                                <td>
                                    <img onclick=\"capEditarImagen(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/editar.png\">
                                </td>
                                <td>
                                    <img onclick=\"capEliminarImagen(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/borrar.png\">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <center>
                        <img src=\"" . htmlentities($elementos["contenidoUrl"]) . "\"/>
                        <br>
                        <br>
                        " . $descripcion . "
                    </center>
                </div>
            </li>";
        }

        if ($elementos["elementoTipo"] == "pregunta") {
            
            $preguntaTexto = mb_convert_encoding(trim($elementos['preguntaTexto']), "UTF-8", "ISO-8859-1");
            $mensajePositivo = mb_convert_encoding(trim($elementos['mensajePositivo']), "UTF-8", "ISO-8859-1");
            $mensajeNegativo = mb_convert_encoding(trim($elementos['mensajeNegativo']), "UTF-8", "ISO-8859-1");
            
            $preguntaTexto = str_replace("\'", "'", $preguntaTexto);
            $preguntaTexto = str_replace('\"', '"', $preguntaTexto);
            
            $mensajePositivo = str_replace("\'", "'", $mensajePositivo);
            $mensajePositivo = str_replace('\"', '"', $mensajePositivo);
            
            $mensajeNegativo = str_replace("\'", "'", $mensajeNegativo);
            $mensajeNegativo = str_replace('\"', '"', $mensajeNegativo);
            
            $html = $html . "<li id=\"pregunta-" . (string) $capsulaId . "-" . (string) $capsulaVersion . "-" . $elementos["preguntaId"] . "\" class=\"ui-state-default\" style=\"\">
                <div class=\"divContenido\">
                    <table width=\"100%\">
                        <tbody>
                            <tr>
                                <td width=\"97%\"></td>
                                <td>
                                    <img onclick=\"capEditarPregunta(" . $elementos["preguntaId"] . ")\" src=\"../css/imagenes/asistente/editar.png\">
                                </td>
                                <td>
                                    <img onclick=\"capEliminarPregunta(" . $elementos["preguntaId"] . ")\" src=\"../css/imagenes/asistente/borrar.png\">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class=\"pregunta\">
                        <b class='bPregunta'>Pregunta:</b>
                        <br>
                        " . $preguntaTexto . "
                    </div>";

            $preguntaId = $elementos["preguntaId"];                                    
            $respuesta = $objCapsula->capSeleccionarRespuestas($capsulaId, $capsulaVersion, $preguntaId);

            $htmlAlternativas = "";

            if ($respuesta) {

                $htmlAlternativas = "<br><table cellspacing=\"0\" cellpadding=\"0\"><tbody>";
                while ($alternativas = $base_datos->sql_fetch_assoc($respuesta)) {

                    $respuestaTexto = mb_convert_encoding(trim($alternativas['respuestaTexto']), "UTF-8", "ISO-8859-1");
                    
                    $respuestaTexto = str_replace("\'", "'", $respuestaTexto);
                    $respuestaTexto = str_replace('\"', '"', $respuestaTexto);
                    
                    if($alternativas['respuestaCorrecta'] == "Si"){
                        $htmlAlternativas = $htmlAlternativas . "<tr><td><input type=\"radio\" checked disabled></td><td style='color:#5D5E6E; padding-left:1px;'>   " . mb_convert_encoding(trim($alternativas['alternativa']), "UTF-8", "ISO-8859-1") . ")  </td><td style='width:1040px; padding-left:2px;'>" . $respuestaTexto . "</td></tr>";
                    }
                    else{
                        $htmlAlternativas = $htmlAlternativas . "<tr><td><input type=\"radio\" disabled></td><td style='color:#5D5E6E; padding-left:1px;'>   " . mb_convert_encoding(trim($alternativas['alternativa']), "UTF-8", "ISO-8859-1") . ")  </td><td style='width:1040px; padding-left:2px;'>" . $respuestaTexto . "</td></tr>";
                    }

                    
                }

                $htmlAlternativas = $htmlAlternativas . "</tbody></table><br>";
            }

            $html = $html . $htmlAlternativas . "";
            
            if($capsulaTipo == "1"){            
                $html = $html . "<div class=\"positivo\"><table border=\"0\"><tr><td valign=\"top\" style=\"padding:10px;\"><img src=\"../css/imagenes/asistente/positivo.png\"></td><td>" . $mensajePositivo . "</td></tr></table></div><br>";
                $html = $html . "<div class=\"negativo\"><table border=\"0\"><tr><td valign=\"top\" style=\"padding:10px;\"><img src=\"../css/imagenes/asistente/negativo.png\"></td><td>" . $mensajeNegativo . "</td></tr></table></div>";
            }
            
            $html = $html . "</div></li>";
        }
        
        if ($elementos["elementoTipo"] == "comentario") {
            
            $descripcion = mb_convert_encoding(trim($elementos['contenidoDescripcion']), "UTF-8", "ISO-8859-1");
            $obligatorio = mb_convert_encoding(trim($elementos['contenidoObligatorio']), "UTF-8", "ISO-8859-1");
            
            $descripcion = str_replace("\'", "'", $descripcion);
            $descripcion = str_replace('\"', '"', $descripcion);
            
            $contenidoHtml = "<table style='width:970px; margin: 0 auto;'><tr><td width='5px' height='18px' valign='top'>";
            
            if($obligatorio == "1"){
                $contenidoHtml .= "<label title='Este campo es obligatorio'>*</label>";                
            }
            
            $contenidoHtml .= "</td><td>" . $descripcion . "</td></tr><tr><td align='center' colspan='2'><div class='divComentarioTexto'></div><br><br></td></tr></table>";    
            

            $html = $html . "<li id=\"comentario-" . (string) $capsulaId . "-" . (string) $capsulaVersion . "-" . $elementos["contenidoId"] . "\" class=\"ui-state-default\" style=\"\">
                <div class=\"divContenido\">
                    <table width=\"100%\">
                        <tbody>
                            <tr>
                                <td width=\"97%\"></td>
                                <td>
                                    <img onclick=\"capEditarComentario(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/editar.png\">
                                </td>
                                <td>
                                    <img onclick=\"capEliminarComentario(" . $elementos["contenidoId"] . ")\" src=\"../css/imagenes/asistente/borrar.png\">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    " . $contenidoHtml . "
                </div>
            </li>";
        }
        
        
    }
}

echo $html;

?>

<!--
<li id="texto-114-1-266" class="ui-state-default" style="">
    <div class="divContenido">
        <table width="100%">
            <tbody>
                <tr>
                    <td width="97%"></td>
                    <td>
                        <img onclick="capEditarTexto(266)" src="../css/imagenes/asistente/editar.png">
                    </td>
                    <td>
                        <img onclick="capEliminarTexto(266)" src="../css/imagenes/asistente/borrar.png">
                    </td>
                </tr>
            </tbody>
        </table>
        <p>Ante las diversar versiones que se han instalada para la aplicación de las cápsulas, se ha desarrollado una nueva versión del asistente que deberá ser capaz de permitirle al usuario trabajar de mejor manera en la construcción de la cápsula.</p>
        <p> </p>
        <p>A continuación, se muestra como ingresamos una imagen del newsletter, cuyo formato se envía cada semana.</p>
        <p> </p>
    </div>
</li>


<li id="imagen-114-1-267" class="ui-state-default">
    <div class="divContenido">
        <table width="100%">
            <tbody>
                <tr>
                    <td width="97%"></td>
                    <td>
                        <img onclick="capEditarImagen(267)" src="../css/imagenes/asistente/editar.png">
                    </td>
                    <td>
                        <img onclick="capEliminarImagen(267)" src="../css/imagenes/asistente/borrar.png">
                    </td>
                </tr>
            </tbody>
        </table>
        <center>
            <img src="../multimedia/2013//CAP_114_1/Newsletter 7.jpg">
            <br>
            <br>
            Este es el pie de página.
        </center>
    </div>
</li>


<li id="pregunta-114-1-292" class="ui-state-default">
    <div class="divContenido">
        <table width="100%">
            <tbody>
                <tr>
                    <td width="97%"></td>
                    <td>
                        <img onclick="capEditarPregunta(292)" src="../css/imagenes/asistente/editar.png">
                    </td>
                    <td>
                        <img onclick="capEliminarPregunta(292)" src="../css/imagenes/asistente/borrar.png">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="pregunta">
            <b>Pregunta:</b>
            <br>
            <p>¿Qué le ha parecido el nuevo asistente?</p>
        </div>
        <br>
        <table cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>
                        <input type="radio" disabled="">
                    </td>
                    <td style="width:1040px">Muy bueno</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" disabled="">
                    </td>
                    <td style="width:1040px">Ma o meque no ma</td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" disabled="">
                    </td>
                    <td style="width:1040px">Bastante mediocre por decir lo menos</td>
                </tr>
            </tbody>
        </table>
    </div>
</li>-->

