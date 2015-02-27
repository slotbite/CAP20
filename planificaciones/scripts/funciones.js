

function planMostrarCapsulas(inputTema, inputCapsula){
    
    var nombreTema = ($(inputTema).get('value'));
           
    new Autocompleter.Request.HTML($(inputCapsula), '../librerias/autocompleterMantenedores.php', {
        // class added to the input during request                                        
        'indicatorClass': 'autocompleter-loading', 
        'minLength': 2,
        'overflow': true,
        'selectMode': 'type-ahead',                                        
                            
        // send additional POST data, check the PHP code
        'postData': {
            'nombre_id'		:	'capsulaId',
            'nombre_campo' 	: 	'capsulaNombre',
            'nombre_tablas' 	:       'From Capsulas c (nolock) inner join Temas t (nolock) on c.temaId = t.temaId',
            'nombre_where'      :       "and c.clienteId = " + $('clienteId').get('value') + " and t.temaNombre like '" + nombreTema + "%'" 
        }
    }); 
    
}


/*****************************************************************************/
/************************   Insertar Planificación   *************************/
/*****************************************************************************/

function planSeleccionarCapsula(){

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
            url         : 'planFuncionBuscarCapsulas.php',
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


        elRequest.send( "nombreTema=" + encodeURIComponent(nombreTema) + 
                        "&ids=" + encodeURIComponent(ids));  
                        
    }
    else
    {
        alert("Seleccione el tema.");
    }
}


function planAgregarCapsulas(){                
               
    tabla = $('tablaCapsulas');                
    filas = tabla.rows.length;
    //var x = tabla.insertRow(filas);
    total = 0;
    uf = 1; //ultima fila
    
    for(i = 1; i < filas; i++){
        if(tabla.rows[i].cells[0] != undefined){
            total = total + 1;
            uf = i;
        }
    }
               
    if(total > 0)
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
        
            objInput1 = tabla2.rows[i].cells[1].innerHTML;
            objInput2 = tabla2.rows[i].cells[2].innerHTML;
            objInput3 = tabla2.rows[i].cells[3].getElementsByTagName('input')[0];
                 
            x = tabla.insertRow(-1);
                 
            var y = x.insertCell(0);
            y.innerHTML = "<input id='" + filas + "' type='checkbox'/> <input id='capsulaId-" + filas + "' type='text' style='width:70px; text-align:right; display:none' value='" + objInput3.value + "'/>";

            var y1 = x.insertCell(1);
            y1.innerHTML = objInput1;
    
            var y2 = x.insertCell(2);
            y2.innerHTML = objInput2;

            var y3 = x.insertCell(3);
            y3.innerHTML = "<input id='cal-" + filas + "' type='text' style='width:70px; text-align:right' readonly='readonly'/>";
        
            var y4 = x.insertCell(4);
            y4.innerHTML = "<button id='btn-" + filas + "' class='btn invisible'><img src='../skins/saam/img/calendar.png'/></button>";       
            
            
            var id = "cal-" + filas + "";
            
            var myCal1 = Calendar.setup({
                inputField: "cal-" + filas + "",
                trigger: "btn-" + filas + "",
                onSelect: function () {
                    this.hide();
                    flatCallback = planCalcularFechaCierre();   
                //flatCallback = planValidarFecha(id);
                },
                showTime: 12,
                dateFormat: "%d-%m-%Y"
            });
            myCal1.setLanguage('es');                                                          
        
            //var y5 = x.insertCell(5);
            //y5.innerHTML = "<input id='fecha-" + filas + "' type='text' style='width:70px; text-align:right;' readonly='readonly' />";
            //y5.setAtributte("Style","display:'none'");
                                    
            filas = filas + 1;
        }           
    }

    tabla = $('tablaCapsulas');
    filas = tabla.rows.length;

    if(filas > 1)
    {             
        $('aEliminarCap').setStyle('display', '');
        $('tablaCapsulas').setStyle('display', '');
    } 
    
    $('mBoxContainer').tween("opacity", 0);
        
}


function planEliminarFila(tabla){

    tabla = $(tabla);
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
    }

    tablaCap = $('tablaCapsulas');    
    filasCap = tablaCap.rows.length;    
    total = 0;
    
    for(i = 1; i < filasCap; i++){
        if(tablaCap.rows[i].cells[0] != null){
            total = total + 1;
        }
    }
    
    if(total == 0){
        $('aEliminarCap').setStyle('display', 'none');
        //$('tablaCapsulas').setStyle('display', 'none');
    }                     
}


function planDescartarUsuarios(){

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


function planCompararFechas(fecha, fecha2){   
    var xMonth=fecha.substring(3, 5);  
    var xDay=fecha.substring(0, 2);  
    var xYear=fecha.substring(6,10);  
    var yMonth=fecha2.substring(3, 5);  
    var yDay=fecha2.substring(0, 2);  
    var yYear=fecha2.substring(6,10);  
    if (xYear> yYear)  
    {  
        return(true)  
    }  
    else  
    {  
        if (xYear == yYear)  
        {   
            if (xMonth> yMonth)  
            {  
                return(true)  
            }  
            else  
            {   
                if (xMonth == yMonth)  
                {  
                    if (xDay>= yDay)  
                        return(true);  
                    else  
                        return(false);  
                }  
                else  
                    return(false);  
            }  
        }  
        else  
            return(false);  
    }  
}        


function planMostrarSectores(inputOrg, inputSec){
    
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


function planMostrarAreas(inputOrg, inputSec, inputArea){                   
    
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


function planBusquedaOrg(){
    
    $('planOrganizacionNombre').disabled = false; 
    $('planSectorNombre').disabled = false; 
    $('planAreaNombre').disabled = false;         
    $('planGrupoNombre').disabled = true; 
    $('planEvaluacionNombre').disabled = true;                         
    $('planGrupoNombre').value = ""; 
    $('planEvaluacionNombre').value = "";            
    
    $('lupaOrg').setStyle('display', '');
    $('lupaSec').setStyle('display', '');
    $('lupaArea').setStyle('display', '');
    $('lupaGru').setStyle('display', 'none'); 
    $('lupaEva').setStyle('display', 'none');
}


function planBusquedaGru(){
    
    $('planOrganizacionNombre').disabled = true; 
    $('planSectorNombre').disabled = true; 
    $('planAreaNombre').disabled = true; 
    $('planGrupoNombre').disabled = false; 
    $('planEvaluacionNombre').disabled = true;             
    $('planOrganizacionNombre').value = ""; 
    $('planSectorNombre').value = ""; 
    $('planAreaNombre').value = ""; 
    $('planEvaluacionNombre').value = "";    
    
    $('lupaOrg').setStyle('display', 'none');
    $('lupaSec').setStyle('display', 'none');
    $('lupaArea').setStyle('display', 'none');
    $('lupaGru').setStyle('display', ''); 
    $('lupaEva').setStyle('display', 'none');
    
}


function planBusquedaEva(){
    
    $('planOrganizacionNombre').disabled = true; 
    $('planSectorNombre').disabled = true; 
    $('planAreaNombre').disabled = true; 
    $('planGrupoNombre').disabled = true; 
    $('planEvaluacionNombre').disabled = false;  
    $('planOrganizacionNombre').value = ""; 
    $('planSectorNombre').value = ""; 
    $('planAreaNombre').value = ""; 
    $('planGrupoNombre').value = "";
    
    $('lupaOrg').setStyle('display', 'none');
    $('lupaSec').setStyle('display', 'none');
    $('lupaArea').setStyle('display', 'none');
    $('lupaGru').setStyle('display', 'none'); 
    $('lupaEva').setStyle('display', '');
}


function planSeleccionarTodo(valor, tabla){
    tabla=$(tabla);
    largo=tabla.rows.length;    
    for (i=1;i<largo;i++){
        
        if(tabla.rows[i].cells[0] != null)
        {
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked = valor;
        }
    }
}


function planSeleccionarTodoCapsulas(valor){
    tabla = document.getElementById('tablaListaCapsulas');
    largo=tabla.rows.length;
    for (i=1;i<largo;i++){
        
        if(tabla.rows[i].cells[0] != null)
        {
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked = valor;
        }
    }
}


function planCalcularFechaCierre(){
            
    tabla = $('tablaCapsulas');                
    filas = tabla.rows.length;    
    
    var getdate = new Date();
    var dia = "00" + getdate.getDate();
    var mes = "00" + (getdate.getMonth() +1).toString();
    var yea = getdate.getFullYear();            

    var fechaActual = dia.substring(dia.length -2, dia.length) + "-" + mes.substring(mes.length -2, mes.length) + "-" + yea;
      
    //    var diasCierre = document.getElementById('diasCierre').value;
            
    //    for(i = 1; i < filas; i++){        
    //        
    //        if(tabla.rows[i].cells[3] != null && tabla.rows[i].cells[5] != null){
    //        
    //            objInput0 = tabla.rows[i].cells[3].getElementsByTagName('input')[0];
    //            
    //            if(objInput0.value != "")
    //            {                                           
    //                objInput1 = tabla.rows[i].cells[5].getElementsByTagName('input')[0];
    //                cfecha = planConvertirAFecha(objInput0.value);                
    //                tfecha = cfecha.getTime();
    //                tfecha = tfecha + (parseInt(diasCierre) * 24 * 60 * 60 * 1000);                                         
    //                cfecha.setTime(tfecha);                                
    //                dias = "0" + (cfecha.getDate());
    //                mes = "0" + (cfecha.getMonth());
    //                año = (cfecha.getFullYear()); 
    //                
    //                if(mes == "00")
    //                {
    //                    mes = "0" + (objInput0.value).substring(3,5);
    //                }
    //                
    //                objInput1.value = dias.substring(dias.length-2, dias.length) + "-" + mes.substring(mes.length-2, mes.length)  + "-" + año ;
    //            }                                     
    //        }
    //    }

    for(i = 1; i < filas; i++){        
        
        if(tabla.rows[i].cells[0] != undefined){
        
            objInputFecha = tabla.rows[i].cells[3].getElementsByTagName('input')[0];
            
            if(objInputFecha.value != "")
            {                                                                                                          
                if(planCompararFechas(fechaActual, objInputFecha.value)){
                    alert("Seleccione una fecha mayor a la fecha actual.");  
                    tabla.rows[i].cells[3].getElementsByTagName('input')[0].value = "";                                       
                }
            }                                     
        }
    } 
}


function planBuscarUsuarios(){                       
        
    document.getElementById('tablaBuscarCarga').style.display = "";         
    
    var radOrg = $('radioOrg');
    var radGru = $('radioGru');
    var radEva = $('radioEva');
    var busqueda = "";
    
    if(radOrg.checked == true){
        busqueda = "Org";
    }
    
    if(radGru.checked == true){
        busqueda = "Gru";
    }
    
    if(radEva.checked == true){
        busqueda = "Eva";
    }
              
              
    var nombreOrg = ($('planOrganizacionNombre').get('value'));
    var nombreSec = ($('planSectorNombre').get('value'));       
    var nombreArea = ($('planAreaNombre').get('value'));       
    var nombreGrup = ($('planGrupoNombre').get('value'));       
    var nombreEva = ($('planEvaluacionNombre').get('value'));       
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
        url         : 'planFuncionBuscarUsuarios.php',
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
        }
    });

    elRequest.send("planOrganizacionNombre=" + encodeURIComponent(nombreOrg) +
        "&planSectorNombre=" + encodeURIComponent(nombreSec) +
        "&planAreaNombre=" + encodeURIComponent(nombreArea) + 
        "&planGrupoNombre=" + encodeURIComponent(nombreGrup) +
        "&planEvaluacionNombre=" + encodeURIComponent(nombreEva) +       
        "&tipoBusqueda=" + busqueda + 
        "&ids=" + encodeURIComponent(ids)
        );
    
    return false;
}


function planSeleccionarUsuarios(){
    
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


function planSeleccionarOrganizaciones(){
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarOrganizaciones.php',
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


function planSeleccionarOrganizacion(){
        
    tabla = $('tablaListaOrganizaciones');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInput1 = tabla.rows[i].cells[1];            
            $('planOrganizacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerOrg').tween("opacity", 0);                            
    
}


function planSeleccionarSectores(){
    
    var organizacionNombre = ($('planOrganizacionNombre').get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarSectores.php',
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


function planSeleccionarSector(){
        
    tabla = $('tablaListaSectores');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInputOrg = tabla.rows[i].cells[1];
            objInputSec = tabla.rows[i].cells[2];
            $('planOrganizacionNombre').set('value', ((objInputOrg.innerText != undefined) ? objInputOrg.innerText : objInputOrg.textContent ));
            $('planSectorNombre').set('value', ((objInputSec.innerText != undefined) ? objInputSec.innerText : objInputSec.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerSec').tween("opacity", 0);                            
    
}


function planSeleccionarAreas(){
    
    var organizacionNombre = ($('planOrganizacionNombre').get('value'));
    var sectorNombre = ($('planSectorNombre').get('value'));
    
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarAreas.php',
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


function planSeleccionarArea(){
        
    tabla = $('tablaListaAreas');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInputOrg = tabla.rows[i].cells[1];
            objInputSec = tabla.rows[i].cells[2];
            objInputAre = tabla.rows[i].cells[3];
            $('planOrganizacionNombre').set('value', ((objInputOrg.innerText != undefined) ? objInputOrg.innerText : objInputOrg.textContent ));
            $('planSectorNombre').set('value', ((objInputSec.innerText != undefined) ? objInputSec.innerText : objInputSec.textContent ));
            $('planAreaNombre').set('value', ((objInputAre.innerText != undefined) ? objInputAre.innerText : objInputAre.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerAre').tween("opacity", 0);                            
    
}


function planSeleccionarGrupos(){
            
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarGrupos.php',
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


function planSeleccionarGrupo(){
        
    tabla = $('tablaListaGrupos');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInputGru= tabla.rows[i].cells[1];            
            $('planGrupoNombre').set('value', ((objInputGru.innerText != undefined) ? objInputGru.innerText : objInputGru.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerGru').tween("opacity", 0);                            
    
}


function planSeleccionarEvaluaciones(){
            
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarEvaluaciones.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos)
            {                
                $('BoxContentEva').set('html',datos);                                                            
                $('mBoxContainerEva').tween(property, to);
                    
            }                        
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send();  
}


function planSeleccionarEvaluacion(){
        
    tabla = $('tablaListaEvaluaciones');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInputEva = tabla.rows[i].cells[1];            
            $('planEvaluacionNombre').set('value', ((objInputEva.innerText != undefined) ? objInputEva.innerText : objInputEva.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerEva').tween("opacity", 0);                            
    
}


function planSeleccionarTemas(){
            
    var property = 'opacity';
    var to = "1";
                                           
    
    var elRequest = new Request({
        url         : 'planFuncionBuscarTemas.php',
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


function planSeleccionarTema(){
        
    tabla = $('tablaListaTemas');                
    filas = tabla.rows.length;
    
    for(i = 1; i < filas; i++){        
        objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
        if(objInput0.checked == true){
        
            objInputTema = tabla.rows[i].cells[1];            
            $('temaNombre').set('value', ((objInputTema.innerText != undefined) ? objInputTema.innerText : objInputTema.textContent ));
            i = filas;
        }           
    }
        
    $('mBoxContainerTema').tween("opacity", 0);                            
    
}


function planConvertirAFecha(string) {
    var date = new Date()
    mes = parseInt(string.substring(3, 5));
    date.setMonth(mes); //en javascript los meses van de 0 a 11
    date.setDate(string.substring(0, 2));
    date.setYear(string.substring(6, 10));  
    return date;
}


function planLimpiarInputs(input1, input2, input3){
    
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


function planValidarFormulario(){

    estado = "";   
    valor = "0";        
                            
    /******************************/
    /******** Planificacion  ******/
    /******************************/                            
                            
    if($('inPlanificacionNombre').value == ""){
        $('oPlanificacion').setStyle('display', '');
        estado = "error";   
    }
    else{
        $('oPlanificacion').setStyle('display', 'none');
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
            objCapPonderacion = tablaCap.rows[i].cells[3].getElementsByTagName('input')[0];

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
        $('btIngresarPlan').click();
    }
                                
}


function planGuardarPlanificacion(){
           
    document.getElementById('tablaCargando').style.display = "";
           
    planificacionNombre = $('inPlanificacionNombre').value;
    planificacionDescripcion = $('inPlanificacionDescripcion').value;
    
    planGuardarCabecera(planificacionNombre, planificacionDescripcion);
    
    var planificacionId = document.getElementById('planificacionId').value;                            
          
    if(!isNaN(parseInt(planificacionId)))
    {    
        tablaCap = $('tablaCapsulas');
        filasCap = tablaCap.rows.length;    

        for(i = 1; i < filasCap; i++){        
            
            if(tablaCap.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tablaCap.rows[i].cells[0].getElementsByTagName('input')[1];
                objInputFechaEnvio = tablaCap.rows[i].cells[3].getElementsByTagName('input')[0];                                                
                
                planGuardarCapsula(planificacionId, objInputCapsulaId.value, objInputFechaEnvio.value);            
            }
        }

        tabla = $('listaUsuariosSeleccionados').tBodies[0];
        filas = tabla.rows.length;         

        for(i = 1; i < filas; i++){   

            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                planGuardarUsuario(planificacionId, objInputUsuarioId.value);                                    
                
            }            
        }

        window.location='../planificaciones/planPlanificaciones.php';
        
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none";
        
        if(planificacionId == "A")
        {
            alert("El nombre de la planificación ya existe.");
        }
        
        if(planificacionId != "A")
        {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    }
        

}


function planGuardarCabecera(planificacionNombre, planificacionDescripcion){
                  
    var elRequest = new Request({
        url         : 'planFuncionGuardarCabecera.php',
        method      : 'post',
        async       : false,
        
        
        onSuccess   : function(datos) {
            if(datos != null && datos!= "")
            {       
                $('planificacionId').value = datos.trim();
            }            
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });


    elRequest.send("planificacionNombre=" + encodeURIComponent(planificacionNombre) + 
        "&planificacionDescripcion=" + encodeURIComponent(planificacionDescripcion));            
    
    return false;
}


function planGuardarCapsula(planificacionId, capsulaId, fechaEnvio){
    
    var elRequest = new Request({
        url         : 'planFuncionGuardarCapsula.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(){            
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error al guardar las cápsulas. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("planificacionId=" + planificacionId +
        "&capsulaId=" + capsulaId +
        "&fechaEnvio=" + fechaEnvio);
    
    return false;
}


function planGuardarUsuario(planificacionId, usuarioId){
    
    var elRequest = new Request({
        url         : 'planFuncionGuardarUsuario.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {                                   
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("planificacionId=" + planificacionId +
        "&usuarioId=" + usuarioId
        );
    
    return false;
}




/*****************************************************************************/
/************************  Editar Planificaciones    *************************/
/*****************************************************************************/


function planEditarData(id){  
    
    document.getElementById('planificacionId').value = id;
    document.getElementById('btnEditarPlanificacion').click();            
    
}


function planValidarFormularioEdicion(){

    estado = "";   
    valor = "0";        
                            
    /******************************/
    /******** Planificacion  ******/
    /******************************/                            
                            
    if($('updPlanificacionNombre').value == ""){
        $('oPlanificacion').setStyle('display', '');
        estado = "error";   
    }
    else{
        $('oPlanificacion').setStyle('display', 'none');
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
            objCapPonderacion = tablaCap.rows[i].cells[3].getElementsByTagName('input')[0];

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
        
        estado = $('planificacionEstado').value;
        
        if(estado == "0"){
                
            if(confirm("La planificación se encuentra inactiva: ¿Desea activarla?")) {
                $('planificacionEstado').value = "1";
                $('btEditarPlan').click();
            }
            else{
                $('btEditarPlan').click();          
            }            
        }
        else{
            $('btEditarPlan').click();          
        } 
                        
    }                    

}


function planEditarPlanificacion(){         
    
    document.getElementById('tablaCargando').style.display = "";
    
    var planificacionId = $('planificacionId').value;
                              
    var planificacionNombre = $('updPlanificacionNombre').value;
    var planificacionDescripcion = $('updPlanificacionDescripcion').value;
    var planificacionEstado = $('planificacionEstado').value;
    
    var idsCapsulas = "";
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
    
    
    tabla = $('listaUsuariosSeleccionados').tBodies[0];                
    filas = tabla.rows.length;
    
    if(filas > 0){
        
        for(i = 0; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                idsUsuarios = idsUsuarios + objInputUsuarioId.value + ",";

            }
        }
    }  
    
    idsUsuarios = idsUsuarios.substring(0,idsUsuarios.length-1);          

    planEditarCabecera(planificacionId, planificacionNombre, planificacionDescripcion, planificacionEstado, idsCapsulas, idsUsuarios);    
    
    planificacionId = document.getElementById('resultadoEdicion').value;
                                
    if(!isNaN(parseInt(planificacionId)))
    {    
        tablaCap = $('tablaCapsulas');
        filasCap = tablaCap.rows.length;    

        for(i = 1; i < filasCap; i++){        
            
            if(tablaCap.rows[i].cells[0] != undefined)
            {
                objInputCapsulaId = tablaCap.rows[i].cells[0].getElementsByTagName('input')[1];
                objInputFechaEnvio = tablaCap.rows[i].cells[3].getElementsByTagName('input')[0];                                                                                
                
                planEditarCapsula(planificacionId, objInputCapsulaId.value, objInputFechaEnvio.value);            
            }
        }
        
        tabla = $('listaUsuariosSeleccionados').tBodies[0];                
        filas = tabla.rows.length;    

        for(i = 1; i < filas; i++){   
        
            if(tabla.rows[i].cells[0] != undefined)
            {
                objInputUsuarioId = tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                planGuardarUsuario(planificacionId, objInputUsuarioId.value);
            }
            
        }

        window.location='../planificaciones/planPlanificaciones.php';               
        
    }
    else
    {
        document.getElementById('tablaCargando').style.display = "none";
        
        if(planificacionId == "A"){
            alert("El planificación ingresada ya existe.");
        }
        else{
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
        
    }
        

}


function planEditarCabecera(planificacionId, planificacionNombre, planificacionDescripcion, planificacionEstado, idsCapsulas, idsUsuarios){
              
    var elRequest = new Request({
        url         : 'planFuncionEditarCabecera.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function(datos) {
            if(datos != "" && datos != null)
            {
                $('resultadoEdicion').value = datos.trim();
            }
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");            
        }                        
        
    });

    elRequest.send("planificacionId=" + planificacionId +
        "&planificacionNombre=" + encodeURIComponent(planificacionNombre) +
        "&planificacionDescripcion=" + encodeURIComponent(planificacionDescripcion) +        
        "&planificacionEstado=" + encodeURIComponent(planificacionEstado) +
        "&idsCapsulas=" + idsCapsulas +         
        "&idsUsuarios=" + idsUsuarios
        );            
    
    return false;
}


function planEditarCapsula(planificacionId, capsulaId, fechaEnvio){
    
    var elRequest = new Request({
        url         : 'planFuncionEditarCapsula.php',
        method      : 'post',
        async       : false,
        
        onSuccess   : function() {
            
        },
        //Si Falla
        onFailure: function() {
            alert("Se ha producido un error. Por favor, inténtelo más tarde.");
        }
    });

    elRequest.send("planificacionId=" + planificacionId +
        "&capsulaId=" + capsulaId +
        "&fechaEnvio=" + fechaEnvio
        );
    
    return false;
}


/*****************************************************************************/
/************************  Anular Planificaciones    *************************/
/*****************************************************************************/

function planAnularData(id, link, destino, estado, mensaje){
            
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