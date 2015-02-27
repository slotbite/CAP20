

function manValidarFormulario(inputId, botonId, mensaje){
    if($(inputId).value != ""){
        $(botonId).click();
    }
    else{
        alert(mensaje);
    }
}


function manEditarData(id, campoId, btnId){  
    
    document.getElementById(campoId).value = id;
    document.getElementById(btnId).click();            
    
}


function manAnularData(id, link, destino, estado, mensaje){
            
    var status = "";    
    
    if(estado == "1"){
        status = "anular";
    }
    else{
        status = "activar";
    }           
    
    var msg = confirm("Desea " + status + " " + mensaje)
    if ( msg ) {
        var elRequest = new Request({
            url     : link + id, 
            method  :'get',        
            async   : false,
            onSuccess: function(datos){                                                                
                if(datos.trim() != null){
                
                    if(datos.trim() == ""){
                          location.href = destino;     
                    }
                    else{
                        alert(datos.trim());
                    }                
                }
            //("formulario").set('html',datos);                
            //$('fila-'+id).remove();
            },
            //Si Falla
            onFailure: function() {
                $('formulario').set('html', 'Error');
            }
        });
        elRequest.send();
    }
    
    return false;               
}


function manValidarFormularioOrganizaciones(orgNombre, asunto, encabezado, pie, botonId){
    
    organizacionNombre = $(orgNombre).value;
    asuntoData = $(asunto).value;
    encabezadoData = $(encabezado).value;
    pieData = $(pie).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(asuntoData == ""){
        $('oAsunto').setStyle('display', '');
        error = "si";
    }
    else{
        $('oAsunto').setStyle('display', 'none');
    }
    
    if(encabezadoData == ""){
        $('oEncabezado').setStyle('display', '');
        error = "si";
    }
    else{
        $('oEncabezado').setStyle('display', 'none');
    }
    
    if(pieData == ""){
        $('oPie').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPie').setStyle('display', 'none');
    }
            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioOrganizacionesEdicion(orgNombre, asunto, encabezado, pie, botonId){
    
    organizacionNombre = $(orgNombre).value;
    asuntoData = $(asunto).value;
    encabezadoData = $(encabezado).value;
    pieData = $(pie).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(asuntoData == ""){
        $('oAsunto').setStyle('display', '');
        error = "si";
    }
    else{
        $('oAsunto').setStyle('display', 'none');
    }
    
    if(encabezadoData == ""){
        $('oEncabezado').setStyle('display', '');
        error = "si";
    }
    else{
        $('oEncabezado').setStyle('display', 'none');
    }
    
    if(pieData == ""){
        $('oPie').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPie').setStyle('display', 'none');
    }
            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        
        estado = $('organizacionEstado').value;
        
        if(estado == "0"){
                
            if(confirm("La organización se encuentra inactiva: ¿Desea activarla?")) {
                $('organizacionEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }                       
    }
}


function manValidarFormularioSectores(orgNombre, secNombre, botonId){
    
    organizacionNombre = $(orgNombre).value;
    sectorNombre = $(secNombre).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(sectorNombre == ""){
        $('oSector').setStyle('display', '');
        error = "si";
    }
    else{
        $('oSector').setStyle('display', 'none');
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioSectoresEdicion(orgNombre, secNombre, botonId){
    
    organizacionNombre = $(orgNombre).value;
    sectorNombre = $(secNombre).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(sectorNombre == ""){
        $('oSector').setStyle('display', '');
        error = "si";
    }
    else{
        $('oSector').setStyle('display', 'none');
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('sectorEstado').value;
        
        if(estado == "0"){
                
            if(confirm("La gerencia/agencia se encuentra inactiva: ¿Desea activarla?")) {
                $('sectorEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }       
    }
}


function manValidarFormularioAreas(orgNombre, secNombre, arNombre, botonId){
    
    organizacionNombre = $(orgNombre).value;
    sectorNombre = $(secNombre).value;
    areaNombre = $(arNombre).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(sectorNombre == ""){
        $('oSector').setStyle('display', '');
        error = "si";
    }
    else{
        $('oSector').setStyle('display', 'none');
    }
    
    if(areaNombre == ""){
        $('oArea').setStyle('display', '');
        error = "si";
    }
    else{
        $('oArea').setStyle('display', 'none');
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioAreasEdicion(orgNombre, secNombre, arNombre, botonId){
    
    organizacionNombre = $(orgNombre).value;
    sectorNombre = $(secNombre).value;
    areaNombre = $(arNombre).value;
    
    error = "";
    
    
    if(organizacionNombre == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }
    
    if(sectorNombre == ""){
        $('oSector').setStyle('display', '');
        error = "si";
    }
    else{
        $('oSector').setStyle('display', 'none');
    }
    
    if(areaNombre == ""){
        $('oArea').setStyle('display', '');
        error = "si";
    }
    else{
        $('oArea').setStyle('display', 'none');
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('areaEstado').value;
        
        if(estado == "0"){
                
            if(confirm("La área se encuentra inactiva: ¿Desea activarla?")) {
                $('areaEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }       
    }
}


function manValidarFormularioCargos(carNombre, botonId){
    
    cargoNombre = $(carNombre).value;    
    
    error = "";
    
    
    if(cargoNombre == ""){
        $('oCargo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oCargo').setStyle('display', 'none');
    }        
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioCargosEdicion(carNombre, botonId){
    
    cargoNombre = $(carNombre).value;    
    
    error = "";
    
    
    if(cargoNombre == ""){
        $('oCargo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oCargo').setStyle('display', 'none');
    }        
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        
        estado = $('cargoEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El cargo se encuentra inactivo: ¿Desea activarlo?")) {
                $('cargoEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }         
    }
}


function manValidarFormularioClientes(inputNombres, inputApellidos, inputEmail, botonId){

    cliNombres = $(inputNombres).value;
    cliApellidos = $(inputApellidos).value;
    cliEmail = $(inputEmail).value;
        
    error = "";
    
    
    if(cliNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(cliApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(cliEmail == "" || manValidarEmail(cliEmail)){
        error = "si";
        
        if(cliEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(cliEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
                            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }            
}


function manValidarFormularioClientesEdicion(inputNombres, inputApellidos, inputEmail, botonId){

    cliNombres = $(inputNombres).value;
    cliApellidos = $(inputApellidos).value;
    cliEmail = $(inputEmail).value;
        
    error = "";
    
    
    if(cliNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(cliApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(cliEmail == "" || manValidarEmail(cliEmail)){
        error = "si";
        
        if(cliEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(cliEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
                            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('clienteEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El cliente se encuentra inactivo: ¿Desea activarlo?")) {
                $('clienteEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }       
    }            
}


function manValidarFormularioPerfiles(perNombre, botonId){
    
    perfilNombre = $(perNombre).value;    
    
    error = "";
    
    
    if(perfilNombre == ""){
        $('oPerfil').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPerfil').setStyle('display', 'none');
    }        
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioPerfilesEdicion(perNombre, botonId){
    
    perfilNombre = $(perNombre).value;    
    
    error = "";
    
    
    if(perfilNombre == ""){
        $('oPerfil').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPerfil').setStyle('display', 'none');
    }        
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('perfilEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El perfil se encuentra inactivo: ¿Desea activarlo?")) {
                $('perfilEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }       
    }
}


function manValidarFormularioAdministradores(inputNombres, inputApellidos, inputEmail, inputPerfil, inputLogin, inputPass, botonId){

    admNombres = $(inputNombres).value;
    admApellidos = $(inputApellidos).value;
    admEmail = $(inputEmail).value;
    //admPerfil = $(inputPerfil).value;
    admLogin = $(inputLogin).value;
    admPass = $(inputPass).value;
    
    error = "";
    
    
    if(admNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(admApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(admEmail == "" || manValidarEmail(admEmail)){
        error = "si";
        
        if(admEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(admEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
    
    /*if(admPerfil == ""){
        $('oPerfil').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPerfil').setStyle('display', 'none');
    }*/
    
    if(admLogin == ""){
        $('oLogin').setStyle('display', '');
        error = "si";
    }
    else{
        $('oLogin').setStyle('display', 'none');
    }
    
    if(admPass == "" || admPass.length < 5){
        error = "si";
        if(admPass == ""){               
            $('oPass').setStyle('display', '');
        }
        else{
            $('oPass').setStyle('display', 'none');
        }
        
        if(admPass.length < 5){               
            $('oPass2').setStyle('display', '');
        }
        else{
            $('oPass2').setStyle('display', 'none');
        }
        
    }
    else{
        $('oPass').setStyle('display', 'none');
        $('oPass2').setStyle('display', 'none');        
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}


function manValidarFormularioAdministradoresEdicion(inputNombres, inputApellidos, inputEmail, inputPerfil, inputLogin, inputPass, botonId){

    admNombres = $(inputNombres).value;
    admApellidos = $(inputApellidos).value;
    admEmail = $(inputEmail).value;
    //admPerfil = $(inputPerfil).value;
    admLogin = $(inputLogin).value;
    admPass = $(inputPass).value;
    
    error = "";
        
    if(admNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(admApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(admEmail == "" || manValidarEmail(admEmail)){
        error = "si";
        
        if(admEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(admEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
    
    /*if(admPerfil == ""){
        $('oPerfil').setStyle('display', '');
        error = "si";
    }
    else{
        $('oPerfil').setStyle('display', 'none');
    }*/
    
    if(admLogin == ""){
        $('oLogin').setStyle('display', '');
        error = "si";
    }
    else{
        $('oLogin').setStyle('display', 'none');
    }
    
    if(admPass == "" || admPass.length < 5){
        error = "si";
        if(admPass == ""){               
            $('oPass').setStyle('display', '');
        }
        else{
            $('oPass').setStyle('display', 'none');
        }
        
        if(admPass.length < 5){               
            $('oPass2').setStyle('display', '');
        }
        else{
            $('oPass2').setStyle('display', 'none');
        }
        
    }
    else{
        $('oPass').setStyle('display', 'none');
        $('oPass2').setStyle('display', 'none');        
    }
                    
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('administradorEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El administrador se encuentra inactivo: ¿Desea activarlo?")) {
                $('administradorEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }        
    }
}


function manValidarFormularioUsuarios(inputRut, inputNombres, inputApellidos, inputEmail, inputCargo, inputOrganizacion, botonId){
    
    uRut = $(inputRut).value;
    uNombres = $(inputNombres).value;
    uApellidos = $(inputApellidos).value;
    uEmail = $(inputEmail).value;
    uCargo = $(inputCargo).value;
    uOrganizacion = $(inputOrganizacion).value;
        
    error = "";
    
    
    if(uRut == "" || manValidarRut(uRut)){
        
        if(uRut == ""){
            $('oRut').setStyle('display', '');
            error = "si";
        }
        else{
            $('oRut').setStyle('display', 'none');
            error = "no";
        }
        
        if(manValidarRut(uRut)){
            $('oRut2').setStyle('display', '');
            error = "si";
        }
        else{
            $('oRut2').setStyle('display', 'none');
            error = "no";
        }
        
    }
    else{
        $('oRut').setStyle('display', 'none');
        $('oRut2').setStyle('display', 'none');
    }
    
    if(uNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(uApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(uEmail == "" || manValidarEmail(uEmail)){
        error = "si";
        
        if(uEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(uEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
    
    if(uCargo == ""){
        $('oCargo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oCargo').setStyle('display', 'none');
    }
    
    if(uOrganizacion == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }       
                            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        $(botonId).click();       
    }        
    
}


function manValidarFormularioUsuariosEdicion(inputRut, inputNombres, inputApellidos, inputEmail, inputCargo, inputOrganizacion, botonId){
    
    uRut = $(inputRut).value;
    uNombres = $(inputNombres).value;
    uApellidos = $(inputApellidos).value;
    uEmail = $(inputEmail).value;
    uCargo = $(inputCargo).value;
    uOrganizacion = $(inputOrganizacion).value;
        
    error = "";
    
    
    if(uRut == "" || manValidarRut(uRut)){
        
        if(uRut == ""){
            $('oRut').setStyle('display', '');
            error = "si";
        }
        else{
            $('oRut').setStyle('display', 'none');
            error = "no";
        }
        
        if(manValidarRut(uRut)){
            $('oRut2').setStyle('display', '');
            error = "si";
        }
        else{
            $('oRut2').setStyle('display', 'none');
            error = "no";
        }
        
    }
    else{
        $('oRut').setStyle('display', 'none');
        $('oRut2').setStyle('display', 'none');
    }
    
    if(uNombres == ""){
        $('oNombres').setStyle('display', '');
        error = "si";
    }
    else{
        $('oNombres').setStyle('display', 'none');
    }        
    
    if(uApellidos == ""){
        $('oApellidos').setStyle('display', '');
        error = "si";
    }
    else{
        $('oApellidos').setStyle('display', 'none');
    }
    
    if(uEmail == "" || manValidarEmail(uEmail)){
        error = "si";
        
        if(uEmail == ""){
            $('oEmail').setStyle('display', '');
        }
        else{
            $('oEmail').setStyle('display', 'none');
        }
        
        if(manValidarEmail(uEmail)){
            $('oEmail2').setStyle('display', '');
        }
        else{
            $('oEmail2').setStyle('display', 'none');
        }        
        
    }
    else{
        $('oEmail').setStyle('display', 'none');
        $('oEmail2').setStyle('display', 'none');
    }
    
    if(uCargo == ""){
        $('oCargo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oCargo').setStyle('display', 'none');
    }
    
    if(uOrganizacion == ""){
        $('oOrganizacion').setStyle('display', '');
        error = "si";
    }
    else{
        $('oOrganizacion').setStyle('display', 'none');
    }       
                            
    if(error == "si"){
        alert("Complete los campos solicitados.");
    }
    else{
        estado = $('usuarioEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El usuario se encuentra inactivo: ¿Desea activarlo?")) {
                $('usuarioEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }       
    }        
    
}


function manValidarEmail(valor) {    
    var filtro = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
    if (filtro.test(valor))
    {                      
        return false;
    }
    else{
        return true;
    }           
}


function manValidarRut(valor) {    
    //var filtro = /^(\d{2}\.\d{3}\.\d{3}-)([a-zA-Z]{1}$|\d{1}$)/;
    var filtro = /^(\d{2}\d{3}\d{3}-)([a-zA-Z]{1}$|\d{1}$)/;
    if (filtro.test(valor) || valor == '')
    {                      
        return false;
    }
    else{
        return true;
    }      
   
}


function manMostrarSectores(inputOrg, inputSec){
    
    var nombreOrg = ($(inputOrg).get('value'));
    
    do{
        nombreOrg = nombreOrg.replace("'","");    
    }while(nombreOrg.indexOf("'") >= 0);
    
    
    do{
        nombreOrg = nombreOrg.replace('"','');
    }while(nombreOrg.indexOf('"') >= 0);
    
        
    new Autocompleter.Request.HTML($(inputSec), '../librerias/autocompleterMantenedores.php', {
        // class added to the input during request                                        
        'indicatorClass': 'autocompleter-loading', 
        'minLength': 2,
        'overflow': true,
        'selectMode': 'type-ahead',                                        
                            
        // send additional POST data, check the PHP code
        'postData': {
            'nombre_id'		:	'sectorId',
            'nombre_campo' 	: 	'sectorNombre',
            'nombre_tablas' 	:       'From Sectores s (nolock) inner join Organizaciones o (nolock) on s.organizacionId = o.organizacionId',
            'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1"
        }
    }); 
}


function manMostrarAreas(inputOrg, inputSec, inputAr){
    
    var nombreOrg = ($(inputOrg).get('value'));
    var nombreSec = ($(inputSec).get('value'));
    
    do{
        nombreOrg = nombreOrg.replace("'","");    
    }while(nombreOrg.indexOf("'") >= 0);
    
    
    do{
        nombreOrg = nombreOrg.replace('"','');
    }while(nombreOrg.indexOf('"') >= 0);
    
    do{
        nombreSec = nombreSec.replace("'","");    
    }while(nombreSec.indexOf("'") >= 0);
    
    
    do{
        nombreSec = nombreSec.replace('"','');
    }while(nombreSec.indexOf('"') >= 0);
    
    new Autocompleter.Request.HTML($(inputAr), '../librerias/autocompleterMantenedores.php', {
        // class added to the input during request                                        
        'indicatorClass': 'autocompleter-loading', 
        'minLength': 2,
        'overflow': true,
        'selectMode': 'type-ahead',                                        
                            
        // send additional POST data, check the PHP code
        'postData': {
            'nombre_id'		:	'areaId',
            'nombre_campo' 	: 	'areaNombre',
            'nombre_tablas' 	:       'From Areas a (nolock) inner join Sectores s (nolock) on a.sectorId = s.sectorId inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId',
            'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and s.sectorNombre like '" + nombreSec + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1 and a.areaEstado = 1"
        }
    }); 
}


function manLimpiarInputs(input1, input2, input3){
    
    if(input1 != ""){
        document.getElementById(input1).value = "";
    }
    
    if(input2 != ""){
        document.getElementById(input2).value = "";
    }
    
    if(input3 != ""){
        document.getElementById(input3).value = "";
    }

}



/*** Buscadores Lupas ***/



function manSeleccionarOrganizaciones(){
    
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarOrganizaciones.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentOrg').set('html',datos);                                                            
                $('mBoxContainerOrg').tween(property, to);                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();                              
    
}


function manSeleccionarOrganizacion(inputOrg){
        
    tabla = $('tablaListaOrganizaciones');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];                                     
            $(inputOrg).set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerOrg').tween("opacity", 0);                            
    
}


function manSeleccionarSectores(inputOrg){
    
    var organizacionNombre = ($(inputOrg).get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarSectores.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentSec').set('html',datos);                                                            
                $('mBoxContainerSec').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre));  
                                
}


function manSeleccionarSector(inputSec, inputOrg){
        
    tabla = $('tablaListaSectores');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objOrg = tabla.rows[i].cells[1];
            objSec = tabla.rows[i].cells[2];
            $(inputOrg).set('value', ((objOrg.innerText != undefined) ? objOrg.innerText : objOrg.textContent ));
            $(inputSec).set('value', ((objSec.innerText != undefined) ? objSec.innerText : objSec.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerSec').tween("opacity", 0);                            
    
}


function manSeleccionarAreas(inputOrg, inputSec){
    
    var organizacionNombre = ($(inputOrg).get('value'));
    var sectorNombre = ($(inputSec).get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarAreas.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentAre').set('html',datos);                                                            
                $('mBoxContainerAre').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre) + 
        "&sectorNombre=" + encodeURIComponent(sectorNombre)
        );  
}


function manSeleccionarArea(inputArea, inputSec, inputOrg){
        
    tabla = $('tablaListaAreas');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objOrg = tabla.rows[i].cells[1];
            objSec = tabla.rows[i].cells[2];
            objArea = tabla.rows[i].cells[3];
            
            $(inputOrg).set('value', ((objOrg.innerText != undefined) ? objOrg.innerText : objOrg.textContent ));
            $(inputSec).set('value', ((objSec.innerText != undefined) ? objSec.innerText : objSec.textContent ));
            $(inputArea).set('value', ((objArea.innerText != undefined) ? objArea.innerText : objArea.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerAre').tween("opacity", 0);                            
    
}


function manSeleccionarTodo(valor, nombretabla){
    tabla=$(nombretabla);
    largo=tabla.rows.length;    
    for (i=1;i<largo;i++){
        
        if(tabla.rows[i].cells[0] != undefined){             
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked=valor;
        }
    }
}


function manSeleccionarPerfiles(){
    
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarPerfiles.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentPer').set('html',datos);                                                            
                $('mBoxContainerPer').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();                              
    
}


function manSeleccionarPerfil(inputPer){
        
    tabla = $('tablaListaPerfiles');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $(inputPer).set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerPer').tween("opacity", 0);                            
    
}


function manSeleccionarCargos(){
    
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarCargos.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentCar').set('html',datos);                                                            
                $('mBoxContainerCar').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();                              
    
}


function manSeleccionarCargo(inputCar){
        
    tabla = $('tablaListaCargos');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $(inputCar).set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerCar').tween("opacity", 0);                            
    
}

/*************************************/
/*************************************/
/*************************************/


/*** Guardar Grupo ***/


function manBuscarUsuarios(inputOrg, inputSec, inputArea){                       
             
    document.getElementById('tablaBuscarCarga').style.display = "";             
             
    var nombreOrg = ($(inputOrg).get('value'));
    var nombreSec = ($(inputSec).get('value'));
    var nombreArea = ($(inputArea).get('value'));  
    var ids = "";
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                ids = ids + objInputUsuarioId.value + ",";

            }
        }
    }        
    
    ids = ids.substring(0,ids.length-1);
    
    var elRequest = new Request({
        url         : 'manFuncionBuscarUsuarios.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('ListaUsuarios').set('html',datos);
                fdTableSort.init('ListadeUsuarios');
                                                               
                if($('ListadeUsuarios').rows.length > 1){
                    $('aAgregarUsuarios').setStyle('display', '');
                }
                else{
                    $('aAgregarUsuarios').setStyle('display', 'none');
                }
                
                document.getElementById('tablaBuscarCarga').style.display = "none";
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            document.getElementById('tablaBuscarCarga').style.display = "none";
        }
    });

    elRequest.send("organizacionNombre=" + encodeURIComponent(nombreOrg) +
        "&sectorNombre=" + encodeURIComponent(nombreSec) +
        "&areaNombre=" + encodeURIComponent(nombreArea) + 
        "&ids=" + encodeURIComponent(ids)
        );
    
    return false;
}


function manSeleccionarUsuarios(){
    
    tabla = $('listaUsuariosSeleccionados');                
    filas = tabla.rows.length;
    var x = tabla.insertRow(filas);
   

    tabla2 = $('ListadeUsuarios');                
    filas2 = tabla2.rows.length;
    
    for(i = 1; i < filas2; i++){   
        
        if(tabla2.rows[i].cells[0] != undefined)
        {
            objInput0 = tabla2.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInputUsuarioId = tabla2.rows[i].cells[0].getElementsByTagName('input')[1];
                objInputNombre = tabla2.rows[i].cells[1].innerHTML;
                objInputOrg = tabla2.rows[i].cells[2].innerHTML;
                objInputSec = tabla2.rows[i].cells[3].innerHTML;
                objInputAre = tabla2.rows[i].cells[4].innerHTML;
                objInputCar = tabla2.rows[i].cells[5].innerHTML;
                                                                       
                /** Creamos la nueva fila **/
            
                x = tabla.tBodies[0].insertRow(-1);
                 
                var y = x.insertCell(0);
                y.style.width = "28px";
                y.align = "center";
                y.innerHTML = "<input type='checkbox'/><input id='usuarioId' type='hidden' value='" + objInputUsuarioId.value + "'/>";

                var y1 = x.insertCell(1);            
                y1.style.width = "179px";
                y1.innerHTML = objInputNombre;               

                var y2 = x.insertCell(2);            
                y2.style.width = "179px";
                y2.innerHTML = objInputOrg;
        
                var y3 = x.insertCell(3);            
                y3.style.width = "179px";
                y3.innerHTML = objInputSec;
                                                                                      
                var y4 = x.insertCell(4);            
                y4.style.width = "179px";
                y4.innerHTML = objInputAre;                                                
            
                var y5 = x.insertCell(5);            
                y5.style.width = "179px";
                y5.innerHTML = objInputCar;
        
                /** Borramos la fila de la tabla **/
            
                var td = objInput0.parentNode;
                var tr = td.parentNode;
                var table = tr.parentNode;
                table.removeChild(tr);
             
                filas2 = tabla2.rows.length;
                i = i - 1;
        
            }
        }
    }  
        
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    total = 0;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                total = total + 1;
            }
        }
    } 
    
    if(total > 0){
        $('aDescartarUsuarios').setStyle('display', '');
    }
    else{
        $('aDescartarUsuarios').setStyle('display', 'none');
    }
                
}


function manDescartarUsuarios(){

    tabla = $('listaUsuariosSeleccionados').tBodies[0];      
    filas = tabla.rows.length;         
    
    for(i = 0; i < filas; i++){   
        
        if(tabla.rows[i].cells[0] != undefined)
        {
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){                    
        
                /** Borramos la fila de la tabla **/
            
                var td = objInput0.parentNode;
                var tr = td.parentNode;
                var table = tr.parentNode;
                table.removeChild(tr);
             
                filas = tabla.rows.length;
                i = i - 1;
        
            }
        }
    }  
    
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    total = 0;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                total = total + 1;
            }
        }
    } 
    
    if(total > 0){
        $('aDescartarUsuarios').setStyle('display', '');
    }
    else{
        $('aDescartarUsuarios').setStyle('display', 'none');
    }

}


function manValidarFormularioGrupos(inputGrupo, botonId){

    grupoNombre = $(inputGrupo).value;    
    
    error = "";
        
    if(grupoNombre == ""){
        $('oGrupo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oGrupo').setStyle('display', 'none');
    }   
    
    /*******************************************************/
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    total = 0;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                total = total + 1;
            }
        }
    } 
    
    if(total == 0){
        $('oUsuarios').setStyle('display', '');
        error = "si";
    }
    else{
        $('oUsuarios').setStyle('display', 'none');
    }
      
    
        
    if(error == "si"){
        alert("Complete los datos solicitados.");
    }
    else{
        $(botonId).click();       
    }
}
    

function manGuardarGrupo(gruNombre, gruDescripcion){
                            
    document.getElementById('tablaCargando').style.display = ""; 
    
    var grupoNombre = $(gruNombre).value;
    var grupoDescripcion = $(gruDescripcion).value;
    
    manGuardarGrupoCabecera(grupoNombre, grupoDescripcion);
    
    var grupoId = document.getElementById('grupoId').value;       

    if(!isNaN(parseInt(grupoId)))
    {                 
        tabla = $('listaUsuariosSeleccionados').tBodies[0];
        filas = tabla.rows.length;         

        for(i = 0; i < filas; i++){   

            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                manGuardarGrupoUsuario(grupoId, objInputUsuarioId.value);               
            }
        }

        window.location='../mantenedores/manGrupos.php';
        
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none"; 
        
        if(grupoId == "B"){
            alert("El nombre del grupo ya existe.");
        }
        else{
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    }
        
}


function manGuardarGrupoCabecera(grupoNombre, grupoDescripcion){
                   
    var elRequest = new Request({
        url         : 'manFuncionGuardarGrupo.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {                                  
             $('grupoId').value = datos.trim();                                      
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });


    elRequest.send("grupoNombre=" + encodeURIComponent(grupoNombre) +
        "&grupoDescripcion=" + encodeURIComponent(grupoDescripcion)
        );            
    
    return false;
}


function manGuardarGrupoUsuario(grupoId, usuarioId){
    
    var elRequest = new Request({
        url         : 'manFuncionGuardarGrupoUsuario.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos == "A")
            {                
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("grupoId=" + grupoId +
        "&usuarioId=" + usuarioId
        );
    
    return false;
}


/**********************************/
/********** Editar Grupo **********/
/**********************************/


function manValidarFormularioGruposEdicion(inputGrupo, botonId){

    grupoNombre = $(inputGrupo).value;    
    
    error = "";
        
    if(grupoNombre == ""){
        $('oGrupo').setStyle('display', '');
        error = "si";
    }
    else{
        $('oGrupo').setStyle('display', 'none');
    }   
    
    /*******************************************************/
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    total = 0;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                total = total + 1;
            }
        }
    } 
    
    if(total == 0){
        $('oUsuarios').setStyle('display', '');
        error = "si";
    }
    else{
        $('oUsuarios').setStyle('display', 'none');
    }
      
    
        
    if(error == "si"){
        alert("Complete los datos solicitados.");
    }
    else{
        estado = $('grupoEstado').value;
        
        if(estado == "0"){
                
            if(confirm("El grupo se encuentra inactivo: ¿Desea activarlo?")) {
                $('grupoEstado').value = "1";
                $(botonId).click();
            }
            else{
                $(botonId).click();          
            }            
        }
        else{
            $(botonId).click();          
        }      
    }

}


function manEditarGrupo(gruNombre, gruDescripcion){
          
    document.getElementById('tablaCargando').style.display = "";      
          
    var grupoNombre = $(gruNombre).value;
    var grupoDescripcion = $(gruDescripcion).value;
    var grupoId = $('grupoId').value;
    var grupoEstado = $('grupoEstado').value;            
    var ids = "";
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                ids = ids + objInputUsuarioId.value + ",";

            }
        }
    }        
    
    ids = ids.substring(0,ids.length-1);     
    
    manEditarGrupoCabecera(grupoId, grupoNombre, grupoDescripcion, ids, grupoEstado);
   
    var resultado = document.getElementById('resultadoEdicion').value;      
    
    if(!isNaN(parseInt(resultado)))
    {                 
        tabla = $('listaUsuariosSeleccionados').tBodies[0];
        filas = tabla.rows.length;         

        for(i = 0; i < filas; i++){   

            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                manGuardarGrupoUsuario(grupoId, objInputUsuarioId.value);               
            }
        }

        window.location='../mantenedores/manGrupos.php';
        
    }
    else
    {                
        document.getElementById('tablaCargando').style.display = "none"; 
        
        if(resultado == "B"){
            alert("El nombre del grupo ya existe.");
        }
        else{
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    }
        
}


function manEditarGrupoCabecera(grupoId, grupoNombre, grupoDescripcion, ids, grupoEstado){
                   
    var elRequest = new Request({
        url         : 'manFuncionEditarGrupo.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {             
            $('resultadoEdicion').value = datos.trim();                                                               
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });


    elRequest.send("grupoId=" + encodeURIComponent(grupoId) +
        "&grupoNombre=" + encodeURIComponent(grupoNombre) +
        "&grupoDescripcion=" + encodeURIComponent(grupoDescripcion) + 
        "&grupoEstado=" + encodeURIComponent(grupoEstado) + 
        "&ids=" + encodeURIComponent(ids)        
        );            
    
    return false;
}


function manDisableEnterKey(e){
    var key;
    if(window.event){
        key = window.event.keyCode; //IE
    }else{
        key = e.which; //firefox
    }
    if(key==13){
        return false;
    }else{
        return true;
    }
}