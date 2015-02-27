

function evaMostrarEvaluaciones(){
    
    var nombreTema = ($('evaTemaNombre').get('value'));
           
    new Autocompleter.Request.HTML($('evaEvaluacionNombre'), '../librerias/autocompleterMantenedores.php', {
        // class added to the input during request                                        
        'indicatorClass': 'autocompleter-loading', 
        'minLength': 2,
        'overflow': true,
        'selectMode': 'type-ahead',                                        
                            
        // send additional POST data, check the PHP code
        'postData': {
            'nombre_id'		:	'evaluacionId',
            'nombre_campo' 	: 	'evaluacionNombre',
            'nombre_tablas' 	:       'From Evaluaciones e (nolock) inner join Temas t (nolock) on e.temaId = t.temaId',
            'nombre_where'      :       "and e.clienteId = " + $('clienteId').get('value') + " and t.temaNombre like '" + nombreTema + "%' and e.administradorId = " + $('administradorId').get('value') 
        }
    }); 
    
}

/*****************************************************************************/
/************************   Insertar Evaluacion   ****************************/
/*****************************************************************************/


function evaSeleccionarTemas(){
            
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarTemas.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentTema').set('html',datos);                                                            
                $('mBoxContainerTema').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();  
}


function evaSeleccionarTema(){
        
    tabla = $('tablaListaTemas');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('temaNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerTema').tween("opacity", 0);                            
    
}


function evaSeleccionarTodo(valor, tabla){
    tabla=$(tabla);
    largo=tabla.rows.length;    
    for (i=1;i<largo;i++){
        
        if(tabla.rows[i].cells[0] != undefined)
        {
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked = valor;
        }
    }
}


function evaSeleccionarCapsula(){
    
    var ids = "";
    
    tabla = $('tablaCapsulas');                
    filas = tabla.rows.length;
    
    if(filas > 1){
        
        for(i = 1; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                ids = ids + objInputCapsulaId.value + ",";
            }
        }
    }       

    ids = ids.substring(0,ids.length-1);
    
    if($('temaNombre').value != "")
    {
        var property = 'opacity';
        var to = "1";
                               
        var nombreTema = ($('temaNombre').get('value'));           
    
        var elRequest = new Request({
            url         : 'evaFuncionBuscarCapsulas.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('BoxContent').set('html',datos);                                                            
                    $('mBoxContainer').tween(property, to);
                    
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });


        elRequest.send("nombreTema=" + encodeURIComponent(nombreTema) + 
                       "&ids=" + encodeURIComponent(ids));                          
    }
    else
    {
        alert("Seleccione el tema.");
    }
}


function evaAgregarCapsulas(){                
               
    tabla = $('tablaCapsulas');                
    filas = tabla.rows.length;
    //var x = tabla.insertRow(filas);
    uf = 0; //ultima fila
    
    for(i = 1; i < filas; i++){
        if(tabla.rows[i].cells[0] != undefined){            
            uf = i;
        }
    }
        
    if(uf != 0)
    {
        texto = tabla.rows[uf].cells[0].getElementsByTagName('input')[0];
        filas = (parseInt(texto.id) + 1);
    }else{
        filas = 1;
    }
    
    tabla2 = $('tablaListaCapsulas');                
    filas2 = tabla2.rows.length;
    
    for(i = 1; i < filas2; i++){
        objInput0 = tabla2.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
                    
            objCapsulaNombre = tabla2.rows[i].cells[2].innerHTML;
            objCapsulaId = tabla2.rows[i].cells[3].getElementsByTagName('input')[0];
                 
            x = tabla.insertRow(-1);
                 
            var y = x.insertCell(0);
            y.innerHTML = "<input id='" + filas + "' type='checkbox'/><input id='capsulaId-" + filas + "' type='hidden' value='" + objCapsulaId.value + "'/>";

            var y1 = x.insertCell(1);
            y1.innerHTML = objCapsulaNombre;
    
            var y2 = x.insertCell(2);
            y2.innerHTML = '<input id="pond-cap-' + filas + '" type="text" style="width:40px; text-align:right" maxlength="3" onkeypress="return evaSoloNumeros(event);" onBlur="evaSoloNumerosEnteros(this.id); evaCalcularPonderacion(this.id)"/> %';
                                    
            filas = filas + 1;
        }
    }

    tabla = $('tablaCapsulas');
    filas = tabla.rows.length;

    if(filas > 1)
    {             
        $('aEliminarCap').setStyle('display', '');
        //$('tablaCapsulas').setStyle('display', '');
        document.getElementById('temaNombre').disabled = true;
        document.getElementById('lupaTema').style.display = "none";
    } 
    
    $('mBoxContainer').tween("opacity", 0);
        
}


function evaSeleccionarTodoCapsulas(valor){
    tabla = document.getElementById('tablaListaCapsulas');
    largo=tabla.rows.length;
    for (i=1;i<largo;i++){
        
        if(tabla.rows[i].cells[0] != undefined)
        {
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked = valor;
        }
    }
}


function evaSoloNumeros(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ( keynum == 8 ) return true;
    return /\d/.test(String.fromCharCode(keynum));
}


function evaSeleccionar(id){
    document.getElementById(id).select();
}


function evaSoloNumerosEnteros(objId){
               
    var valorData = document.getElementById(objId).value;    
    
    if(valorData > 100){
        alert('Las ponderaciones no pueden superar el 100%.');
        document.getElementById(objId).value = 0;
    }
    else{        
        document.getElementById(objId).value = valorData * 1;
    }        
}


function evaAgregarPractica(){

    if($('temaNombre').value != "")
    {
        tabla = $('tablaPracticas');
        filas = tabla.rows.length;
        var x = tabla.insertRow(filas);        
        uf = 0; //ultima fila

        for(i = 1; i < filas; i++){
            if(tabla.rows[i].cells[0] != undefined){
                uf = i;
            }
        }

        if(uf != 0)
        {
            texto = tabla.rows[uf].cells[0].getElementsByTagName('input')[0];            
            objid = (texto.id).split("-");            
            filas = (parseInt(objid[1]) + 1);
        } else{
            filas = 1
            uf = 1;
        }
        

        var y = x.insertCell(0);
        y.innerHTML = "<input id='prac-" + filas + "' type='checkbox'/><input id='practicaId-" + filas + "' type='hidden' value='0'/>";

        var y1 = x.insertCell(1);
        y1.innerHTML = "<input id='item-" + filas + "' type='text' style='width:340px;'/>";

        var y2 = x.insertCell(2);
        y2.innerHTML = "<input id='pond-item-" + filas + "' type='text' style='width:40px; text-align:right' onkeypress='return evaSoloNumeros(event);' onblur='evaSoloNumerosEnteros(this.id); evaCalcularPonderacion(this.id)'/>%";
                
        
        if(uf != 0)
        {                        
            //$('tablaPracticas').setStyle('display', '');
            $('aEliminarPrac').setStyle('display', '');
            document.getElementById('temaNombre').disabled = true;
            document.getElementById('lupaTema').style.display = "none";
        }       
    }
    else
    {
        alert("Seleccione el tema.");
    }

}


function evaEliminarFila(tablaSelect){
            
    tabla = $(tablaSelect);
    filas = tabla.rows.length;                   

    for(i = 1; i < filas; i++){
        
        if(tabla.rows[i].cells[0] != undefined){
        
            objCheked = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
       
            if(objCheked.checked == true){
                                                                                                                                                                    
                var td = objCheked.parentNode;
                var tr = td.parentNode;
                var table = tr.parentNode;
                table.removeChild(tr);

                filas = tabla.rows.length;
                i = i - 1;
            }
        }
//        else{
//            tabla.deleteRow(i);
//            filas = tabla.rows.length;
//            i = i - 1;
//        }
    }

    tablaCap = $('tablaCapsulas');    
    filasCap = tablaCap.rows.length;    
    totalCap = 0;
    
    for(i = 1; i < filasCap; i++){
        if(tablaCap.rows[i].cells[0] != undefined){
            totalCap = totalCap + 1;
        }
    }
    
    if(totalCap == 0){
        $('aEliminarCap').setStyle('display', 'none');
        //$('tablaCapsulas').setStyle('display', 'none');
    }          
    
    tablaPrac = $('tablaPracticas');    
    filasPrac = tablaPrac.rows.length;    
    totalPrac = 0;
    
    for(i = 1; i < filasPrac; i++){
        if(tablaPrac.rows[i].cells[0] != undefined){
            totalPrac = totalPrac + 1;
        }
    }
    
    if(totalPrac == 0){
        $('aEliminarPrac').setStyle('display', 'none');
        //$('tablaPracticas').setStyle('display', 'none');
    }
    
    if(totalCap == 0 && totalPrac == 0){
        document.getElementById('temaNombre').disabled = false;
        document.getElementById('lupaTema').style.display = "";
    }
    
    evaCalcularPonderacion();
}


function evaCalcularPonderacion(id){
    
    tabla = $('tablaCapsulas');
    filas = tabla.rows.length;
    total = 0;

    for(i = 1; i < filas; i++){
        
        if(tabla.rows[i].cells[0] != undefined){
        
            objPonderacion = tabla.rows[i].cells[2].getElementsByTagName('input')[0];

            if(!isNaN(parseInt(objPonderacion.value)))
            {
                total = total + (parseInt(objPonderacion.value));
            }
        }
    }

    tabla = $('tablaPracticas');
    filas = tabla.rows.length;

    for(i = 1; i < filas; i++){
        
        if(tabla.rows[i].cells[0] != undefined){
        
            objPonderacion = tabla.rows[i].cells[2].getElementsByTagName('input')[0];

            if(!isNaN(parseInt(objPonderacion.value)))
            {
                total = total + (parseInt(objPonderacion.value));
            }
        }
    }

    if(total <= 100){
        $('totalPorcentaje').value = total;
    }
    else{
        if(id != ""){
        
            document.getElementById(id).value = 0;
            alert("La ponderación total no debe superar el 100%");        
        }
    }        
}


function evaLimpiarInputs(input1, input2, input3){
    
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


function evaMostrarSectores(inputOrg, inputSec){
    
    var nombreOrg = ($(inputOrg).get('value'));
           
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


function evaMostrarAreas(inputOrg, inputSec, inputArea){                   
    
    var nombreOrg = ($(inputOrg).get('value'));
    var nombreSec = ($(inputSec).get('value'));                  
      
    new Autocompleter.Request.HTML($(inputArea), '../librerias/autocompleterMantenedores.php', {
        // class added to the input during request                                        
        'indicatorClass': 'autocompleter-loading', 
        'minLength': 2,
        'overflow': true,                   
                                    
        // send additional POST data, check the PHP code
        'postData': {
            'nombre_id'		:	'areaId',
            'nombre_campo' 	: 	'areaNombre',
            'nombre_tablas' 	:       'From Areas a (nolock) inner join Sectores s (nolock) on a.sectorId = s.sectorId inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId',
            'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and s.sectorNombre like '" + nombreSec + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1 and a.areaEstado = 1"
        }
        
        
    }); 
}


function evaSeleccionarOrganizaciones(){
    
    var property = 'opacity';
    var to = "1";                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarOrganizaciones.php',
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


function evaSeleccionarOrganizacion(){
        
    tabla = $('tablaListaOrganizaciones');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('evaOrganizacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerOrg').tween("opacity", 0);                            
    
}


function evaSeleccionarSectores(){
    
    var organizacionNombre = ($('evaOrganizacionNombre').get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarSectores.php',
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


function evaSeleccionarSector(){
        
    tabla = $('tablaListaSectores');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objOrg = tabla.rows[i].cells[1];
            objSec = tabla.rows[i].cells[2];
            
            $('evaOrganizacionNombre').set('value', ((objOrg.innerText != undefined) ? objOrg.innerText : objOrg.textContent ));
            $('evaSectorNombre').set('value', ((objSec.innerText != undefined) ? objSec.innerText : objSec.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerSec').tween("opacity", 0);                            
    
}


function evaSeleccionarAreas(){
    
    var organizacionNombre = ($('evaOrganizacionNombre').get('value'));
    var sectorNombre = ($('evaSectorNombre').get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarAreas.php',
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


function evaSeleccionarArea(){
        
    tabla = $('tablaListaAreas');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objOrg = tabla.rows[i].cells[1];
            objSec = tabla.rows[i].cells[2];
            objAre = tabla.rows[i].cells[3];
                    
            $('evaOrganizacionNombre').set('value', ((objOrg.innerText != undefined) ? objOrg.innerText : objOrg.textContent ));
            $('evaSectorNombre').set('value', ((objSec.innerText != undefined) ? objSec.innerText : objSec.textContent ));
            $('evaAreaNombre').set('value', ((objAre.innerText != undefined) ? objAre.innerText : objAre.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerAre').tween("opacity", 0);                            
    
}


function evaSeleccionarGrupos(){
            
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'evaFuncionBuscarGrupos.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentGru').set('html',datos);                                                            
                $('mBoxContainerGru').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();  
}


function evaSeleccionarGrupo(){
        
    tabla = $('tablaListaGrupos');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('evaGrupoNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerGru').tween("opacity", 0);                            
    
}


function evaBusquedaOrg(){
    
    $('evaOrganizacionNombre').disabled = false; 
    $('evaSectorNombre').disabled = false; 
    $('evaAreaNombre').disabled = false;         
    $('evaGrupoNombre').disabled = true;     
    $('evaGrupoNombre').value = "";     
    
    $('lupaOrg').setStyle('display', '');
    $('lupaSec').setStyle('display', '');
    $('lupaArea').setStyle('display', '');
    $('lupaGru').setStyle('display', 'none');     
}


function evaBusquedaGru(){
    
    $('evaOrganizacionNombre').disabled = true; 
    $('evaSectorNombre').disabled = true; 
    $('evaAreaNombre').disabled = true; 
    $('evaGrupoNombre').disabled = false;     
    $('evaOrganizacionNombre').value = ""; 
    $('evaSectorNombre').value = ""; 
    $('evaAreaNombre').value = "";       
    
    $('lupaOrg').setStyle('display', 'none');
    $('lupaSec').setStyle('display', 'none');
    $('lupaArea').setStyle('display', 'none');
    $('lupaGru').setStyle('display', '');     
    
}


function evaBuscarUsuarios(){                       
    
    document.getElementById('tablaBuscarCarga').style.display = "";                                 
    
    var radOrg = $('radioOrg');
    var radGru = $('radioGru');    
    var busqueda = "";
    
    if(radOrg.checked == true){
        busqueda = "Org";
    }
    
    if(radGru.checked == true){
        busqueda = "Gru";
    }
                                    
    var nombreOrg = ($('evaOrganizacionNombre').get('value'));
    var nombreSec = ($('evaSectorNombre').get('value'));
    var nombreArea = ($('evaAreaNombre').get('value'));
    var nombreGrup = ($('evaGrupoNombre').get('value'));       
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
        url         : 'evaFuncionBuscarUsuarios.php',
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
        "&grupoNombre=" + encodeURIComponent(nombreGrup) +        
        "&tipoBusqueda=" + busqueda + 
        "&ids=" + encodeURIComponent(ids)
        );
    
    return false;
}


function evaSeleccionarUsuarios(){
    
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
                objInputEmail = tabla2.rows[i].cells[2].innerHTML;
                objInputOrg = tabla2.rows[i].cells[3].innerHTML;
                objInputSec = tabla2.rows[i].cells[4].innerHTML;
                objInputAre = tabla2.rows[i].cells[5].innerHTML;
                objInputCar = tabla2.rows[i].cells[6].innerHTML;
                                                                       
                /** Creamos la nueva fila **/
            
                x = tabla.tBodies[0].insertRow(-1);
                 
                var y = x.insertCell(0);
                y.style.width = "33px";
                y.align = "center";
                y.innerHTML = "<input type='checkbox'/><input id='usuarioId' type='hidden' value='" + objInputUsuarioId.value + "'/>";

                var y1 = x.insertCell(1);            
                y1.style.width = "147px";
                y1.innerHTML = objInputNombre;               

                var y2 = x.insertCell(2);            
                y2.style.width = "147px";
                y2.innerHTML = objInputEmail;

                var y3 = x.insertCell(3);            
                y3.style.width = "147px";
                y3.innerHTML = objInputOrg;
        
                var y4 = x.insertCell(4);            
                y4.style.width = "146px";
                y4.innerHTML = objInputSec;
                                                                                      
                var y5 = x.insertCell(5);            
                y5.style.width = "147px";
                y5.innerHTML = objInputAre;                                                
            
                var y6 = x.insertCell(6);            
                y6.style.width = "147px";
                y6.innerHTML = objInputCar;
        
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


function evaDescartarUsuarios(){

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


function evaValidarFormularioEvaluacion(){

    estado = "";   
    valor = "0";        
                            
    /******************************/
    /******** Evaluacion  *********/
    /******************************/                            
                            
    if($('evaluacionNombre').value == ""){
        $('oEvaluacion').setStyle('display', '');
        estado = "error";   
    }
    else{
        $('oEvaluacion').setStyle('display', 'none');
    }

    /******************************/
    /*********** Tema  ************/
    /******************************/
    
    if($('temaNombre').value == ""){
        $('oTema').setStyle('display', '');
        estado = "error";    
    }
    else{
        $('oTema').setStyle('display', 'none');
    }
        
    /******************************/
    /********* Capsulas  **********/
    /******************************/

    tablaCap = $('tablaCapsulas');
    filasCap = tablaCap.rows.length;
    blancos = "";
    totalCap = 0;
    
    for(i = 1; i < filasCap; i++){        
        
        if(tablaCap.rows[i].cells[0] != undefined)
        {                                 
            totalCap = totalCap + 1;
            objCapPonderacion = tablaCap.rows[i].cells[2].getElementsByTagName('input')[0];

            if(objCapPonderacion.value == "" || objCapPonderacion.value == "0")
            {
                blancos = "si";
                i = filasCap;
            }            
        }
    }

    if(blancos == "si" || totalCap == 0){
        $('oCapsulas').setStyle('display', '');
        estado = "error";                
    }
    else{
        $('oCapsulas').setStyle('display', 'none');
    }
    
    /******************************/
    /********* Practicas  *********/
    /******************************/
        
    tablaPrac = $('tablaPracticas');
    filasPrac = tablaPrac.rows.length;
    blancos = "";
                        
    for(i = 1; i < filasPrac; i++){
        
        if(tablaPrac.rows[i].cells[1] != undefined)
        {        
            objPracNombre = tablaPrac.rows[i].cells[1].getElementsByTagName('input')[0];
            objPracPonderacion = tablaPrac.rows[i].cells[2].getElementsByTagName('input')[0];

            if(objPracNombre.value == "" || objPracPonderacion.value == "" || objPracPonderacion.value == "0")
            {
                blancos = "si";
                i = filasPrac;
            }
        }
    }

    if(blancos == "si"){
        $('oPracticas').setStyle('display', '');
        estado = "error";
    }
    else{
        $('oPracticas').setStyle('display', 'none');
    }
   
    /******************************/
    /** Validar porcentaje final **/
    /******************************/
    
    if(parseInt($('totalPorcentaje').value) == 0 || parseInt($('totalPorcentaje').value) != 100){
        $('oTotal').setStyle('display', '');        
        estado = "error";        
    }
    else {
        $('oTotal').setStyle('display', 'none');        
    } 
 
    /******************************/
    /*********** Usuarios *********/
    /******************************/

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
        estado = "error";
    }
    else{
        $('oUsuarios').setStyle('display', 'none');
    }
            

    if(estado == "error"){        
        alert("Complete los datos solicitados.");
    }
    else{
        $('btIngresarEva').click();
    }

}


function evaGuardarEvaluacion(){
     
    document.getElementById('tablaCargando').style.display = "";             
    
    var temaNombre = $('temaNombre').value;        
    var evaluacionNombre = $('evaluacionNombre').value;
    var evaluacionDescripcion = $('evaluacionDescripcion').value;
               
    evaGuardarCabecera(temaNombre, evaluacionNombre, evaluacionDescripcion);
               
    var evaluacionId = document.getElementById('evaluacionId').value;        
           
    
    if(!isNaN(parseInt(evaluacionId)))
    {  
        tablaCap = $('tablaCapsulas');
        filasCap = tablaCap.rows.length;    

        for(i = 1; i < filasCap; i++){        
            
            if(tablaCap.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tablaCap.rows[i].cells[0].getElementsByTagName('input')[1];
                objInputPonderacion = tablaCap.rows[i].cells[2].getElementsByTagName('input')[0];                                                
                
                evaGuardarCapsula(evaluacionId, objInputCapsulaId.value, objInputPonderacion.value, temaNombre);         
            }
        }
                
        tablaPrac = $('tablaPracticas');
        filasPrac = tablaPrac.rows.length;    

        for(i = 1; i < filasPrac; i++){
            
            if(tablaPrac.rows[i].cells[0] != undefined)
            {
                objPracticaNombre= tablaPrac.rows[i].cells[1].getElementsByTagName('input')[0];
                objPracticaPonderacion = tablaPrac.rows[i].cells[2].getElementsByTagName('input')[0];
                
                evaGuardarPractica(evaluacionId, i, objPracticaNombre.value, objPracticaPonderacion.value, temaNombre);
            }
        }
           
           
        tabla = $('listaUsuariosSeleccionados').tBodies[0];
        filas = tabla.rows.length;         

        for(i = 1; i < filas; i++){   

            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                evaGuardarUsuario(evaluacionId, objInputUsuarioId.value);
            }
        }   
                                                          
        window.location='../evaluaciones/evaEvaluaciones.php';
          
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none";
        
        if(evaluacionId == "B")
        {
            alert("El nombre de la evaluación ya existe.");
        }
        
        if(evaluacionId == "A")
        {
            alert("El tema seleccionado no existe.");
        }
        
        if(evaluacionId != "A" && evaluacionId != "B")
        {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }                
        
    }
        
}


function evaGuardarCabecera(temaNombre, evaluacionNombre, evaluacionDescripcion){
                   
    var elRequest = new Request({
        url         : 'evaFuncionGuardarCabecera.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos != null && datos!= ""){                             
                $('evaluacionId').value = datos.trim();
            }
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });


    elRequest.send("temaNombre=" + encodeURIComponent(temaNombre) +
        "&evaluacionNombre=" + encodeURIComponent(evaluacionNombre) +
        "&evaluacionDescripcion=" + encodeURIComponent(evaluacionDescripcion)
        );            
    
    return false;
}


function evaGuardarCapsula(evaluacionId, capsulaId, ponderacion, temaNombre){
    
    var elRequest = new Request({
        url         : 'evaFuncionGuardarCapsula.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {                                  
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error al guardar las cápsulas. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&capsulaId=" + capsulaId +
        "&ponderacion=" + ponderacion + 
        "&temaNombre=" + encodeURIComponent(temaNombre)
        );
    
    return false;
}


function evaGuardarPractica(evaluacionId, practicaId, itemNombre, ponderacion, temaNombre){
    
    
    var elRequest = new Request({
        url         : 'evaFuncionGuardarPractica.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {                                    
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error al guardar las notas prácticas. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&practicaId=" + practicaId +
        "&ponderacion=" + ponderacion + 
        "&temaNombre=" + encodeURIComponent(temaNombre) + 
        "&practicaNombre=" + encodeURIComponent(itemNombre)
        );
    
    return false;
}


function evaGuardarUsuario(evaluacionId, usuarioId){
    
    var elRequest = new Request({
        url         : 'evaFuncionGuardarUsuario.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {                                  
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error al guardar a los usuarios. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&usuarioId=" + usuarioId
        );
    
    return false;
}



/*****************************************************************************/
/************************    Ver Calificaciones   ****************************/
/*****************************************************************************/

/* Ver calificaciones*/

function evaCalificaciones(id){  
    
    document.getElementById('evaluacionIdCalificaciones').value = id;
    document.getElementById('btnVerCalificaciones').click();            
    
}   


function evaNumeroDecimal(input, tipo){
    
    var valor = document.getElementById(input).value;
    
    if(tipo == "1"){        
        filtro = /^[0-7]{1,1}(\.[0-9]{0,1})?$/;
    }
    else{
        filtro = /^[0-9]{1,3}(\.[0]{0,0})?$/;        
    }
                
    if (filtro.test(valor))
    {                      
        return true;
    }
    else{
        
        if(tipo == "1"){        
            document.getElementById(input).value = "0.0";
        }
        else{
            document.getElementById(input).value = "0";
        }
        
        return false;
    }           
    
    
    
}


function evaCalcularNotaFinal(tipo){
    
    tablaUsuarios = $('notasFinales');
    
    columnas = $('nColumnas').value;
    notaFinal = 0;
        
    if(tablaUsuarios != null)
    {
        filas = tablaUsuarios.rows.length;
    }
    else
    {
        filas = 0;
    }
                          
    for(i = 1; i < filas; i++){
        
        for(j = 0; j < columnas;j ++ ){
            
            ponderacion = tablaUsuarios.rows[0].cells[j + 3].getElementsByTagName('input')[0];
            nota = tablaUsuarios.rows[i].cells[j + 3].getElementsByTagName('input')[0];
                       
            notaFinal = notaFinal + (ponderacion.value * nota.value);
                                    
        }  
        id = "nf-" + i;
        
        if(tipo == "1"){
            document.getElementById(id).value = notaFinal.toFixed(1);            
        }
        else{
            document.getElementById(id).value = notaFinal.toFixed(0);            
        }
        
        notaFinal = 0;                                       
        
    }
       
}


function evaCalcularNotaFinalFila(fila, tipo){
            
    tablaUsuarios = $('notasFinales');
    
    columnas = $('nColumnas').value;
    notaFinal = 0;
        
    if(tablaUsuarios != null)
    {
        filas = tablaUsuarios.rows.length;
    }
    else
    {
        filas = 0;
    }
                          
            
    for(j = 0; j < columnas;j ++ ){
                
        ponderacion = tablaUsuarios.rows[0].cells[j + 3].getElementsByTagName('input')[0];
        nota = tablaUsuarios.rows[fila].cells[j + 3].getElementsByTagName('input')[0];

        notaFinal = notaFinal + (ponderacion.value * nota.value);

    }  
    id = "nf-" + fila;
              
    if(tipo == "1"){
        document.getElementById(id).value = notaFinal.toFixed(1);            
    }
    else{
        document.getElementById(id).value = notaFinal.toFixed(0);            
    }
        
    document.getElementById(fila).value = "1";    
       
}


function evaMascaraNotas(d,sep,nums){
           
    var pat = new Array(1,1)    
    
    if(d.valant != d.value){
        val = d.value;
        largo = val.length;
        val = val.split(sep);
        val2 = '';
        
        for(r=0;r<val.length;r++){
            val2 += val[r];
        }
        
        if(nums){
            for(z=0;z<val2.length;z++){
                if(isNaN(val2.charAt(z))){
                    letra = new RegExp(val2.charAt(z),"g");
                    val2 = val2.replace(letra,"");
                }
            }
        }
        
        val = ''
        val3 = new Array()
        
        for(s=0; s<pat.length; s++){
            val3[s] = val2.substring(0,pat[s]);
            val2 = val2.substr(pat[s]);
        }
        
        for(q=0;q<val3.length; q++){
            if(q ==0){
                val = val3[q];
            }
            else{
                if(val3[q] != ""){
                    val += sep + val3[q];
                }
            }
        }
        
        d.value = val
        d.valant = val
    }           
}


function evaAplicarFormato(id, tipo){
    
    valor = document.getElementById(id).value;
    
    if(tipo == "1")
    {   
        if(valor.length == 1){
            document.getElementById(id).value = valor + ".0";
        }
    }
    else{
        if(parseInt(valor) > 100){
            alert("La nota máxima es 100.");
            document.getElementById(id).value = "0";
        }
    }
}


function evaExportarEvaluacionExcel(){
    
    document.getElementById('btnExportarCalificaciones').click();
    
}


function evaGuardarNotasEvaluacion(){
        
    document.getElementById('tablaCargando').style.display = "";            
        
    var evaluacionId = $('evaluacionId').value;    
    var nColumnas = $('nColumnas').value;    
    var nColumnasC = $('nColumnasC').value;    
            
    if(evaluacionId != "" && evaluacionId != null )
    {    
        tabla = $('notasFinales');
        filas = tabla.rows.length;    

        for(i = 1; i < filas; i++){   
                                                          
            objUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
            objStatus = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                                    
            if(objStatus.value != "")
            {                                
                for(j = (3 + parseInt(nColumnasC)); j < (3 + parseInt(nColumnas)); j++){

                    objPracticaId = tabla.rows[i].cells[j].getElementsByTagName('input')[0];
                    objPonderacion = tabla.rows[0].cells[j].getElementsByTagName('input')[0];

                    var practicaId =  objPracticaId.id.split("-");                               
                                                           
                    evaGuardarNotaUsuario(evaluacionId, objUsuarioId.value, practicaId[2], objPracticaId.value, objPonderacion.value);
                }
            }
        }          
        
        window.location='../evaluaciones/evaEvaluaciones.php';
        
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none"; 
        alert("Se ha producido un error. Por favor, inténtelo más tarde.");       
    }
    
    
}


function evaGuardarNotaUsuario(evaluacionId, usuarioId, practicaId, nota, ponderacion){

    var elRequest = new Request({
        url         : 'evaFuncionGuardarNotaUsuario.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {               
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&usuarioId=" + usuarioId +
        "&practicaId=" + practicaId +
        "&nota=" + nota +
        "&ponderacion=" + ponderacion
        );            
    
    return false;
}


/*****************************************************************************/
/************************  Editar Evaluaciones    ****************************/
/*****************************************************************************/


function evaEditarData(id){  
    
    document.getElementById('evaluacionId').value = id;
    document.getElementById('btnEditarEvaluacion').click();            
    
}


function evaMostrarBusquedaUsuarios(){
    
    $('mBoxContainerUsu').tween('opacity', 1);
}


function evaValidarFormularioEvaluacionEdicion(){

    estado = "";   
    valor = "0";        
                            
    /******************************/
    /******** Evaluacion  *********/
    /******************************/                            
                            
    if($('evaluacionNombre').value == ""){
        $('oEvaluacion').setStyle('display', '');
        estado = "error";   
    }
    else{
        $('oEvaluacion').setStyle('display', 'none');
    }

    /******************************/
    /*********** Tema  ************/
    /******************************/
    
    if($('temaNombre').value == ""){
        $('oTema').setStyle('display', '');
        estado = "error";    
    }
    else{
        $('oTema').setStyle('display', 'none');
    }
        
    /******************************/
    /********* Capsulas  **********/
    /******************************/

    tablaCap = $('tablaCapsulas');
    filasCap = tablaCap.rows.length;
    blancos = "";
    totalCap = 0;
    
    for(i = 1; i < filasCap; i++){        
        
        if(tablaCap.rows[i].cells[0] != undefined)
        {                                 
            totalCap = totalCap + 1;
            objCapPonderacion = tablaCap.rows[i].cells[2].getElementsByTagName('input')[0];

            if(objCapPonderacion.value == "" || objCapPonderacion.value == "0")
            {
                blancos = "si";
                i = filasCap;
            }            
        }
    }

    if(blancos == "si" || totalCap == 0){
        $('oCapsulas').setStyle('display', '');
        estado = "error";                
    }
    else{
        $('oCapsulas').setStyle('display', 'none');
    }
    
    /******************************/
    /********* Practicas  *********/
    /******************************/
        
    tablaPrac = $('tablaPracticas');
    filasPrac = tablaPrac.rows.length;
    blancos = "";
                        
    for(i = 1; i < filasPrac; i++){
        
        if(tablaPrac.rows[i].cells[1] != undefined)
        {        
            objPracNombre = tablaPrac.rows[i].cells[1].getElementsByTagName('input')[0];
            objPracPonderacion = tablaPrac.rows[i].cells[2].getElementsByTagName('input')[0];

            if(objPracNombre.value == "" || objPracPonderacion.value == "" || objPracPonderacion.value == "0")
            {
                blancos = "si";
                i = filasPrac;
            }
        }
    }

    if(blancos == "si"){
        $('oPracticas').setStyle('display', '');
        estado = "error";
    }
    else{
        $('oPracticas').setStyle('display', 'none');
    }
   
    /******************************/
    /** Validar porcentaje final **/
    /******************************/
    
    if(parseInt($('totalPorcentaje').value) == 0 || parseInt($('totalPorcentaje').value) != 100){
        $('oTotal').setStyle('display', '');        
        estado = "error";        
    }
    else {
        $('oTotal').setStyle('display', 'none');        
    } 
 
    /******************************/
    /*********** Usuarios *********/
    /******************************/

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
        estado = "error";
    }
    else{
        $('oUsuarios').setStyle('display', 'none');
    }
            

    if(estado == "error"){        
        alert("Complete los datos solicitados.");
    }
    else{
        
        estado = $('evaluacionEstado').value;
        
        if(estado == "0"){
                
            if(confirm("La evaluación se encuentra inactiva: ¿Desea activarla?")) {
                $('evaluacionEstado').value = "1";
                $('btnEditarEva').click();
            }
            else{
                $('btnEditarEva').click();          
            }            
        }
        else{
            $('btnEditarEva').click();          
        } 
                
    }          

}


function evaEditarEvaluacion(){         
    
    document.getElementById('tablaCargando').style.display = "";  
    
    var evaluacionId = $('evaluacionId').value;
              
    var temaNombre = $('temaNombre').value;            
    var evaluacionNombre = $('evaluacionNombre').value;
    var evaluacionDescripcion = $('evaluacionDescripcion').value;
    var evaluacionEstado = $('evaluacionEstado').value;
    var idsCapsulas = "";    
    var idsPracticas = "";
    var idsUsuarios = "";
    
    tablaCap = $('tablaCapsulas');
    filasCap = tablaCap.rows.length;
    
    if(filasCap > 1){
        
        for(i = 1; i < filasCap; i++){   
        
            if(tablaCap.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tablaCap.rows[i].cells[0].getElementsByTagName('input')[1];
                idsCapsulas = idsCapsulas + objInputCapsulaId.value + ",";

            }
        }
    }  
    
    idsCapsulas = idsCapsulas.substring(0,idsCapsulas.length-1);         
    
    tablaPrac = $('tablaPracticas');
    filasPrac = tablaPrac.rows.length;
    
    if(filasPrac > 1){
        
        for(i = 1; i < filasPrac; i++){   
        
            if(tablaPrac.rows[i].cells[0] != undefined)
            {
                objInputPracticaId = tablaPrac.rows[i].cells[0].getElementsByTagName('input')[1];
                idsPracticas = idsPracticas + objInputPracticaId.value + ",";

            }
        }
    }  
    
    idsPracticas = idsPracticas.substring(0,idsPracticas.length-1);
    
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    
    if(filas > 0){
        
        for(i = 1; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                idsUsuarios = idsUsuarios + objInputUsuarioId.value + ",";

            }
        }
    }  
    
    idsUsuarios = idsUsuarios.substring(0,idsUsuarios.length-1);
               
    
    evaEditarCabecera(evaluacionId, temaNombre, evaluacionNombre, evaluacionDescripcion, evaluacionEstado, idsCapsulas, idsPracticas, idsUsuarios);    
    

    var resultadoEvaluacion = $('resultadoEdicion').value;
                
    if(!isNaN(parseInt(resultadoEvaluacion)))
    {    
        tablaCap = $('tablaCapsulas');
        filasCap = tablaCap.rows.length;    

        for(i = 1; i < filasCap; i++){        
            
            if(tablaCap.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tablaCap.rows[i].cells[0].getElementsByTagName('input')[1];
                objInputPonderacion = tablaCap.rows[i].cells[2].getElementsByTagName('input')[0];                                                
                
                evaEditarCapsula(evaluacionId, objInputCapsulaId.value, objInputPonderacion.value, temaNombre);         
            }
        }
                
        tablaPrac = $('tablaPracticas');
        filasPrac = tablaPrac.rows.length;    

        for(i = 1; i < filasPrac; i++){
            
            if(tablaPrac.rows[i].cells[0] != undefined)
            {
                objPracticaId= tablaPrac.rows[i].cells[0].getElementsByTagName('input')[1];
                objPracticaNombre= tablaPrac.rows[i].cells[1].getElementsByTagName('input')[0];
                objPracticaPonderacion = tablaPrac.rows[i].cells[2].getElementsByTagName('input')[0];
                
                evaGuardarPractica(evaluacionId, objPracticaId.value, objPracticaNombre.value, objPracticaPonderacion.value, temaNombre);
            }
        }
        
        tablaUsuario= $('listaUsuariosSeleccionados');
        filasUsuario = tablaUsuario.rows.length;    

        for(i = 1; i < filasUsuario; i++){
            
            if(tablaUsuario.rows[i].cells[1] != null)
            {
                objInputUsuarioId = tablaUsuario.rows[i].cells[0].getElementsByTagName('input')[1];
                evaGuardarUsuario(evaluacionId, objInputUsuarioId.value);                
            }            
        }
            
        window.location='../evaluaciones/evaEvaluaciones.php';
        
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none";  
        
        if(resultadoEvaluacion == "A"){
            alert("El tema seleccionado no existe.");
        }
        
        if(resultadoEvaluacion == "B"){
            alert("La evaluación ingresada ya existe.");
        }
        
        if(resultadoEvaluacion == "C"){
            alert("Ya ha comenzado el proceso de evaluación. No es posible la edición.");
        }
        
        if(resultadoEvaluacion != "A" && resultadoEvaluacion != "B" && resultadoEvaluacion != "C"){
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
        
    }
        

}


function evaEditarCabecera(evaluacionId, temaNombre, evaluacionNombre, evaluacionDescripcion, evaluacionEstado, idsCapsulas, idsPracticas, idsUsuarios){
                              
    var elRequest = new Request({
        url         : 'evaFuncionEditarCabecera.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos != "" && datos != null)
            {                   
                $('resultadoEdicion').value  = datos.trim();                                                    
            }            
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&temaNombre=" + encodeURIComponent(temaNombre) +
        "&evaluacionNombre=" + encodeURIComponent(evaluacionNombre) +
        "&evaluacionDescripcion=" + encodeURIComponent(evaluacionDescripcion) + 
        "&evaluacionEstado=" + encodeURIComponent(evaluacionEstado) + 
        "&idsCapsulas=" + encodeURIComponent(idsCapsulas) + 
        "&idsPracticas=" + encodeURIComponent(idsPracticas) + 
        "&idsUsuarios=" + encodeURIComponent(idsUsuarios)
        );            
    
    return false;
}


function evaEditarCapsula(evaluacionId, capsulaId, ponderacion, temaNombre){
    
    var elRequest = new Request({
        url         : 'evaFuncionEditarCapsula.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {
            
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("evaluacionId=" + evaluacionId +
        "&capsulaId=" + capsulaId +
        "&ponderacion=" + ponderacion + 
        "&temaNombre=" + encodeURIComponent(temaNombre)
        );
    
    return false;
}


/*****************************************************************************/
/************************  Anular Evaluacion    ******************************/
/*****************************************************************************/

function evaAnularData(id, link, destino, estado, mensaje){
            
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
            onSuccess: function(datos){
                if(datos != null && datos != ""){
                    alert(datos);
                }
                else{
                    location.href = destino;
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

