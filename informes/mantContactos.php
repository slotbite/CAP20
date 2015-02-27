<?php
session_start();

ini_set("memory_limit", "600M");

include("../default.php");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();


$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<script type="text/javascript" src="../scripts/tablesort.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<div>
    <?
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";        
    } else {


        if ($_SESSION['perfilId'] == 1) {
            $menu1 = "display:block;";
        } else {
            $menu1 = "display:none;";
        }

        require('../planificaciones/clases/planificaciones.class.php');
        $objPlanificacion = new planificaciones();

        $consulta = $objPlanificacion->planBuscarCliente($cliente_id);
        $cliente = mssql_fetch_array($consulta);
        $consulta = $objPlanificacion->planBuscarAdministrador($administradorId);
        $administrador = mssql_fetch_array($consulta);

        $cnombre = htmlentities($cliente['clienteNombreCompleto']);
        $anombre = htmlentities($administrador['administradorNombreCompleto']);


        $plantilla->setTemplate("MantContactos");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha",
            "MANT" => "$menu1",
            "CLIENTENOMBRE" => $cnombre,
            "ADMIN" => $anombre,
            "CLIENTE" => $cliente_id
        ));

        echo $plantilla->show();
    }
    ?>
</div>
    <?
    $plantilla->setTemplate("footer_2");
    echo $plantilla->show();
    ?>
<script type="text/javascript">
                                                                                                    
    window.addEvent('domready', function() {    
        $('mBoxContainerOrg').setStyle('opacity', '0'); 
        $('mBoxContainerSec').setStyle('opacity', '0'); 
        $('mBoxContainerAre').setStyle('opacity', '0'); 
        $('mBoxContainerGru').setStyle('opacity', '0'); 
        $('mBoxContainerSecInfo').setStyle('opacity', '0'); 
        
        $('btnCancelarSecInforme').addEvent('click', function(){
            var property = 'opacity';
            var to = "0";
            $('mBoxContainerSecInfo').tween(property, to);
        });
        
        new Autocompleter.Request.HTML($('nombre_ga'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :   'sectorId',
                'nombre_campo'      :   'sectorNombre',
                'nombre_tablas'     :   'From IndSectoresInformes (nolock)',
                'nombre_where'      :   ''
            }
        });
        
        new Autocompleter.Request.HTML($('planOrganizacionNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading',
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :   'organizacionId',
                'nombre_campo'      :   'organizacionNombre',
                'nombre_tablas'     :   'From Organizaciones o (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and o.organizacionEstado = 1'
            }
        });
        
        new Autocompleter.Request.HTML($('planGrupoNombre'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 2,
            'overflow': true,
            'selectMode': 'type-ahead',

            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id'         :	'grupoId',
                'nombre_campo'      : 	'grupoNombre',
                'nombre_tablas'     :   'From Grupos g (nolock)',
                'nombre_where'      :   'and clienteId = ' + $('clienteId').get('value') + ' and g.grupoEstado = 1'                                         
            }
        }); 
     
        $('btnCancelarOrg').addEvent('click', function(){
            var property = 'opacity';
            var to = "0";
            $('mBoxContainerOrg').tween(property, to);
        });
        
        $('btnCancelarSec').addEvent('click', function(){
            var property = 'opacity';
            var to = "0";
            $('mBoxContainerSec').tween(property, to);
        });
        
        $('btnCancelarAre').addEvent('click', function(){
            var property = 'opacity';
            var to = "0";
            $('mBoxContainerAre').tween(property, to);
        });
        
        $('btnCancelarGru').addEvent('click', function(){
            var property = 'opacity';
            var to = "0";
            $('mBoxContainerGru').tween(property, to);
        });
    });
    
    function SeleccionarSectoresInforme(){
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : 'FuncionBuscarSectoresInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('BoxContentSecInfo').set('html',datos);                                                            
                    $('mBoxContainerSecInfo').tween(property, to);
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });

        elRequest.send();  
        
    }
    
    function SeleccionarSectorInforme(){
        
        var tabla = $('tablaListaSectores');                
        var filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            var objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){

                var objInputSec = tabla.rows[i].cells[1];
                $('nombre_ga').set('value', ((objInputSec.innerText != undefined) ? objInputSec.innerText : objInputSec.textContent ));
                i = filas;
            }           
        }
        
        $('mBoxContainerSecInfo').tween("opacity", 0);                            
    
    }
    function ValidarSelGA(){
        $('oGA').setStyle('visibility','hidden')
   
        if($('nombre_ga').value==''){
            $('oGA').setStyle('visibility','visible')
        }else{
            if(ValidarGAInforme()==false){
                $('oGA').setStyle('visibility','visible')
            }else{
                var sector_id=$('sector_id').value;
                ListarContactos(sector_id);
            }
        }
    }

    function ValidarGAInforme(){
        var valido=false;
        var elRequest = new Request({
            url         : 'FuncionValidarSectoresInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('sector_id').value=datos;
                    if(datos!='-1'){
                        valido=true;   
                    }
                }                        
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send('nombre='+ $('nombre_ga').value); 
        return(valido)
    }

    function ValidaGuardar(){
        var estado="";
        $('oGA').setStyle('visibility','hidden')
        if($('nombre_ga').value==''){
            $('oGA').setStyle('visibility','visible')
        }else{
            if(ValidarGAInforme()==false){
                $('oGA').setStyle('visibility','visible')
                estado = "error";
            }else{
                var tabla = $('listaUsuariosSeleccionados').tBodies[0];                
                var filas = tabla.rows.length;
                var total = 0;

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
            }
    
            if(estado == "error"){        
                alert("Complete los datos solicitados.");
            }else{
     
                var sector_id=$('sector_id').value;
                //Request que limpie la tabla:
     
                LimpiarContactos(sector_id);
     
                tabla = $('listaUsuariosSeleccionados').tBodies[0];                
                filas = tabla.rows.length;
                for(i = 0; i < filas; i++){   

                    if(tabla.rows[i].cells[0] != undefined)
                    {
                        var control= tabla.rows[i].cells[0].getElementsByTagName('input')[1];
                        var personal_id=control.value;
                        guardarContactos(sector_id,personal_id)
                    }
                }
                alert('Los contactos han sido guardados correctamente');
            }
        }
    
    }
    function LimpiarContactos(sector_id){
        var elRequest = new Request({
            url         : 'FuncionLimpiarSectoresInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function() {
                //
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send('sector_id='+ sector_id);
    }

    function guardarContactos(sector_id,personal_id){
        var elRequest = new Request({
            url         : 'FuncionGuardarSectoresInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function() {
                //
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send('sector_id='+ sector_id+'&personal_id='+personal_id);
    }
    function ListarContactos(sector_id){
        var elRequest = new Request({
            url         : 'FuncionListarContactosSectoresInforme.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(html) {
                $('listaUsuariosSeleccionadosBody').set('html',html);
            },
            onFailure: function() {
                alert("Se ha producido un error. Por favor, inténtelo más tarde.");
            }
        });
    
        elRequest.send('sector_id='+ sector_id);
    }

    function ConfirmaSalir(){
        location.href = '../mantenedores/admMantenedores.php';
    }



</script>    


