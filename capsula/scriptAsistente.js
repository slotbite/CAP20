 /*** OBJETOS ***/


var currentImage;
var currentIndex = -1;
var interval;

function showImage(index) {
    if (index < $('#divVistaPrevia img').length) {
        var indexImage = $('#divVistaPrevia img')[index]
        if (currentImage) {
            if (currentImage != indexImage) {
                $(currentImage).css('z-index', 2);
                $(currentImage).css({
                    'display': 'none',
                    'z-index': 1
                });
            //                    $(currentImage).fadeOut(0, function() {
            //                        $(this).css({'display':'none','z-index':1})
            //                    });
            }
        }

        //$(indexImage).css({'display':'block', 'opacity':1});
        $(indexImage).fadeIn();
        
        currentImage = indexImage;
        currentIndex = index;
        $('#ulImagenes li').removeClass('active');
        $($('#ulImagenes li')[index]).addClass('active');
    }
}


function showNext() {
    var len = $('#divVistaPrevia img').length;
    var next = currentIndex < (len - 1) ? currentIndex + 1 : 0;
    showImage(next);
}


function subirImagen(form, action_url) {
    
    $('#imagenCargada').html("<img src='../css/imagenes/asistente/cargando.gif'><i>Cargando</i>");
    
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    
    $('#capsulaIdForm').attr("value", capsulaId);
    $('#capsulaVersionForm').attr("value", capsulaVersion);

    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");

    // Add to document...
    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";
    
    iframeId = document.getElementById("upload_iframe");

    // Add event...
    var eventHandler = function() {
        
        if (iframeId.detachEvent)
            iframeId.detachEvent("onload", eventHandler);
        else
            iframeId.removeEventListener("load", eventHandler, false);

        // Message from server...
        if (iframeId.contentDocument) {
            content = iframeId.contentDocument.body.innerHTML;
        } else if (iframeId.contentWindow) {
            content = iframeId.contentWindow.document.body.innerHTML;
        } else if (iframeId.document) {
            content = iframeId.document.body.innerHTML;
        }

        // Del the iframe...
        setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
    }
    
    if (iframeId.addEventListener)
        iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent)
        iframeId.attachEvent("onload", eventHandler);

    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");

    // Submit the form...
    form.submit();
}


function retornarAbc(posicion) {
    var a = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "ñ", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
    var letra = "";
    
    if (posicion <= 27) {
        letra = a[posicion];
    }
    
    return letra;
}





function agregarAlternativaB(obj) {
    //var myClass = $(obj).attr("class");
    var totalFilas = $('#tablaAlternativas >tbody >tr').length;
    var tr = $(obj).parents('tr').first();
    var trIndex = ($(tr).index() + 1);
    var inputValor = "";
    inputValor = $($(tr).find("td")[2]).find('input').val();
    
    if (trIndex > 2) {
        if (totalFilas == trIndex) {
            if (inputValor == "") {
                $(tr).addClass("trAlternativa");
                $(tr.find("td")[0]).find('input').prop("checked", false);
                $(tr.find("td")[0]).find('input').prop("disabled", true);
            
            } 
            else {
                var $tr = $('#tablaAlternativas').find("tbody tr:last").clone();
                $tr.find("td")[1].innerHTML = retornarAbc(trIndex) + ")";
                $($tr.find("td")[2]).find('input').attr("placeHolder", "Texto de alternativa " + retornarAbc(trIndex)).attr("value", "");
                $tr.addClass("trAlternativa");
                $($tr.find("td")[0]).find('input').prop("checked", false);
                $($tr.find("td")[0]).find('input').prop("disabled", true);
                $('#tablaAlternativas').find("tbody tr:last").after($tr);
                aplicarPlaceHolder();
            }
        } 
        else {
            if ($('#tablaAlternativas >tbody >tr')[trIndex] != undefined && $($('#tablaAlternativas >tbody >tr')[trIndex]).attr("class") == "trAlternativa" && inputValor == "") {
                $($('#tablaAlternativas >tbody >tr')[trIndex]).remove();
                $(tr).addClass("trAlternativa");
                $(tr.find("td")[0]).find('input').prop("checked", false);
                $(tr.find("td")[0]).find('input').prop("disabled", true);
            }
        }
    }
}
function agregarAlternativa(obj) {
    //var myClass = $(obj).attr("class");
    var tr = $(obj).parents('tr').first();
    $(tr).removeClass("trAlternativa").addClass("");
    $(tr.find("td")[0]).find('input').prop("disabled", false);
}

function eliminarAlternativa(obj) {
    var tr = $(obj).parents('tr').first();
    var trClass = $(tr).attr("class");
    var tablaFilas = $('#tablaAlternativas >tbody >tr').length;
    
    if (trClass != "trAlternativa" && tablaFilas > 3) {
        var trIndex = $(tr).index();
        $($('#tablaAlternativas >tbody >tr')[trIndex]).remove();
        tablaFilas = $('#tablaAlternativas >tbody >tr').length;
        
        for (var i = 0; i < tablaFilas; i++) {
            $('#tablaAlternativas').children().children()[i].children[1].innerHTML = retornarAbc(i) + ")";
        }
    }
}



function eliminarPregunta(obj) {
    var tr = $(obj).parents('tr').first();
    var trClass = $(tr).attr("class");
    var tablaFilas = $('#tablaFeedback >tbody >tr').length;
    
    if (trClass != "trPregunta" && tablaFilas > 3) {
        var trIndex = $(tr).index();
        $($('#tablaFeedback >tbody >tr')[trIndex]).remove();
        tablaFilas = $('#tablaFeedback >tbody >tr').length;
        
        for (var i = 0; i < tablaFilas; i++) {
            $('#tablaFeedback').children().children()[i].children[1].innerHTML = retornarAbc(i) + ")";
        }
    }
}
function agregarPregunta(obj) {
    //var myClass = $(obj).attr("class");
    var tr = $(obj).parents('tr').first();
    $(tr).removeClass("trPregunta").addClass("");
}

function agregarPreguntaB(obj) {
    //var myClass = $(obj).attr("class");
    var totalFilas = $('#tablaFeedback >tbody >tr').length;
    var tr = $(obj).parents('tr').first();
    var trIndex = ($(tr).index() + 1);
    var inputValor = "";
    inputValor = $($(tr).find("td")[2]).find('input').val();
    
    if (trIndex > 2) {
        if (totalFilas == trIndex) {
            if (inputValor == "") {
                $(tr).addClass("trPregunta");
            } else {
                var $tr = $('#tablaFeedback').find("tbody tr:last").clone();
                $tr.find("td")[1].innerHTML = retornarAbc(trIndex) + ")";
                $($tr.find("td")[2]).find('input').attr("placeHolder", "Escribe tu pregunta aquí.").attr("value", "");
                ;
                $tr.addClass("trPregunta");
                $('#tablaFeedback').find("tbody tr:last").after($tr);
                aplicarPlaceHolder();
            }
        } 
        else {
            if ($('#tablaFeedback >tbody >tr')[trIndex] != undefined && $($('#tablaFeedback >tbody >tr')[trIndex]).attr("class") == "trPregunta" && inputValor == "") {
                $($('#tablaFeedback >tbody >tr')[trIndex]).remove();
                $(tr).addClass("trPregunta");
            
            }
        }
    }
}




function validarPaso1() {
    
    var estado = "";
    var caso = 0;
    
    var capsulaTipo = $('#capsulaTipo').val();
    
    if (capsulaTipo == 3) {
        $('#aPregunta').css('display', 'none');
        $('#aFeedback').css('display', 'none');
    } 
    else {
        $('#aPregunta').css('display', '');
        
        if (capsulaTipo == 1) {
            $('.trCuestionario').css('display', '');
            $('#aFeedback').css('display', 'none');
        } 
        else {
            $('.trCuestionario').css('display', 'none');
        }
    
    }
    
    seleccionarTemaId();
    
    if (validarTemaNombre() == "") {
        caso++;
    }
    
    if (validarCapsulaNombre() == "") {
        caso++;
    }
    
    if (caso > 0) {
        $('#lbPaso1').css("display", "");
    } else {
        
        $('#lbPaso1').css("display", "none");
        var capsulaNombre = $('#capsulaNombre').val();
        var capsulaNumero = $('#capsulaNumero').val();
        
        var titulo = "";
        
        if (capsulaNumero.trim() != "") {
            titulo = capsulaNumero + ".- " + capsulaNombre;
        } 
        else {
            titulo = capsulaNombre;
        }
        
        $('#tdTituloCapsula').html("<h1>" + titulo.trim() + "</h1>");
        $('#h1TituloCapsula').html(titulo.trim());
        
        guardarCabeceraCapsula();
        
        var capsulaValida = $('#capsulaValida').val();
        
        if (capsulaValida.trim() == "1") {
            estado = "OK";
            $('#capsulaTipo').prop("disabled", true);
            $('#temaNombre').prop("disabled", true);
        } 
        else {
            
            switch (capsulaValida.trim()) {
                case "2":
                    $('#lbCapsulaId').css("display", "");
                    break;
                
                case "3":
                    $('#lbCapsulaId').css("display", "");
                    break;
                
                case "4":
                    $('#lbTemaId').css("display", "");
                    break;
            
            }
        }
    }
    
    return estado;
}


function validarPaso2() {
    
    $('#h2Step2').html("Ingrese los contenidos <img src=\"../css/imagenes/asistente/cargando.gif\" height=\"14\" width=\"14\" style=\"margin-left:980px\"> &nbsp;<i><b>Guardando</b></i>");
    
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var capsulaTipo = $('#capsulaTipo').val();
    var count = 0;
    var countPregunta = 0;
    var resultado = '';
    
    capLimpiarElementos(capsulaId, capsulaVersion);
    
    $('#componentesCapsula li.ui-state-default').each(function(index, elemento) {
        var id = $(elemento).attr("id");
        var ids = id.split("-");
        
        var elementoTipo = ids[0];
        var capsulaId = ids[1];
        var capsulaVersion = ids[2];
        var contenidoId = 0;
        var preguntaId = 0;
        
        if (elementoTipo == 'pregunta') {
            preguntaId = ids[3];
            countPregunta++;
        } 
        else {
            contenidoId = ids[3];
        }
        
        capGuardarElementos(capsulaId, capsulaVersion, elementoTipo, contenidoId, preguntaId);
        count++;
    
    });
    
    if (count == 0) {
        $('#h2Step2').html("Ingrese los contenidos");
        alert("Debe ingresar elementos a la cápsula.");
    } 
    else {
        if ((capsulaTipo == 1 || capsulaTipo == 2) && countPregunta == 0) {
            $('#h2Step2').html("Ingrese los contenidos");
            alert("Debe ingresar preguntas a la cápsula.");
        } 
        else {
            $('#capsulaEstado').attr("value", "1");
            guardarCabeceraCapsula();
            resultado = 'OK';
        }
    }
    
    $('#h2Step2').html("Ingrese los contenidos");
    return resultado;
}

/*** Validar Tema Nombre ***/
function validarTemaNombre() {
    
    var estado = "";
    var valorTema = $('#temaNombre').val();
    var valorTemaId = $('#temaId').val();
    
    if (valorTema == "" || valorTemaId == 0) {
        if (valorTema == "") {
            $('#lbTema').css("display", "");
            $('#lbTemaId').css("display", "none");
        } 
        else if (valorTema != "" && valorTemaId == 0) {
            $('#lbTema').css("display", "none");
            $('#lbTemaId').css("display", "");
        }
    } 
    else {
        $('#lbTema').css("display", "none");
        $('#lbTemaId').css("display", "none");
        estado = "OK";
    }
    return estado;
}


function validarTemaNombreOnBlur() {
    
    var lbDisplay = $('#lbPaso1').css("display");
    
    seleccionarTemaId();
    
    if (lbDisplay == "" || lbDisplay == "inline") {
        
        var caso = 0;
        
        var valorTema = $('#temaNombre').val();
        var valorTemaId = $('#temaId').val();
        
        if (valorTema == "" || valorTemaId == 0) {
            caso++;
            
            if (valorTema == "") {
                $('#lbTema').css("display", "");
                $('#lbTemaId').css("display", "none");
            } 
            else if (valorTema != "" && valorTemaId == 0) {
                $('#lbTema').css("display", "none");
                $('#lbTemaId').css("display", "");
            }
        } 
        else {
            $('#lbTema').css("display", "none");
            $('#lbTemaId').css("display", "none");
        }
        
        if (validarCapsulaNombre() == "") {
            caso++;
        }
        
        if (caso > 0) {
            $('#lbPaso1').css("display", "");
        } 
        else {
            $('#lbPaso1').css("display", "none");
        }
    }
}

/*** Validar Capsula Nombre ***/
function validarCapsulaNombre() {
    
    var estado = "";
    var valorCapsula = $('#capsulaNombre').val();
    
    if (valorCapsula == "") {
        $('#lbCapsula').css("display", "");
    } 
    else {
        $('#lbCapsula').css("display", "none");
        estado = "OK";
    }
    
    return estado;
}


function validarCapsulaNombreOnBlur() {
    
    var lbDisplay = $('#lbPaso1').css("display");
    $('#lbCapsulaId').css("display", "none");
    
    if (lbDisplay == "" || lbDisplay == "inline") {
        
        var caso = 0;
        
        var valorCapsula = $('#capsulaNombre').val();
        
        if (valorCapsula == "") {
            $('#lbCapsula').css("display", "");
        } 
        else {
            $('#lbCapsula').css("display", "none");
        }
        
        if (validarTemaNombre() == "") {
            caso++;
        }
        
        if (caso > 0) {
            $('#lbPaso1').css("display", "");
        } 
        else {
            $('#lbPaso1').css("display", "none");
        }
    }
}


function habilitarImagenes() {
    //Gestionar con each
    $('#ulImagenes li').each(function(index, elemento) {
        
        if (index == 0) {
            $(elemento).attr("class", "active");
            $(elemento).attr("rel", index + 1);
        } 
        else {
            $(elemento).attr("rel", index + 1);
            $(elemento).attr("class", "");
        }
    
    });
    
    $('#ulImagenes li').bind('click', function(e) {
        var count = $(this).attr('rel');
        showImage(parseInt(count) - 1);
    });

}


function limpiarAlternativas(capsulaTipo) {
    
    var alternativasHTML = "<table id='tablaAlternativas' cellpadding='0' cellspacing='0'>";
    
    if (capsulaTipo == 1) {
        alternativasHTML = alternativasHTML + "<tr><td><input type='radio' name='correcta' checked/></td><td>a) </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa a' style='width:980px' maxLength='1000'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
        alternativasHTML = alternativasHTML + "<tr><td><input type='radio' name='correcta'/></td><td>b) </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa b' style='width:980px' maxLength='1000'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
        alternativasHTML = alternativasHTML + "<tr class='trAlternativa'><td><input type='radio' name='correcta' disabled/><td>c.- </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa c' style='width:980px' maxLength='1000' onFocus='agregarAlternativa(this)' onBlur='agregarAlternativaB(this)'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
    } 
    else {
        alternativasHTML = alternativasHTML + "<tr><td><input type='radio' name='correcta' style='display:none'/></td><td>a) </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa a' style='width:980px' maxLength='1000'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
        alternativasHTML = alternativasHTML + "<tr><td><input type='radio' name='correcta' style='display:none'/></td><td>b) </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa b' style='width:980px' maxLength='1000'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
        alternativasHTML = alternativasHTML + "<tr class='trAlternativa'><td><input type='radio' name='correcta' disabled style='display:none'/><td>c.- </td><td style='width:1000px'><input type='text' class='inputText' placeHolder='Texto de alternativa c' style='width:980px' maxLength='1000' onFocus='agregarAlternativa(this)' onBlur='agregarAlternativaB(this)'/></td><td><img src='../css/imagenes/asistente/eliminar.png' onClick='eliminarAlternativa(this)'/></td></tr>";
    }
    alternativasHTML = alternativasHTML + "</table>";
    
    return alternativasHTML;
}


function capEnviarEmailPrueba() {
    
    var usuarioEmail = $('#emailPrueba').attr("value");
    var resultado = '';
    
    $(".stepContainer").css("height", $("#step-3").height() + 20 + "px");
    
    if (usuarioEmail.trim() != "") {
        $('#lbEmail').css("display", "none");
        $('#divResultadoEnvio').html("");
        
        $('#divEnviando').css("display", "");
        resultado = capEnviarEmail(0, usuarioEmail, 'USUARIO DE PRUEBA', 0, 0, 0, 'prueba');
        
        if (resultado.trim() == '1') {
            $('#divResultadoEnvio').html("<b><i>La cápsula se ha enviado satisfactoriamente</i></b>");
        } 
        else {
            $('#divResultadoEnvio').html("<b><i>Error al enviar la cápsula. Inténtelo más tarde</i></b>");
        }
    } 
    else 
    {
        $('#lbEmail').css("display", "");
    }
    
    $('#divEnviando').css("display", "none");

}


function capEnviarEmail(usuarioId, usuarioEmail, usuarioNombre, organizacionId, sectorId, areaId, caso) {
    
    var resultado = "";
    
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var capsulaNombre = $('#capsulaNombre').val();
    var temaId = $('#temaId').val();
    var temaImagen = $('#temaUrl').val();
    var capsulaTipo = $('#capsulaTipo').val();
    
    var capsulaNumero = $('#capsulaNumero').val();
    
    var titulo = "";
    
    if (capsulaNumero.trim() != "") {
        titulo = capsulaNumero + ".- " + capsulaNombre;
    } 
    else {
        titulo = capsulaNombre;
    }
    
    $.ajax({
        url: 'funcionEnvioEmail',
        type: 'POST',
        data: {
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            capsulaNombre: titulo,
            capsulaTipo: capsulaTipo,
            temaId: temaId,
            temaImagen: temaImagen,
            usuarioId: usuarioId,
            usuarioEmail: usuarioEmail,
            usuarioNombre: usuarioNombre,
            organizacionId: organizacionId,
            sectorId: sectorId,
            areaId: areaId,
            caso: caso
        },
        cache: false,
        async: false,
        dataType: "html"
    }).done(function(data) {
        resultado = data.trim();
    });
    
    return resultado;
}


function SeleccionarTemas() {
    $('#mBoxContainerTema').fadeIn();

}


function SeleccionarTema() {
    
    var nombreTema = '';
    
    $('#tablaListaTema tr').each(function() {
        
        var tdA = $(this).find("td").eq(0);
        var inputA = tdA.find("input")[0];
        
        var select = (inputA.checked) ? 'Si' : 'No';
        
        if (select == 'Si') {
            var tdB = $(this).find("td").eq(1);
            nombreTema = $(tdB).html();
        }
    });
    
    $('#temaNombre').attr("value", nombreTema);
    $('#mBoxContainerTema').fadeOut();

}


function enviarMails() {
    
    var capsulaNombre = $('#capsulaNombre').attr("value");
    var capsulaNumero = $('#capsulaNumero').val();
    
    var titulo = "";
    
    if (capsulaNumero.trim() != "") {
        titulo = capsulaNumero + ".- " + capsulaNombre;
    } 
    else {
        titulo = capsulaNombre;
    }
    
    var temaNombre = $('#temaNombre').attr("value");
    
    $('#inputFrmCapsulaNombre').attr("value", titulo.trim());
    $('#inputFrmTema').attr("value", temaNombre);
    $('#inputFrmEnvioPrueba').click();
}


function eliminarImagen() {
    
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var valorSrc = '';
    
    if (confirm("La siguiente acción no afecta el contenido.\nDesea continuar?")) {
        
        $('#ulImagenes li').each(function(index, elemento) {
            
            var valorClase = $(elemento).attr("class");
            if (valorClase.trim() == "active") {
                
                var img = $(this).find("img").eq(0);
                valorSrc = $(img).attr("src");
                
                $(elemento).remove();
            }
        });
        
        
        $('#divVistaPrevia img').each(function(index, elemento) {
            var valorSrcPrevio = $(elemento).attr("src");
            if (valorSrcPrevio.trim() == valorSrc.trim()) {
                $(elemento).remove();
            }
        });
        
        $.ajax({
            url: 'funcionEliminarImagen',
            type: 'POST',
            data: {
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                capsulaImagenRuta: valorSrc
            },
            cache: false,
            async: false,
            dataType: "html"
        }).done(function(data) {
        });
        
        habilitarImagenes();
    
    }
    
    return false;

}


function comentarioTitulo() {
    
    var titulo = $('#comentarioTitulo').val();
    $('#tdComentarioTitulo').html(titulo);

}

function capEliminarPregunta(preguntaId) {

    if(confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();

        $.ajax({
            url:'funcionEliminarPregunta',
            type:'POST',
            data:{
                preguntaId: preguntaId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion
            },
            cache:false,
            async: false,
            dataType: "json"
        }).done(function(data) {

            });

        var liId = 'pregunta-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + preguntaId.toString();
        $("#" + liId).remove();

        $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    }

    return false;
}
function agregarNumPregunta() {
    
    var num = 0;
    
    $('#componentesCapsula li').each(function(i) {
        
        var id = $(this).attr('id');
        id = id.substring(0, 8);
        
        if (id == 'pregunta') {
            num++;
            var objB = $(this).find(".divContenido").find(".pregunta").find(".bPregunta")
            objB.html("Pregunta " + num.toString() + ":");
        
        }
    
    });

}
function agregarNumPregunta(){
    
    var num = 0;
            
    $('#componentesCapsula li').each(function (i) {

        var id = $(this).attr('id');
        id = id.substring(0,8);

        if(id == 'pregunta'){                    
            num++;
            var objB = $(this).find(".divContenido").find(".pregunta").find(".bPregunta")
            objB.html("Pregunta " + num.toString() + ":");

        }

    });

}
function agregarNumFeedback() {
    
    var num = 0;
    
    $('#componentesCapsula li').each( function( i ) {
        
        var id = $(this).attr('id');
        id = id.substring(0, 8);
        
        if (id == 'feedback') {
            num++;
            var objB = $(this).find(".divContenido").find(".feedback").find(".bFeedback")
            objB.html("Feedback " + num.toString() + ":");
        
        }
    
    });

}

/*** DATOS ***/



function capGuardarElementos(capsulaId, capsulaVersion, elementoTipo, contenidoId, preguntaId) {
    
    $.ajax({
        url: 'funcionGuardarElementos',
        type: 'POST',
        async: false,
        data: {
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            elementoTipo: elementoTipo,
            contenidoId: contenidoId,
            preguntaId: preguntaId
        },
        cache: false,
        dataType: "html"
    }).done(function(data) {
    });
}


function capLimpiarElementos(capsulaId, capsulaVersion) {
    
    $.ajax({
        url: 'funcionLimpiarElementos',
        type: 'POST',
        async: false,
        data: {
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache: false,
        dataType: "html"
    }).done(function(data) {
    });
}


function c(preguntaId) {
    
    if (confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        
        $.ajax({
            url: 'funcionEliminarPregunta',
            type: 'POST',
            data: {
                preguntaId: preguntaId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
        
        });
        
        var liId = 'pregunta-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + preguntaId.toString();
        $("#" + liId).remove();
        
        $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    }
    
    return false;
}


function capEditarPregunta(preguntaId){
    
    $("#overlay").css('filter', 'alpha(opacity=50)');
    /*$('#menuOpciones').click();*/
    $('#overlay').fadeIn();
    $('#divPregunta').css({visibility: "visible",zIndex: "2000"}).animate({opacity: 1}, 500);
    //$('#divPregunta').fadeIn();
    
    $("#lbPregunta").css('display', 'none');
    $("#lbPregunta2").css('display', 'none');
    $("#lbRespuestaPositiva").css('display', 'none');
    $("#lbRespuestaPositiva2").css('display', 'none');
    $("#lbRespuestaNegativa").css('display', 'none');
    $("#lbRespuestaNegativa2").css('display', 'none');
    
    document.getElementById('divPreguntaScroll').scrollTop = 0;
    
    $("#preguntaId").attr('value', preguntaId);
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var capsulaTipo = $('#capsulaTipo').val();
    var preguntaTexto = '';
    var mensajePositivo = '';
    var mensajeNegativo = '';
    
    
    if (capsulaTipo == 1) {
        $("#tdTituloAlternativa").html("<br>Alternativas (marque la alternativa correcta):<label id=\"lbAlternativas\" style=\"display:none\">*</label>")
    } 
    else {
        $("#tdTituloAlternativa").html("<br>Alternativas:<label id=\"lbAlternativas\" style=\"display:none\">*</label>")
    }
    
    $.ajax({
        url: 'funcionSeleccionarPregunta',
        type: 'POST',
        data: {
            preguntaId: preguntaId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if (data != null) {
            preguntaId = data[0].preguntaId;
            preguntaTexto = data[0].preguntaTexto;
            mensajePositivo = data[0].mensajePositivo;
            mensajeNegativo = data[0].mensajeNegativo;
            $('#tdAlternativas').html(capSeleccionarRespuestas(capsulaId, capsulaVersion, capsulaTipo, preguntaId, "2"));
            aplicarPlaceHolder();
        }
    });
    
    nicEditors.findEditor('tEditorPregunta').setContent(preguntaTexto);
    nicEditors.findEditor('tEditorRespuestaPositiva').setContent(mensajePositivo);
    nicEditors.findEditor('tEditorRespuestaNegativa').setContent(mensajeNegativo);
    
    return false;
}


function capSeleccionarRespuestas(capsulaId, capsulaVersion, capsulaTipo, preguntaId, caso) {
    
    var htmlRespuestas = "";
    
    $.ajax({
        url: 'funcionSeleccionarRespuestas',
        type: 'POST',
        data: {
            preguntaId: preguntaId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            capsulaTipo: capsulaTipo,
            caso: caso
        },
        cache: false,
        async: false,
        dataType: "html"
    }).done(function(data) {
        htmlRespuestas = data;
    });
    
    return htmlRespuestas;

}


function capGuardarRespuestas(capsulaId, capsulaVersion, preguntaId, respuestaTexto, respuestaCorrecta, alternativa, orden) {
    
    $.ajax({
        url: 'funcionGuardarRespuesta',
        type: 'POST',
        data: {
            preguntaId: preguntaId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            respuestaTexto: respuestaTexto,
            respuestaCorrecta: respuestaCorrecta,
            alternativa: alternativa,
            orden: orden,
        },
        cache: false,
        async: false,
        dataType: "html"
    }).done(function(data) {
    });

}


function capGuardarPregunta() {
    
    var preguntaId = $('#preguntaId').val();
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var capsulaTipo = $('#capsulaTipo').val();
    
    var preguntaTexto = nicEditors.findEditor('tEditorPregunta').getContent();
    var mensajePositivo = nicEditors.findEditor('tEditorRespuestaPositiva').getContent();
    var mensajeNegativo = nicEditors.findEditor('tEditorRespuestaNegativa').getContent();
    
    var validacion = "OK";

    //    var valorPregunta = editorPregunta.selection.win.document.activeElement.innerText;
    //    if (valorPregunta == undefined) {
    //        valorPregunta = editorPregunta.selection.win.document.activeElement.textContent;
    //    }
    //    
    //    var valorPositiva = editorRespuestaPositiva.selection.win.document.activeElement.innerText;
    //    if (valorPositiva == undefined) {
    //        valorPositiva = editorRespuestaPositiva.selection.win.document.activeElement.textContent;
    //    }
    //    
    //    var valorNegativo = editorRespuestaNegativa.selection.win.document.activeElement.innerText;
    //    if (valorNegativo == undefined) {
    //        valorNegativo = editorRespuestaNegativa.selection.win.document.activeElement.textContent;
    //    }
    
    var valorPregunta2 = preguntaTexto.length;
    var valorPositiva2 = mensajePositivo.length;
    var valorNegativo2 = mensajeNegativo.length;
    
    
    var seleccionado = 0;
    var capsulaTipo = $("#capsulaTipo").val();
    
    $('#tablaAlternativas tr').each(function() {
        
        var tr = $(this).attr('class');
        if (tr != 'trAlternativa') {
            var tdA = $(this).find("td").eq(0);
            var inputA = tdA.find("input")[0];
            if ((inputA.checked)) {
                seleccionado = 1;
            }
        }
    });
    
    if (seleccionado == 0 && capsulaTipo == 1) {
        $("#lbAlternativas").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 0;
    } 
    else {
        $("#lbAlternativas").css('display', 'none');
    }
    
    if (preguntaTexto.trim() == '' || preguntaTexto.trim() == '<br>') {
        $("#lbPregunta").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 0;
    } 
    else {
        $("#lbPregunta").css('display', 'none');
    }
    
    if ((mensajePositivo.trim() == '' || mensajePositivo.trim() == '<br>') && capsulaTipo == 1) {
        $("#lbRespuestaPositiva").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 200;
    } 
    else {
        $("#lbRespuestaPositiva").css('display', 'none');
    }
    
    if ((mensajeNegativo.trim() == '' || mensajeNegativo.trim() == '<br>') && capsulaTipo == 1) {
        $("#lbRespuestaNegativa").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 200;
    } 
    else {
        $("#lbRespuestaNegativa").css('display', 'none');
    }
    
    if (valorPregunta2 > 8000) {
        $("#lbPregunta2").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 0;
    } 
    else {
        $("#lbPregunta2").css('display', 'none');
    }
    
    if (valorPositiva2 > 8000) {
        $("#lbRespuestaPositiva2").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 200;
    } 
    else {
        $("#lbRespuestaPositiva2").css('display', 'none');
    }
    
    if (valorNegativo2 > 8000) {
        $("#lbRespuestaNegativa2").css('display', '');
        validacion = "";
        document.getElementById('divPreguntaScroll').scrollTop = 200;
    } 
    else {
        $("#lbRespuestaNegativa2").css('display', 'none');
    }
    
    if (validacion == "OK") {
        
        var caso = "";
        preguntaId == "0" ? caso = "new" : caso = "";
        
        $.ajax({
            url: 'funcionGuardarPregunta',
            type: 'POST',
            data: {
                preguntaId: preguntaId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                preguntaTexto: preguntaTexto,
                mensajePositivo: mensajePositivo,
                mensajeNegativo: mensajeNegativo
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
            if (data != null) {
                preguntaId = data[0].preguntaId;
                preguntaTexto = data[0].preguntaTexto;
                mensajePositivo = data[0].mensajePositivo;
                mensajeNegativo = data[0].mensajeNegativo;
                
                $('#tablaAlternativas tr').each(function(i) {
                    
                    var trAlternativa = $(this).attr('class');
                    
                    if (trAlternativa != 'trAlternativa') {
                        
                        var tdA = $(this).find("td").eq(0);
                        var tdAlt = $(this).find("td").eq(1);
                        var tdB = $(this).find("td").eq(2);
                        
                        var inputA = tdA.find("input")[0];
                        var inputB = tdB.find("input:text");
                        
                        var respuestaCorrecta = (inputA.checked) ? 'Si' : 'No';
                        var alternativa = tdAlt.html();
                        alternativa = alternativa.substring(0, 1);
                        var respuestaTexto = inputB.attr("value");
                        
                        
                        capGuardarRespuestas(capsulaId, capsulaVersion, preguntaId, respuestaTexto, respuestaCorrecta, alternativa, i + 1);
                    }
                
                });
            }
        });
        
        preguntaTexto = "<div class='pregunta'><b class='bPregunta'>Pregunta:</b><br>" + preguntaTexto + "</div><br>" + capSeleccionarRespuestas(capsulaId, capsulaVersion, capsulaTipo, preguntaId, "1");
        
        if (capsulaTipo == 1) {
            preguntaTexto = preguntaTexto + "<br><div class='positivo'><table border='0'><tr><td valign='top' style='padding:10px;'><img src='../css/imagenes/asistente/positivo.png'></td><td>" + mensajePositivo + "</td></tr></table></div><br>";
            preguntaTexto = preguntaTexto + "<br><div class='negativo'><table border='0'><tr><td valign='top' style='padding:10px;'><img src='../css/imagenes/asistente/negativo.png'></td><td>" + mensajeNegativo + "</td></tr></table></div>";
        }

        //tdAlternativas
        
        var liId = 'pregunta-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + preguntaId.toString()
        
        if (caso == "new") {
            $("#componentesCapsula").append("<li id='" + liId + "' class='ui-state-default'><div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarPregunta(" + preguntaId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarPregunta(" + preguntaId + ")'></td></tr></table>" + preguntaTexto + "</div>");
        } 
        else {
            $("#" + liId).html("<div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarPregunta(" + preguntaId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarPregunta(" + preguntaId + ")'></td></tr></table>" + preguntaTexto + "</div>");
        }
        
        agregarNumPregunta();
        
        $('.cerrarPopUp').click();
    
    }

}

function capEliminarImagen(contenidoId) {
    
    if (confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        var contenidoTipo = 2;
        
        $.ajax({
            url: 'funcionEliminarContenido',
            type: 'POST',
            data: {
                contenidoId: contenidoId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                contenidoTipo: contenidoTipo
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
        
        });
        
        var liId = 'imagen-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString();
        $("#" + liId).remove();
        
        $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    }
    
    return false;
}


function capEditarImagen(contenidoId) {
    
    $('#lbImagen').css("display", "none");
    
    $("#contenidoImagenId").attr('value', contenidoId);
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var contenidoDescripcion = "";
    var contenidoUrl = "";
    
    $.ajax({
        url: 'funcionSeleccionarContenido',
        type: 'POST',
        data: {
            contenidoId: contenidoId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if (data != null) {
            contenidoId = data[0].contenidoId;
            contenidoDescripcion = data[0].contenidoDescripcion;
            contenidoUrl = data[0].contenidoUrl;
        }
    });
    
    $("#overlay").css('filter', 'alpha(opacity=50)');
    
    var count = 0;
    
    
    $('#divVistaPrevia img').each(function(index, elemento) {
        
        if ($(elemento).attr("src").trim() == contenidoUrl.trim()) {
            $(elemento).css("display", "inline");
            count = index + 1;
        } 
        else {
            $(elemento).css("display", "none");
        }
    
    });
    
    if (count == 0) {
        //habilitarImagenes();
        $('#ulImagenes').prepend("<li rel='" + ($('#divVistaPrevia img').length + 1).toString() + "' class=\"active\"><img src='" + contenidoUrl + "' alt='' /></li>");
        $('#divVistaPrevia').prepend("<img src='" + contenidoUrl + "' alt='' style='display:inline'/>");
        habilitarImagenes();
        $('#ulImagenes li')[0].click();
    } 
    else {
        $('#ulImagenes li').each(function(index, elemento) {
            if (index == (count - 1)) {
                $(elemento).attr("class", "active");
            } 
            else {
                $(elemento).attr("class", "");
            }
        });
    }
    
    $('#imagenPie').attr("value", contenidoDescripcion);


    /*$('#menuOpciones').click();*/
    $('#overlay').fadeIn();
    $('#divImagen').css({visibility: "visible",zIndex: "2000"}).animate({opacity: 1}, 500);
    //$('#divImagen').fadeIn();
    
    return false;
}


function capGuardarImagen() {
    
    var contenidoUrl = '';
    
    $('#divVistaPrevia img').each(function(index, elemento) {
        
        imgDisplay = $(elemento).css("display");
        if (imgDisplay == "" || imgDisplay == "inline") {
            contenidoUrl = $(elemento).attr("src");
        }
    });
    
    
    if (contenidoUrl.trim() != "") {
        
        var contenidoId = $('#contenidoImagenId').val();
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        var contenidoTipo = 2;
        var contenidoDescripcion = $('#imagenPie').val();
        
        
        var imgDisplay = "";
        
        
        var caso = "";
        
        contenidoId == "0" ? caso = "new" : caso = "";
        
        $.ajax({
            url: 'funcionGuardarContenido',
            type: 'POST',
            data: {
                contenidoId: contenidoId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                contenidoTipo: contenidoTipo,
                contenidoDescripcion: contenidoDescripcion,
                contenidoUrl: contenidoUrl
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
            if (data != null) {
                contenidoId = data[0].contenidoId;
                contenidoDescripcion = data[0].contenidoDescripcion;
                contenidoUrl = data[0].contenidoUrl;
            }
        });
        
        var liId = 'imagen-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString()
        
        if (caso == "new") {
            $("#componentesCapsula").append("<li id='" + liId + "' class='ui-state-default'><div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarImagen(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarImagen(" + contenidoId + ")'></td></tr></table><center><img src='" + contenidoUrl + "'/><br><br>" + contenidoDescripcion + "</center></div></li>");
        } 
        else {
            $("#" + liId).html("<div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarImagen(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarImagen(" + contenidoId + ")'></td></tr></table><center><img src='" + contenidoUrl + "'/><br><br>" + contenidoDescripcion + "</center></div>");
        }
        
        $('.cerrarPopUp').click();
    
    } 
    else {
        $('#lbImagen').css("display", "");
    }
}


function capEliminarTexto(contenidoId) {
    
    if (confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        var contenidoTipo = 1;
        
        $.ajax({
            url: 'funcionEliminarContenido',
            type: 'POST',
            data: {
                contenidoId: contenidoId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                contenidoTipo: contenidoTipo
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
        
        });
        
        var liId = 'texto-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString();
        $("#" + liId).remove();
        
        $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    
    }
    
    return false;
}


function capEditarTexto(contenidoId) {
    
    $("#lbTextoEditor").css('display', 'none');
    
    $("#contenidoTextoId").attr('value', contenidoId);
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var contenidoDescripcion = "";
    
    $.ajax({
        url: 'funcionSeleccionarContenido',
        type: 'POST',
        data: {
            contenidoId: contenidoId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            contenidoDescripcion: contenidoDescripcion
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if (data != null) {
            contenidoId = data[0].contenidoId;
            contenidoDescripcion = data[0].contenidoDescripcion;
        }
    });
    
    $("#overlay").css('filter', 'alpha(opacity=50)');

    /*$('#menuOpciones').click();*/
    $('#overlay').fadeIn();
    $('#divTexto').css({visibility: "visible",zIndex: "2000"}).animate({opacity: 1}, 500);
    //$('#divTexto').fadeIn();
    
    nicEditors.findEditor('tEditorTexto').setContent(contenidoDescripcion);
    
    return false;
}


function capGuardarTexto() {
    
    var contenidoId = $('#contenidoTextoId').val();
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var contenidoTipo = 1;
    
    var contenidoDescripcion = nicEditors.findEditor('tEditorTexto').getContent();
    
    var contenidoUrl = ' ';
    var caso = "";
    
    contenidoId == "0" ? caso = "new" : caso = "";
    
    if (contenidoDescripcion.length <= 8000) {
        
        $("#lbTextoEditor").css('display', 'none');
        
        $.ajax({
            url: 'funcionGuardarContenido',
            type: 'POST',
            data: {
                contenidoId: contenidoId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                contenidoTipo: contenidoTipo,
                contenidoDescripcion: contenidoDescripcion,
                contenidoUrl: contenidoUrl
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
            if (data != null) {
                contenidoId = data[0].contenidoId;
                contenidoDescripcion = data[0].contenidoDescripcion;
                contenidoUrl = data[0].contenidoUrl;
            }
        });

        // agregar al div vacio o con elementos 
        var liId = 'texto-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString()
        
        if (caso == "new") {
            $("#componentesCapsula").append("<li id='" + liId + "' class='ui-state-default'><div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarTexto(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarTexto(" + contenidoId + ")'></td></tr></table>" + contenidoDescripcion + "</div></li>");
        } 
        else {
            $("#" + liId).html("<div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarTexto(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarTexto(" + contenidoId + ")'></td></tr></table>" + contenidoDescripcion + "</div>");
        }
        
        $('.cerrarPopUp').click();
    } else {
        $("#lbTextoEditor").css('display', '');
    }
}


function guardarCabeceraCapsula() {
    
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var temaNombre = $('#temaNombre').val();
    var capsulaNombre = $('#capsulaNombre').val();
    var capsulaDescripcion = $('#capsulaDescripcion').val();
    var capsulaTipo = $('#capsulaTipo').val();
    var capsulaEstado = $('#capsulaEstado').val();
    var capsulaNumero = $('#capsulaNumero').val();
    
    $.ajax({
        url: 'funcionGuardarCapsula',
        type: 'POST',
        data: {
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            temaNombre: temaNombre,
            capsulaNombre: capsulaNombre,
            capsulaDescripcion: capsulaDescripcion,
            capsulaTipo: capsulaTipo,
            capsulaEstado: capsulaEstado,
            capsulaNumero: capsulaNumero
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        var temaId = 0;
        var temaUrl = "";
        var estado = "";
        if (data != null) {
            estado = data[0].estado;
            capsulaId = data[0].capsulaId;
            capsulaVersion = data[0].capsulaVersion;
            temaId = data[0].temaId;
            temaUrl = data[0].temaUrl;
            capsulaNumero = data[0].capsulaNumero;
        }
        $('#capsulaValida').attr("value", estado);
        $('#capsulaId').attr("value", capsulaId);
        $('#capsulaVersion').attr("value", capsulaVersion);
        $('#temaId').attr("value", temaId);
        $('#temaUrl').attr("value", temaUrl);
        $('#capsulaNumero').attr("value", capsulaNumero);
    
    });
}


function seleccionarTemaId() {
    
    var temaNombre = $('#temaNombre').val();
    
    $.ajax({
        url: 'funcionSeleccionarTemas.php', //Url a donde la enviaremos
        type: 'POST', //Metodo que usaremos
        data: {
            term: temaNombre,
            caso: 1
        }, //Le pasamos el objeto que creamos con los archivos
        cache: false, //Para que el formulario no guarde cache
        async: false,
        dataType: "json"
    })
    .done(function(data) {
        var temaId = 0;
        var temaUrl = "";
        if (data != null) {
            temaId = data[0].temaId;
            temaUrl = '..' + data[0].temaUrl;
        }
        $('#temaId').attr("value", temaId);
        
        if (data == null || temaUrl.trim() == "..") {
            $('#tdCabeceraTema1').css("display", "");
            $('#tdCabeceraTema2').css("display", "none");
        } 
        else {
            $('#tdCabeceraTema1').css("display", "none");
            $('#tdCabeceraTema2').css("display", "");
        }
        
        $('#temaUrl').attr("src", temaUrl);
    });
}


function capEliminarComentario(contenidoId) {
    
    if (confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        var contenidoTipo = 3;
        
        $.ajax({
            url: 'funcionEliminarContenido',
            type: 'POST',
            data: {
                contenidoId: contenidoId,
                capsulaId: capsulaId,
                capsulaVersion: capsulaVersion,
                contenidoTipo: contenidoTipo
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
        
        });
        
        var liId = 'comentario-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString();
        $("#" + liId).remove();
        
        $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    
    }
    
    return false;
}


function capEditarComentario(contenidoId) {
    
    $("#contenidoComentarioId").attr('value', contenidoId);
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var contenidoDescripcion = "";
    var contenidoObligatorio = "";
    
    $.ajax({
        url: 'funcionSeleccionarContenido',
        type: 'POST',
        data: {
            contenidoId: contenidoId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if (data != null) {
            contenidoId = data[0].contenidoId;
            contenidoDescripcion = data[0].contenidoDescripcion;
            contenidoObligatorio = data[0].contenidoObligatorio
        }
    });
    
    $('#comentarioTitulo').val(contenidoDescripcion);
    $('#tdComentarioTitulo').html(contenidoDescripcion);
    
    if (contenidoObligatorio == "1") {
        $('#lbSi').click();
    }
    
    $("#overlay").css('filter', 'alpha(opacity=50)');
    
    $('#overlay').fadeIn();
    $('#divComentario').css({visibility: "visible",zIndex: "2000"}).animate({opacity: 1}, 500);
    
    return false;

}


function capGuardarComentario() {
    
    var contenidoId = $('#contenidoComentarioId').val();
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var contenidoTipo = 3;
    var contenidoDescripcion = $('#comentarioTitulo').val();
    var contenidoUrl = ' ';
    var contenidoObligatorio = $('#chbComentario:checked').val();
    var caso = "";
    
    contenidoId == "0" ? caso = "new" : caso = "";
    
    $.ajax({
        url: 'funcionGuardarContenido',
        type: 'POST',
        data: {
            contenidoId: contenidoId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            contenidoTipo: contenidoTipo,
            contenidoDescripcion: contenidoDescripcion,
            contenidoUrl: contenidoUrl,
            contenidoObligatorio: contenidoObligatorio
        },
        cache: false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if (data != null) {
            contenidoId = data[0].contenidoId;
            contenidoDescripcion = data[0].contenidoDescripcion;
            contenidoUrl = data[0].contenidoUrl;
            contenidoObligatorio = data[0].contenidoObligatorio;
        }
    });
    
    var contenidoHtml = "<table style='width:970px; margin: 0 auto;'><tr><td width='5px' height='18px' valign='top'>";
    
    if (contenidoObligatorio.toString().trim() == "1") {
        contenidoHtml = contenidoHtml + "<label title='Este campo es obligatorio'>*</label>";
    }
    
    contenidoHtml = contenidoHtml + "</td><td>" + contenidoDescripcion + "</td></tr><tr><td align='center' colspan='2'><div class='divComentarioTexto'></div><br><br></td></tr></table>";
    
    var liId = 'comentario-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + contenidoId.toString()
    
    if (caso == "new") {
        $("#componentesCapsula").append("<li id='" + liId + "' class='ui-state-default'><div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarComentario(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarComentario(" + contenidoId + ")'></td></tr></table>" + contenidoHtml + "</div></li>");
    } 
    else {
        $("#" + liId).html("<div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarComentario(" + contenidoId + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarComentario(" + contenidoId + ")'></td></tr></table>" + contenidoHtml + "</div>");
    }
    
    $('.cerrarPopUp').click();
}

function capCrearFeedback() {
    
    var id_feedback = $('#id_feedback').val();
    var capsulaId = $('#capsulaId').val();
    var capsulaVersion = $('#capsulaVersion').val();
    var administradorId = $('#administradorId').val();
    var cont_F = 0; //contador de preguntas
    var cont_O = 0; //contador de opciones
    var c1 = $('#opcion_1').val();
    var c2 = $('#opcion_2').val();
    var c3 = $('#opcion_3').val();
    var c4 = $('#opcion_4').val();
    var c5 = $('#opcion_5').val();
    var ID_Padre = '';
    var TR2 = '';
    var TR1 = '';
    var TR = '';
    var caso = "";
    var validar = false;
	var pk_id_feedback= 0; // PARA EL FK DE LAS ALTERNATIVAS 
    var num = 0;
    id_feedback == "0" ? caso = "new" : caso = "";
	
		alert(caso);
		console.log(caso);
	
    //valida opciones vacias
    if ( (c1 != '' || c2 != '' || c3 != '' || c4 != '' || c5 != '') &&
         (c1 != 'Opción' || c2 != 'Opción' || c3 != 'Opción' || c4 != 'Opción' || c5 != 'Opción')) {
		
        // valida preguntas vacias 
        $('#tablaFeedback tr').each(function(i) {
            if ($(this).attr('id') != 'first') {
                var trPregunta = $(this).attr('class'); // es fila visible?
                var tdB = $(this).find("td").eq(2); // selec col 3
                var inputB = tdB.find("input:text"); // busca el campo 
                var contenidoAlternativa = inputB.attr("value"); //rescata el contenido
                //valida alternativa vacia y fila en blanco
                if (trPregunta != 'trPregunta' && contenidoAlternativa != '') {
                    validar = true;
                //al menos hay una pregunta
                }
            }
        });
        
        if (validar == true) {

            //creando el Feedback
            $.ajax({
                type: "POST",
                url: "funcionGuardarFeedback",
                cache: false,
                async: false,
                dataType: "json",
                data: {
                    id_feedback: id_feedback,
                    capsulaId: capsulaId,
                    capsulaVersion: capsulaVersion,
                    administradorId: administradorId,
                    c1: c1,
                    c2: c2,
                    c3: c3,
                    c4: c4,
                    c5: c5
                }
            }).done(function(data) {
                if (data != null) {
                    id_feedback = data[0].id_feedback;
                    c1 			= data[0].o1;
                    c2 			= data[0].o2;
                    c3 			= data[0].o3;
                    c4 			= data[0].o4;
                    c5 			= data[0].o5;
                    //console.log(data[0].id_feedback + '-- feedback--' + data[0].o1 + data[0].o2 + data[0].o3 + data[0].o4 + data[0].o5);
					pk_id_feedback=id_feedback;
                }
            });

			
            //TR de las opciones 
            TR1 += "<tr id='first'><td></td><td></td><td style='width: 520px;'>Preguntas :  </td>";
			
            if ($('#opcion_1').val() != '') { num++;  TR1 += "<td class='Feed' >"+$('#opcion_1').val()+"</td>";}
                                                                      
            if ($('#opcion_2').val() != '') { num++;  TR1 += "<td class='Feed' >"+$('#opcion_2').val()+"</td>";}
                                                                      
            if ($('#opcion_3').val() != '') { num++;  TR1 += "<td class='Feed' >"+$('#opcion_3').val()+"</td>";}
                                                                      
            if ($('#opcion_4').val() != '') { num++;  TR1 += "<td class='Feed' >"+$('#opcion_4').val()+"</td>";}
                                                                     
            if ($('#opcion_5').val() != '') { num++;  TR1 += "<td class='Feed' >"+$('#opcion_5').val()+"</td>";}

            TR1 += "</tr>";
			
			
			
			
			
			
			
            //TR de las opciones 

            //Buscando las alternativas
            $('#tablaFeedback tr').each(function(i) {

                // salta el primer tr 
                if ($(this).attr('id') != 'first') {
                    
                    trPregunta = $(this).attr('class'); 		// es fila visible?
                    tdB = $(this).find("td").eq(2); 			// selec col 3
                    inputB = tdB.find("input:text"); 			// busca el campo 
                    contenidoAlternativa = inputB.attr("value"); //rescata el contenido


                    //valida alternativa vacia y fila en blanco
                    if (trPregunta != 'trPregunta' && contenidoAlternativa != '') {
                        console.log('pregunta -> ' + contenidoAlternativa);

                        //creando alternativas
                        $.ajax({
                            type: "POST",
                            url: "funcionGuardarAlternativa",
                            cache: false,
                            async: false,
                            dataType: "json",
                            data: {
                                pk_id_feedback		: pk_id_feedback,
                                capsulaId			: capsulaId,
                                capsulaVersion		: capsulaVersion,
                                contenidoAlternativa: contenidoAlternativa,
                                administradorId		: administradorId
                            }
                        }).done(function(data) {
                            if (data != null) {
                                cont_F++;
                                id_alternativa 			= data[0].id_alternativa;
                                contenidoAlternativa 	= data[0].contenidoAlternativa;
                                //console.log(data[0].id_alternativa + '--  alternativa  --' + data[0].contenidoAlternativa);

                                //tr de las alternativas
                                var TR = "<tr ><td></td><td>" + cont_F + ")</td><td style='text-align: left; border-bottom: 1px gainsboro solid;'>" + contenidoAlternativa + "</td>";

								if ($('#opcion_1').val() != '') {
									TR += "<td class='Feed'  ><input type='hidden' onClick() /><input type='radio'  name='"+id_alternativa+"'/></td>";
								}                                  
								if ($('#opcion_2').val() != '') {                         
									TR += "<td class='Feed'  ><input type='hidden' onClick() /><input type='radio'  name='"+id_alternativa+"'/></td>";
								}                                  
								if ($('#opcion_3').val() != '') {                          
									TR += "<td class='Feed'  ><input type='hidden' onClick() /><input type='radio'  name='"+id_alternativa+"'/></td>";
								}                                  
								if ($('#opcion_4').val() != '') {                          
									TR += "<td class='Feed'  ><input type='hidden' onClick() /><input type='radio'  name='"+id_alternativa+"'/></td>";
								}                                  
								if ($('#opcion_5').val() != '') {              
									TR += "<td class='Feed'  ><input type='hidden' onClick() /><input type='radio'  name='"+id_alternativa+"'/></td>";
								}
								
                                TR += "</tr>";
                                TR2 += TR;
                            }
                        });
                    }
                }
			});
			
            var TABLA = "<div class='feedback'><b class='bFeedback'>Feedback:</b><br><table cellpadding='0' cellspacing='0' class='F1'><tbody>" + TR1 + TR2 + "</tbody></table></div>";
		   // VISTA PREVIA DRAGGABLE

            var liId = 'feedback-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + id_feedback.toString()
            
            if (caso == "new") {

                $("#componentesCapsula").append("<li id='" + liId + "' class='ui-state-default'><div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarFeedback(" + id_feedback + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarFeedback(" + id_feedback + ")'></td></tr></table>" + TABLA + "</div>");
            } else {
                
                $("#" + liId).html("<div class='divContenido'><table width='100%'><tr><td width='97%'></td><td><img src='../css/imagenes/asistente/editar.png' onClick='capEditarFeedback(" + id_feedback + ")'></td><td><img src='../css/imagenes/asistente/borrar.png' onClick='capEliminarFeedback(" + id_feedback + ")'></td></tr></table>" + TABLA + "</div>");
            }

			agregarNumFeedback();
			
            $('.cerrarPopUp').click();
            return 0;
        } else { 
            alert('Debes crear almenos una pregunta');
        }
    } else {
        alert('Deves completar al menos 1 campo');
    }
}








function capEliminarFeedback(id_feedback) {
    
    if (confirm("Se eliminará el contenido.\nDesea continuar?")) {
        var capsulaId = $('#capsulaId').val();
        var capsulaVersion = $('#capsulaVersion').val();
        
        $.ajax({
            url: 'funcionEliminarFeedback',
            type: 'POST',
            data: {
                capsulaId		: capsulaId,
                id_feedback		: id_feedback,
                capsulaVersion	: capsulaVersion
            },
            cache: false,
            async: false,
            dataType: "json"
        }).done(function(data) {
			
			alert(data[0].estado);
			
			if (data[0].estado == 1 ){
				console.log('Feedback borrado');
			}else{ 
				console.log('Error de al borrar Feedback -> BD ');
			}
			
        
        });

    var liId = 'feedback-' + capsulaId.toString() + '-' + capsulaVersion.toString() + '-' + id_feedback.toString();
    $("#" + liId).remove();

    $(".stepContainer").css("height", $("#step-2").height() + 14 + "px");
    }
    
    return false;
}

function capEditarFeedback(id_feedback) {

    $("#overlay").css('filter', 'alpha(opacity=50)');
    $('#overlay').fadeIn();
    $('#divFeedback').css({visibility: "visible", zIndex:"2000"}).animate({opacity: 1}, 500);

    document.getElementById('divFeedbackScroll').scrollTop = 0;
    
    $("#id_feedback").attr('value', id_feedback);

    var capsulaId       = $('#capsulaId').val();
    var capsulaVersion  = $('#capsulaVersion').val();

    $.ajax({
        url:'funcionSeleccionarFeedback',
        type:'POST',
        data:{
            id_feedback: id_feedback,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache:false,
        async: false,
        dataType: "json"
    }).done(function(data) {
        if(data != null){	
			id_feedback = data[0].id_feedback;
			c1 			= data[0].o1;
			c2 			= data[0].o2;
			c3 			= data[0].o3;
			c4 			= data[0].o4;
			c5 			= data[0].o5;

            //$('#tdPreguntas').empty();
			$('#tdPreguntas').html( capSeleccionarAlternativas(capsulaId, capsulaVersion,id_feedback) );
						
			//Setea opciones
			if( c1 != null ){	$('#opcion_1').val(c1);	}else{	$('#opcion_1').val('');	}
			if( c2 != null ){	$('#opcion_2').val(c2);	}else{	$('#opcion_2').val('');	}
			if( c3 != null ){	$('#opcion_3').val(c3);	}else{	$('#opcion_3').val('');	}
			if( c4 != null ){	$('#opcion_4').val(c4);	}else{	$('#opcion_4').val('');	}
			if( c5 != null ){	$('#opcion_5').val(c5);	}else{	$('#opcion_5').val('');	}

            //aplicarPlaceHolder();
        }
    });

    return false;
}
function capSeleccionarAlternativas(capsulaId, capsulaVersion, id_feedback) {
    
    var htmlRespuestas = "";
    
    $.ajax({
        url: 'funcionSeleccionarAlternativas',
        type: 'POST',
        data: {
            id_feedback: id_feedback,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion
        },
        cache: false,
        async: false,
        dataType: "html"
    }).done(function(data) {
        htmlRespuestas = data;
    });
    
    return htmlRespuestas;

}






function capCrearAlternativasFeedback(feedbackId, capsulaId, capsulaVersion, alternativaEscogida) {
    
    $.ajax({
        url: 'funcionCrearAlternativasFeedback',
        type: 'POST',
        data: {
            feedbackId: feedbackId,
            capsulaId: capsulaId,
            capsulaVersion: capsulaVersion,
            alternativaEscogida: alternativaEscogida
        },
        cache: false,
        async: false,
        dataType: "html" // <- ? json 
    }).done(function(data) {
    
    });
}
function cargarPreguntasFeedback() { //agrega las x preguntas de la pestaña FEEDBACK 
    
    var total_preguntas = $('#total_preguntas').find('option:selected').val();
    var Nombre_cap = $('#capsulaNombre').val();
    
    console.log('cargando' + total_preguntas + ' preguntas ');
    $.ajax({
        type: "POST",
        url: "funcionCargarPreguntasFeedback",
        data: {total_preguntas: total_preguntas,
            Nombre_cap: Nombre_cap
        }
    }).done(function(data) {
        console.log(data);
        if (data != null) {

            //vacia preguntas anteriores
            $('.Pregunta').remove();
            //inserta las preguntas 
            $('#Fila1').after(data);
        //$('#CargaPreguntas').css('visibility', 'visible');
        }
    });

}

/*   $(document).keydown(function(key) {
    
    switch(parseInt(key.which,10)) {
      case 13:    //<-
        $('img').click();
        
        break;

    }
  }); */
