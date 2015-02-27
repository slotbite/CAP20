<?
session_start();
setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

ini_set('mssql.textlimit', 2147483647);
ini_set('mssql.textsize', 2147483647); 

$clienteId = $_SESSION['clienteId'];
$administradorId = $_SESSION['administradorId'];
$usuarioLog = $_SESSION['usuario'];

$_SESSION['clienteId'] = 1;
$_SESSION['administradorId'] = $administradorId;


include("../default.php");
$plantilla->setPath('../plantillas/');
$plantilla->setTemplate("asistenteCabecera");
$plantilla->setVars(array("USUARIO" => " $usuarioLog ",
    "FECHA" => "$fechaActual"
));
echo $plantilla->show();

if ($usuarioLog == '') {
    echo "<script>window.location='../index.php';</script>";
}


require('clase/capsula.class.php');
$objCapsula = new Capsula();


$capsulaId = ($_POST['capsulaId'] != "") ? $_POST['capsulaId'] : "0";
$capsulaVersion = ($_POST['capsulaVersion'] != "") ? $_POST['capsulaVersion'] : "0";
$capsulaNombre = '';
$capsulaDescripcion = '';
$capsulaTipo = 1;
$temaId = 0;
$temaNombre = '';
$capsulaEstado = 1;


//$capsulaId = 114;
//$capsulaVersion = 1;

if ($capsulaId != "0" && $capsulaVersion != "0") {

//    $capsulaId = $_POST['capsulaId'];
//    $capsulaVersion = $_POST['capsulaVersion'];

    $capsula = $objCapsula->capSeleccionarCapsula($capsulaId, $capsulaVersion);     //Genera la edicion posterior de ser necesario

    $capsulaId = $capsula['capsulaId'];
    $capsulaVersion = $capsula['capsulaVersion'];
    $capsulaNombre = $capsula['capsulaNombre'];
    $capsulaDescripcion = $capsula['capsulaDescripcion'];
    $capsulaTipo = $capsula['capsulaTipo'];
    $capsulaEstado = $capsula['capsulaEstado'];
    $temaId = $capsula['temaId'];
    $temaNombre = $capsula['temaNombre'];
    $capsulaNumero = $capsula['capsulaNumero'];
    
    //echo $capsulaNumero . "AAAAA";

    $imagenes = $objCapsula->capSeleccionarImagenes($capsulaId, $capsulaVersion);

    $htmlDivVistaPrevia = "";
    $htmlUlImagenes = "";
    $totalImagenes = 1;

    if ($imagenes) {
        while ($listaImagenes = $base_datos->sql_fetch_assoc($imagenes)) {

            $htmlDivVistaPrevia = $htmlDivVistaPrevia . "<img src = \"" . htmlentities($listaImagenes['capsulaImagenRuta']) . "\" alt = \"\" />";
            $htmlUlImagenes = $htmlUlImagenes . "<li rel='" . (string) $totalImagenes . "'><img src=\"" . htmlentities($listaImagenes['capsulaImagenRuta']) . "\" alt=\"\" /></li>";
            $totalImagenes++;
        }
    }
}

//echo $capsulaId . "<br>";
//echo $capsulaVersion . "<br>";


$temas = $objCapsula->capMostrarTemas($clienteId, $administradorId, '', 0);

$htmlTemas = "<table id='tablaListaTema' class='tablaLupa'>";

if ($temas) {
    while ($listaTemas = $base_datos->sql_fetch_assoc($temas)) {
        $htmlTemas = $htmlTemas . "<tr><td><input type='radio' name='temas'/></td><td>" . htmlentities(trim($listaTemas['temaNombre'])) . "</td></tr>";
    }
}

$htmlTemas = $htmlTemas . "</table>";


//if ($clienteId == '') {
//    echo "<script>window.location='../';</script>";
//}
//require('clases/clientes.class.php');
//$objCliente = new clientes();
?>

<style>
    
    .nicEdit-main{        
        background-color: #FFFFFF;       
    }
    
</style>


<!-- PAGE LOAD -->
<script type="text/javascript">

    bkLib.onDomLoaded(function() { 
         new nicEditor({maxHeight : 350, buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul', 'subscript','superscript','strikethrough','removeformat','indent','outdent','fontSize','fontFormat']}).panelInstance('tEditorTexto');         
         new nicEditor({buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul', 'subscript','superscript','strikethrough','removeformat','indent','outdent','fontSize','fontFormat']}).panelInstance('tEditorPregunta');
         new nicEditor({buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul', 'subscript','superscript','strikethrough','removeformat','indent','outdent','fontSize','fontFormat']}).panelInstance('tEditorRespuestaPositiva');
         new nicEditor({buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul', 'subscript','superscript','strikethrough','removeformat','indent','outdent','fontSize','fontFormat']}).panelInstance('tEditorRespuestaNegativa');
    });

    $(document).ready(function(){
                        
        window.scrollTo(0,0);
        
        $("#divPrecarga1").css("display","inline");
        $("#divPrecarga2").css("display","inline");
        $("#divPrecarga3").css("display","inline");   
        
               
        $( "#componentesCapsula" ).sortable({
         update: function(){            
            agregarNumPregunta();
         }    
        });

        $(':checkbox').bind('change', function() {
            var thisClass = $(this).attr('class');
            if ($(this).attr('checked')) {
                $(':checkbox.' + thisClass + ":not(#" + this.id + ")").removeAttr('checked');
            }
            else {
                $(this).attr('checked', 'checked');
            }
        });

        $(":file").filestyle({
            input:false,
            classButton: "btn",
            buttonText: "Examinar..."
        });

        // Smart Wizard
        $('#wizard').smartWizard(
        {   labelNext:"Siguiente",
            transitionEffect: "fade", //none/fade/slide/slideleft
            keyNavigation: 0,            
            labelPrevious:"Anterior",
            labelFinish:"Finalizar"});

        function onFinishCallback(){
            $('#wizard').smartWizard('showMessEage','Finish Clicked');
        }
        // Smart Wizard End


        // Galería
        $('#ulImagenes li').bind('click',function(e){
            var count = $(this).attr('rel');
            showImage(parseInt(count)-1);
        });
        // Galería End



        $('#aTexto').click(function() {

            $("#contenidoTextoId").attr('value', '0');
            $("#overlay").css('filter', 'alpha(opacity=50)');
            
            $("#lbTextoEditor").css('display', 'none'); 

            /*$('#menuOpciones').click();*/
            $('#overlay').fadeIn();
            $('#divTexto').css({visibility: "visible", zIndex:"2000"}).animate({opacity: 1}, 500);
            //$('#divTexto').fadeIn();
            
            nicEditors.findEditor('tEditorTexto').setContent('');  
                                                            
            return false;
        });

        $('#aImagen').click(function() {
            $("#contenidoImagenId").attr('value', '0');
            $("#overlay").css('filter', 'alpha(opacity=50)');
            /*$('#menuOpciones').click();*/
            $('#overlay').fadeIn();

            $('#ulImagenes li').each(function(index, elemento){
                $(elemento).attr("class","");
            });

            $('#divVistaPrevia img').each(function(index, elemento){
                $(elemento).css("display", "none");
            });
                        
            $('#lbImagen').css("display", "none");            
            $('#imagenPie').attr("value","");
            $('#divImagen').css({visibility: "visible", zIndex:"2000"}).animate({opacity: 1}, 500);
            //$('#divImagen').fadeIn();

            return false;
        });

        $('#aPregunta').click(function() {
            
            var capsulaTipo = $("#capsulaTipo").val();
            
            if(capsulaTipo == 1){
                $("#tdTituloAlternativa").html("<br>Alternativas (marque la alternativa correcta):<label id=\"lbAlternativas\" style=\"display:none\">*</label>")
            }
            else{
                $("#tdTituloAlternativa").html("<br>Alternativas:<label id=\"lbAlternativas\" style=\"display:none\">*</label>")
            }
                                    
            
            $("#preguntaId").attr('value', '0');
            $("#overlay").css('filter', 'alpha(opacity=50)');
            /*$('#menuOpciones').click();*/
            $('#overlay').fadeIn();
            $('#divPregunta').css({visibility: "visible", zIndex:"2000"}).animate({opacity: 1}, 500);
            //$('#divPregunta').fadeIn();
            
            
            $("#lbPregunta").css('display', 'none');
            $("#lbPregunta2").css('display', 'none');
            $("#lbRespuestaPositiva").css('display', 'none');
            $("#lbRespuestaPositiva2").css('display', 'none');
            $("#lbRespuestaNegativa").css('display', 'none');
            $("#lbRespuestaNegativa2").css('display', 'none');           

            document.getElementById('divPreguntaScroll').scrollTop = 0;

            nicEditors.findEditor('tEditorPregunta').setContent('');
            nicEditors.findEditor('tEditorRespuestaPositiva').setContent('');
            nicEditors.findEditor('tEditorRespuestaNegativa').setContent('');

            $('#tdAlternativas').html(limpiarAlternativas(capsulaTipo));

            aplicarPlaceHolder();

            return false;

        });
         
        $('#aComentario').click(function() {

            $("#contenidoComentarioId").attr('value', '0');
            $("#overlay").css('filter', 'alpha(opacity=50)');
                        
            $('#comentarioTitulo').attr("value", "");
            $('#tdComentarioTitulo').html("");
            $('#lbNo').click();
            
            $('#overlay').fadeIn();
            $('#divComentario').css({visibility: "visible", zIndex:"2000"}).animate({opacity: 1}, 500);            
            return false;
        });
           
        $('#aEmail').click(function() {            
            $('#divEmails').fadeOut();
            $('#divEmail').fadeIn()            
            return false;
        });

        $('#aEmails').click(function() {            
            //$('#divEmail').fadeOut()
            //$('#divEmails').fadeIn();                       
                        
            return false;
        });
        
        $('.cerrarPopUp').click(function() {
            $("#overlay").css('filter', 'alpha(opacity=0)');            
            $('#overlay').fadeOut();
            $('#divTexto').css({visibility: "invisible", zIndex:"0"}).animate({opacity: 0}, 500);
            $('#divImagen').css({visibility: "invisible", zIndex:"0"}).animate({opacity: 0}, 500);
            $('#divPregunta').css({visibility: "invisible", zIndex:"0"}).animate({opacity: 0}, 500);
            $('#divComentario').css({visibility: "invisible", zIndex:"0"}).animate({opacity: 0}, 500);
            $(".stepContainer").css("height", $("#step-2").height() + 24 + "px");            
            return false;
        });
          
        $('.buttonFinish').click(function() {           
            window.location.href = '../capsulas/adm_capsulas.php';

        });
        
        $('#btnCancelarTema').click(function() {            
            $('#mBoxContainerTema').fadeOut();                                    
        });
        
        $( "#divTexto").css("top","40px");
        $( "#divImagen").css("top","40px");
        $( "#divPregunta").css("top","40px");
        
        $( "#container").css("opacity","1");
        $( "#container").css("filter","alpha(opacity=1)");
        
        
        $(".cb-enable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-disable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', true);
            $( "#lbComentarioTitulo").css("display","");            
        });
        
        $(".cb-disable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-enable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', false);
            $( "#lbComentarioTitulo").css("display","none");
        });
        
        agregarNumPregunta();
        
    });


</script>

<input type="hidden" name="clienteId" id="clienteId" value="<?php echo htmlentities($clienteId) ?>"/>


<input id="capsulaId" type="hidden" value="<? echo $capsulaId; ?>"/>
<input id="capsulaVersion" type="hidden" value="<? echo $capsulaVersion; ?>"/>
<input id="capsulaEstado" type="hidden" value="<? echo $capsulaEstado; ?>"/>
<input id="capsulaValida" type="hidden" value=""/>
<input id="temaId" type="hidden" value="<? echo $temaId; ?>"/>


<div id="container"  style="min-height: 500px; width: 1290px; opacity:0; filter:alpha(opacity=0);">

    <br>

    <div id="overlay"></div>


    <div id="divPrecarga1" style="top:1000px">
        
        
        <!-- Texto -->
        <div id="divTexto">

            <input id="contenidoTextoId" type="hidden" value="0">
            <table class="tablaEstructura">
                <tr>
                    <td valign="top" style="border-bottom: 1px solid #CDCDCD; font-size: 14px; height: 20px">
                        <img src="../css/imagenes/asistente/texto.png" height="20" width="20">&nbsp;<b>Contenido: Texto</b>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <br>Ingrese el texto aquí:
                    </td>
                </tr>
                <tr style="height: 400px">
                    <td>                        
                        <input id="estadoEditorTexto" type="hidden" value="0">
                        <textarea id="tEditorTexto" style="width: 990px; height: 350px"></textarea>                        
                    </td>
                </tr>
                <tr>
                    <td valign="bottom" align="right">
                        <label id="lbTextoEditor" style="display:none">Ha superado la cantidad de caractéres válidos.</label>
                        <input type="button" class="btn cerrarPopUp" value="Cancelar"> &nbsp
                        <input type="button" class="btn cafe" value="Guardar" onClick="capGuardarTexto()">
                    </td>
                </tr>
            </table>

        </div>


        <!-- Imagenes -->
        <div id="divImagen">

            <input id="contenidoImagenId" type="hidden" value="0">
            <table class="tablaEstructura">
                <tr>
                    <td valign="top" style="border-bottom: 1px solid #CDCDCD; font-size: 14px; height: 20px" colspan="2">
                        <img src="../css/imagenes/asistente/imagenes.png" height="20" width="20">&nbsp;<b>Contenido: Imágenes</b>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="height:120px">
                        <br>
                        Seleccione una imagen:
                        <br><br>
    <!--                    <input id="imagenArchivo" type="file" name="archivos[]"  class="filestyle" onchange="subirImagen();"/>-->
                        <form>
                            <input type="file" name="datafile" onChange="subirImagen(this.form,'funcionSubirImagen.php'); return false;"/></br>
                            <input id="capsulaIdForm" name="capsulaId" type="hidden">
                            <input id="capsulaVersionForm" name="capsulaVersion" type="hidden">
                        </form>

                        <div id="imagenCargada">

                        </div>

                    </td>
                    <td valign="top" align="center" rowspan="2" style="width:66%; height:200px">
                        <br>
                        Vista previa
                        <br><br>
                        <div id="divVistaPrevia">
                            <? echo $htmlDivVistaPrevia; ?>                                
                        </div>
                        <br>
                        <input id="imagenPie" type="text" class="inputText" placeHolder="Comentario..." style="width:650px" maxLength="500"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Imágenes disponibles por cápsula:

                        <br><br>

                        <div id="divImagenesDisponibles">
                            <ul id="ulImagenes">
                                <? echo $htmlUlImagenes; ?>
                            </ul>
                        </div>                        
                        <a href="#" onClick="eliminarImagen()">Eliminar</a>                        
                    </td>
                </tr>
                <tr>                    
                    <td valign="top" align="right" colspan="2">
                        <label id="lbImagen" style="margin-right:380px; display:none">Seleccione una imagen.</label>
                        <input type="button" class="btn cerrarPopUp" value="Cancelar"> &nbsp
                        <input type="button" class="btn cafe" value="Guardar" onClick="capGuardarImagen()">
                    </td>
                </tr>
            </table>

        </div>


        <!-- Pregunta -->
        <div id="divPregunta">

            <input id="preguntaId" type="hidden" value="0">
            <table class="tablaEstructura">
                <tr>
                    <td valign="top" style="border-bottom: 1px solid #CDCDCD; font-size: 14px; height: 20px">
                        <img src="../css/imagenes/asistente/preguntas.png" height="20" width="20">&nbsp;<b>Contenido: Pregunta</b>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div id="divPreguntaScroll" style="height:420px; width:1095px; overflow-y: scroll; ">
                            <table>
                                <tr>
                                    <td style="height: 15px">
                                        <br>Pregunta:<label id="lbPregunta" style="display:none">*</label><label id="lbPregunta2" style="display:none">Ha superado la cantidad de caractéres válidos.</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 100px">
                                        <input id="estadoEditorPregunta" type="hidden" value="0">
                                        <textarea id="tEditorPregunta" style="width: 980px"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdTituloAlternativa" style="height: 15px">
                                        <br>Alternativas (marque la alternativa correcta):<label id="lbAlternativas" style="display:none">*</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="tdAlternativas" style="height: 130px" valign="top">
                                        <table id="tablaAlternativas" cellpadding="0" cellspacing="0">
                                            <tr><td><input type="radio" name="correcta"/></td><td>a.- </td><td style="width:1000px"><input type="text" class="inputText" placeHolder="Texto de alternativa a" style="width:980px" maxLength="1000"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                                            <tr><td><input type="radio" name="correcta"/></td><td>b.- </td><td style="width:1000px"><input type="text" class="inputText" placeHolder="Texto de alternativa b" style="width:980px" maxLength="1000"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                                            <tr class="trAlternativa"><td><input type="radio" name="correcta"/><td>c.- </td><td style="width:1000px"><input type="text" class="inputText" placeHolder="Texto de alternativa c" style="width:980px" maxLength="1000" onFocus="agregarAlternativa(this)" onBlur="agregarAlternativaB(this)"/></td><td><img src="../css/imagenes/asistente/eliminar.png" onClick="eliminarAlternativa(this)"/></td></tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="trCuestionario">
                                    <td style="height: 15px">
                                        <br>Respuesta positiva:<label id="lbRespuestaPositiva" style="display:none">*</label><label id="lbRespuestaPositiva2" style="display:none">Ha superado la cantidad de caractéres válidos.</label>
                                    </td>
                                </tr>
                                <tr class="trCuestionario">
                                    <td style="height: 100px">
                                        <textarea id="tEditorRespuestaPositiva" style="width: 990px"></textarea>
                                    </td>
                                </tr>
                                <tr class="trCuestionario">
                                    <td style="height: 15px">
                                        <br>Respuesta Negativa:<label id="lbRespuestaNegativa" style="display:none">*</label><label id="lbRespuestaNegativa2" style="display:none">Ha superado la cantidad de caractéres válidos.</label>
                                    </td>
                                </tr>
                                <tr class="trCuestionario">
                                    <td style="height: 100px">
                                        <textarea id="tEditorRespuestaNegativa" style="width: 990px"></textarea>
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

        </div>
        
        
        <!-- Comentario -->
        <div id="divComentario">

            <input id="contenidoComentarioId" type="hidden" value="0">
            <table class="tablaEstructura">
                <tr>
                    <td valign="top" style="border-bottom: 1px solid #CDCDCD; font-size: 14px; height: 20px" colspan="2">
                        <img src="../css/imagenes/asistente/comentario.png" height="20" width="20">&nbsp;<b>Contenido: Comentario</b>
                    </td>                    
                </tr>
                <tr>
                    <td valign="middle" width="80px">
                        Título
                    </td>
                    <td valign="middle">
                        <input id="comentarioTitulo" type="text" class="inputText" maxLength="500" placeHolder="Ingrese el título del comentario aquí..." onkeyup="comentarioTitulo()" style="width:650px" />
                    </td>
                </tr>                
                <tr>
                    <td valign="midlle">
                        Obligatorio
                    </td>
                    <td valign="midlle" align="left">
                        
                        <p class="field switch">
                            <label id="lbSi" class="cb-enable"><span>Si</span></label>
                            <label id="lbNo" class="cb-disable selected"><span>No</span></label>
                            <input type="checkbox" id="chbComentario" class="checkbox" value="1"/>
                        </p>
                        
                    </td>                    
                </tr>
                <tr>
                    <td colspan="2">                        
                        Vista previa
                    </td>
                </tr>
                <tr>
                    <td style="height:210px; background-color: #FFFFFF; padding: 2px" colspan="2" valign="top">
                        
                        <table style="width:970px; margin: 0 auto; margin-top: 10px">                            
                            <tr>
                                <td width="5px" height="18px" valign="top">
                                    <label id="lbComentarioTitulo" title="Este campo es obligatorio" style="display:none">*</label>
                                </td>
                                <td id="tdComentarioTitulo">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td align="center" colspan="2">  
                                    <div class="divComentarioTexto"></div>
                                    <br>
                                </td>
                            </tr>
                        </table>
                                                                        
                    </td>                    
                </tr>                    
                <tr>
                    <td valign="bottom" align="right" colspan="2">
                        <input type="button" class="btn cerrarPopUp" value="Cancelar"> &nbsp
                        <input type="button" class="btn cafe" value="Guardar" onClick="capGuardarComentario()">
                    </td>
                </tr>
            </table>

        </div>

    </div>


    <!-- Smart Wizard -->
    <div id="wizard" class="swMain">

        <ul>
            <li><a href="#step-1">
                    <span class="stepNumber">1</span>
                    <span class="stepDesc">
                        Datos Generales<br />
                    </span>
                </a></li>
            <li><a href="#step-2">
                    <span class="stepNumber">2</span>
                    <span class="stepDesc">
                        Contenidos<br />
                    </span>
                </a></li>
            <li><a href="#step-3">
                    <span class="stepNumber">3</span>
                    <span class="stepDesc">
                        Acciones<br />
                    </span>
                </a></li>
            <li>

            </li>
        </ul>
        <br><a href="#" onclick="location.href = '../capsulas/listado_capsulas.php'" style="float:right;" class="volver">Volver</a>                

        <div id="step-1">
            <h2 class="StepTitle">Introducción</h2>

            <br>

            <p> Bienvenido al módulo de Cápsulas, donde podrá realizar su creación, búsqueda, edición, anulación/activación, envío y carga de cápsulas en el sistema. <br>
                Esta herramienta permite comunicar conceptos claves respecto de un área del conocimiento, sin la necesidad de requerir que el destinatario
                deba asistir a una instancia presencial, bajo la modalidad de: </p>

            <ul class="lista">
                <li><font>Cuestionario</font>: preguntas y sus posibles respuestas. </li>
                <li><font>Encuesta</font>: preguntas, posibles respuestas y un campo (optativo) para el ingreso de observaciones por el encuestado.</li>
                <li><font>Contenido</font>: entrega de material de lectura y estudio.</li>
            </ul>

            A continuación, complete los campos: <label id="lbPaso1" style="display:none">Complete los valores solicitados.</label>

            <div style="margin-left: 520px">

                <div id ="mBoxContainerTema" class="BoxContainer" style="position: absolute; display:none; width:350px;" height="auto">

                    <div class="BoxTitle">Temas</div>    

                    <div id="BoxContentTema" class="BoxContent " style="width: auto; font-size: 11px;">     

                        <? echo $htmlTemas; ?>

                    </div>

                    <div class="BoxFooterContainer" align="right">

                        <input id="btnCancelarTema" type="button" name="button" class="btn" value="Cancelar" />   
                        <input type="button" name="button" class="btn verde" value="Seleccionar" onclick="SeleccionarTema()" />   

                    </div>

                </div>

            </div>

            <br><br>                        

            <table class="tablaFormulario">
                <tr>
                    <td>
                        Tipo:
                    </td>
                    <td>
                        <select id="capsulaTipo" <?
                        if ($capsulaId != 0) {
                            echo "disabled";
                        }
                        ?>>
                            <option value="1" <?
                                if ($capsulaTipo == 1) {
                                    echo "selected";
                                }
                        ?>>Cuestionario </option>
                            <option value="2" <?
                                    if ($capsulaTipo == 2) {
                                        echo "selected";
                                    }
                        ?>>Encuesta </option>
                            <option value="3" <?
                                    if ($capsulaTipo == 3) {
                                        echo "selected";
                                    }
                        ?>>Contenido </option>
                        </select>                        
                    </td>                    
                </tr>
                <tr>
                    <td width="100px">
                        Tema:
                    </td>
                    <td>
                        <input id="temaNombre" type="input" style="width:360px" onBlur="validarTemaNombreOnBlur()" maxLength="50" value="<? echo htmlentities($temaNombre) ?>" <?
                                    if ($capsulaId != 0) {
                                        echo "disabled";
                                    }
                        ?>/> <img id="lupaEva" src='../skins/saam/img/lupa.png' style="cursor: pointer; display:<?
                               if ($capsulaId != 0) {
                                   echo 'none';
                               } else {
                                   echo '';
                               }
                        ?>;" onclick="SeleccionarTemas()"/><label id="lbTema" style="display:none">*</label><label id="lbTemaId" style="display:none">El tema seleccionado no existe.</label>

                    </td>
                </tr>                
                <tr>
                    <td>
                        Cápsula:
                    </td>
                    <td>
                        <input id="capsulaNombre" type="input" style="width:360px" onBlur="validarCapsulaNombreOnBlur()" maxLength="50" value="<? echo htmlentities($capsulaNombre) ?>"/> <label id="lbCapsula" style="display:none">*</label><label id="lbCapsulaId" style="display:none">La cápsula ingresada ya está asociada al tema.</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        N° Cápsula:
                    </td>
                    <td>
                        <input id="capsulaNumero" type="input" style="width:30px" maxLength="3" value="<? echo htmlentities($capsulaNumero) ?>"/> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Descripción:
                    </td>
                    <td>
                        <input id="capsulaDescripcion" type="input" style="width:700px" maxLength="250" value="<? echo htmlentities($capsulaDescripcion) ?>"/>
                    </td>
                </tr>
            </table>
        </div>

        <div id="step-2" style="heigth:auto">

            <div id="divPrecarga2" style="display:none">
                <h2 id="h2Step2" class="StepTitle">Ingrese los contenidos</h2>

                <br>

                <div id="fl_menu">
                    <div class="label"><img src="../css/imagenes/asistente/menu.png" alt="O" height="40" width="40"></div>
                    <div id="menuOpciones" class="menu">
                        <a id="aTexto" href="#" class="menu_item" title="Texto"><img src="../css/imagenes/asistente/texto.png" height="40" width="40"></a>
                        <a id="aImagen" href="#" class="menu_item" title="Imágenes"><img src="../css/imagenes/asistente/imagenes.png" height="40" width="40"></a>                        
                        <a id="aPregunta" href="#" class="menu_item" title="Preguntas"><img src="../css/imagenes/asistente/preguntas.png" height="40" width="40"></a>
                        <a id="aComentario" href="#" class="menu_item" title="Comentario"><img src="../css/imagenes/asistente/comentario.png" height="40" width="40"></a>
                    </div>
                </div>

                <center>

                    <div style="background: #FFFFFF; width:1100px; padding: 10px; margin-bottom: 20px">

                        <table class="tablaTema" CELLSPACING='0' CELLSPADDING='0'>
                            <tr>
                                <td id="tdCabeceraTema1" class="tdCabeceraTemaA">
                                    <span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                                </td>
                                <td id="tdCabeceraTema2" style='display:none;' class="tdCabeceraTemaB">
                                    <img id="temaUrl" src='' border='0' height='150px' width="700px">
                                </td>
                                <td style='width:250px;border:1px solid #4B6C9F;' align='center'>
                                    <img src='../css/imagenes/headerFooter/logo.png' border='0'>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 100px" colspan="2" align="left">
                                    <h3>Bienvenido(a)</h3>
                                    <b>Nombre de usuario</b>
                                </td>
                            </tr>
                            <tr>
                                <td id="tdTituloCapsula" align="center" colspan="2">
                                    <h1>Nombre CAPSULA</h1>
                                </td>
                            </tr>
                        </table>

                        <br/>

                        <div style="background-color: #FFFFFF">

                            <ul id="componentesCapsula">
                                <? include 'funcionSeleccionarContenidosCapsula.php'; ?>
                            </ul>

                        </div>

                    </div>

                </center>

            </div>

        </div>

        <div id="step-3">

            <div id="divPrecarga3" style="display:none">

                <h2 class="StepTitle">Seleccione </h2>
                <br>

                <table>
                    <tr>
                        <td colspan="7">
                            <p><b>Usted ha editado exitósamente la cápsula.</b></p>
                            <h1 id="h1TituloCapsula"></h1>
                            <p>Ahora puede realizar las siguientes acciones:</p>
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td width="100px"></td>
                        <td width="70px">
                            <a id="aEmail" href="#" title="Email"><img width="50" height="50" src="../css/imagenes/asistente/email.png"></a>
                        </td>
                        <td width="350px" valign="top">
                            <b>Envío de prueba</b><br>
                            Realizar un envío de prueba <br>de la cápsula recién creada.

                        </td>
                        <td width="70px">
                            <a id="aEmails" href="#" title="Email"><img width="50" height="50" src="../css/imagenes/asistente/emails.png" onClick="enviarMails()"></a>
                        </td>
                        <td width="350px" valign="top">
                            <b>Envío </b><br>
                            Realizar el envío <br> de la cápsula recién creada.
                        </td>
                        <td width="70px">
                            <a href="capAsistente.php" title="Cápsula"><img width="50" height="50" src="../css/imagenes/asistente/nuevaCap.png"></a>
                        </td>
                        <td width="350px" valign="top">
                            <b>Nueva </b><br>
                            Crear nueva cápsula.
                        </td>

                    </tr>   
                    <tr>
                        <td colspan="7"><br><br></td>
                    </tr>
                    <tr>
                        <td width="100px"></td>
                        <td colspan="6">

                            <div id="divEmail" style="display:none; width:600px;">                           
                                Ingrese un email válido: <br><br><input id="emailPrueba" type="input" style="width:250px" maxLength="50" class="inputText"/> &nbsp;<label id="lbEmail" style="display:none">*</label>
                                <br>
                                <br>

                                <input type="button" class="btn cafe" value="Enviar" onClick="capEnviarEmailPrueba()">

                            </div>

                            <div id="divEmails" style="display:none;width:980px; border:1px solid #CDCDCD; padding: 10px">

                                <form id="frmEnvioPrueba" method="post" action="../capsulas/envio_capsula_1.php">
                                    <input id="inputFrmCapsulaNombre" name="capsulaNombre"/>
                                    <input id="inputFrmTema" name="temaNombre"/>
                                    <input id="inputFrmEnvioPrueba" type="submit">
                                </form>


                            </div>

                            <div id="divEnviando" style="display:none; padding-left: 450px;">                                
                                <table>
                                    <tr>
                                        <td valign="middle">
                                            <b><i>Enviando</i></b>
                                        </td>
                                        <td>
                                            <img src="../css/imagenes/asistente/cargando.gif" alt="O" height="14" width="14">
                                        </td>
                                    </tr>                                
                                </table>                                                                                        
                            </div>

                            <div id="divResultadoEnvio" style="padding-left: 400px; width:600px;">    

                            </div>

                        </td>

                    </tr>
                </table>

            </div>

        </div>

    </div>

    <br>
    <br>
    <!-- End SmartWizard Content -->

</div>

<br>
<br>
<br>




<!-- MENU -->
<script>

    //config
    $float_speed = 0; //milliseconds
    $float_easing = "linear";  //"easeOutExpo";

    //cache vars
    $fl_menu = $("#fl_menu");
    $fl_menu_menu = $("#fl_menu .menu");
    $fl_menu_label = $("#fl_menu .label");

    $(window).load(function() {
        /*menuPosition=$('#fl_menu').position().top;*/

        menuPosition = -175;

        FloatMenu();

        $fl_menu.click(function(){

            var styleValor = document.getElementById("menuOpciones").style.display;

            if(styleValor == "" || styleValor == "none"){
                $fl_menu_label.fadeTo(0, 1);
                $fl_menu_menu.fadeIn(0);
            }
            else{
                $fl_menu_label.fadeTo(0, 0.55);   /* (velocidad, opacidad) */
                $fl_menu_menu.fadeOut(0);
            }
        });
    });

    $(window).scroll(function () {
        FloatMenu();
    });

    function FloatMenu(){
        var scrollAmount = $(document).scrollTop();

        if(window['menuPosition'] != null){

            var newPosition = menuPosition + scrollAmount;

            if($(window).height() < $fl_menu.height() + $fl_menu_menu.height()){
                $fl_menu.css("top",menuPosition);
            } else {
                $fl_menu.stop().animate({top: newPosition}, $float_speed, $float_easing);
            }
        }
    }

</script>


<!--AUTOCOMPLETE-->
<script>
    $(function() {
        $( "#temaNombre" ).autocomplete({
            source: "funcionSeleccionarTemas.php",
            minLength: 1,
            select: function(event, ui) {
                //var temaId = ui.item.temaId;
                //$('#temaId').attr("value", temaId);
            },
            messages: {
                noResults: '',
                results: function() {}
            }
        });
    });
</script>




<?
$plantilla->setTemplate("asistentePie");
echo $plantilla->show();
?>




