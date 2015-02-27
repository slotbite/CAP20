
//funcion se ejecuta al presionar el boton buscar, que trae todos los envios duplicados
function seleccionarRegistrosDuplicados() {

    if (document.formConsulta1.fecha_desde.value.length == 0) {
        alert("Debe seleccionar una fecha válida.")
        document.formConsulta1.fecha_desde.focus()
        return 0;
    }
    if (document.formConsulta1.fecha_hasta.value.length == 0) {
        alert("Debe seleccionar una fecha válida.")
        document.formConsulta1.fecha_hasta.focus()
        return 0;
    }
    var fecha_desde = $("#fecha_desde").val();
    var fecha_hasta = $("#fecha_hasta").val();
    $.ajax({
        type: "POST",
        url: "consultas/funcionBuscarRegistrosDuplicados.php",
        data: {fecha_desde: fecha_desde, fecha_hasta: fecha_hasta},
        success: function (data) {
            $('#divTabla').css('visibility', 'visible');
            $('#ListaUsuarios').html(data);
        }
    });

}

//funcion se ejecuta al presionar el boton ver envios, que trae todos los datos del envio
function cargarDetalle(capsulaId2, usuarioId2, tipo1, capsulaVersion, capsulaNombre, usuarioNombre) {

    $.ajax({
        type: "POST",
        url: "consultas/funcionBuscarDetalle.php",
        data: {capsulaId2: capsulaId2, usuarioId2: usuarioId2, tipo1: tipo1, capsulaVersion: capsulaVersion, capsulaNombre: capsulaNombre, usuarioNombre: usuarioNombre},
        success: function (data) {
            $('#repo_id3').html(data);
            $('#repo_hold3').css('visibility', 'visible');
            $('#boton5').css('visibility', 'hidden');
            $('#boton3').css('visibility', 'hidden');
            $('#boton2').css('visibility', 'hidden');
            $('#boton1').css('visibility', 'hidden');

        }
    });
}

//funcion que cierra el formulario donde aparesen los datos del envio
function cerrar_formulario() {
    $('#repo_hold3').css('visibility', 'hidden');
    $('#boton5').css('visibility', 'visible');
    $('#boton3').css('visibility', 'visible');
    $('#boton2').css('visibility', 'visible');
    $('#boton1').css('visibility', 'visible');
    $('#repo_id3').html('');
}


//funcion que elimina el registro duplicado
function eliminarRegistros(envioId, capsulaId, capsulaVersion, usuarioId) {

    var blnRespuesta = confirm('¿Desea eliminar el envío seleccioando?');
    //comparamos la respuesta del usuario
    if (blnRespuesta) {
        $.ajax({
            type: "POST",
            url: "consultas/funcionEliminarRegistrosDuplicados.php",
            data: {envioId: envioId, capsulaId: capsulaId, capsulaVersion: capsulaVersion, usuarioId: usuarioId},
            success: function (data) {
                cerrar_formulario();
                seleccionarRegistrosDuplicados();
            }
        });
    }
}


//funcion se ejecuta al presionar el boton buscar, que trae todos los envios de usuarios inactivos que hay que eliminar
function seleccionarUsuarios() {

    if (document.formConsulta1.fecha_desde.value.length == 0) {
        alert("Debe seleccionar una fecha válida.")
        document.formConsulta1.fecha_desde.focus()
        return 0;
    }
    if (document.formConsulta1.fecha_hasta.value.length == 0) {
        alert("Debe seleccionar una fecha válida.")
        document.formConsulta1.fecha_hasta.focus()
        return 0;
    }
    var fecha_desde = $("#fecha_desde").val();
    var fecha_hasta = $("#fecha_hasta").val();
    $.ajax({
        type: "POST",
        url: "consultas/funcionBuscarRegistrosUsuariosInactivos.php",
        data: {fecha_desde: fecha_desde, fecha_hasta: fecha_hasta},
        success: function (data) {
            $('#divTabla').css('visibility', 'visible');
            $('#ListaUsuarios').html(data);
        }
    });

}


function EliminarDuplicados() {

    $.ajax({
        type: "POST",
        url: "consultas/funcionEliminarRegistrosUsuariosInactivos.php",
        data: {},
        success: function (data) {
            $('#repo_id').html(data);
            $('#repo_hold').css('visibility', 'visible');
            $('#boton1').css('visibility', 'hidden');
        }
    });
}


function buscarIndicadoresCAP(yyyy, mm) {
    
    var profileData = {yyyy: yyyy, mm: mm};

    $.ajax({
        url: "consultas/funcionBuscarIndicadores.php",
        type: "POST",
        data: profileData,
        success: function (result) {
            $('#divIndicadorCap').html(result);            
        }
    });
}


function buscarIndicadoresCAP(yyyy, mm) {
    
    $("#imgCargando").css('display', '');
    
    var profileData = {yyyy: yyyy, mm: mm};

    $.ajax({
        url: "consultas/funcionBuscarIndicadores.php",
        type: "POST",
        data: profileData,
        success: function (result) {
            $('#divIndicadorCap').html(result);
            $("#imgCargando").css('display', 'none');
        }
    });
}



function recalcularIndicadorCAP(yyyy, mm) {
    
    var profileData = {yyyy: yyyy, mm: mm};

    $.ajax({
        url: "consultas/funcionCalcularIndicadores.php",
        type: "POST",
        data: profileData,
        success: function (result) {
            buscarIndicadoresCAP(yyyy, mm);
        }
    });
}


					