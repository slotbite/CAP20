<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
//$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
//$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;


$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;
$administrador_id = $_SESSION['administradorId'] ? $_SESSION['administradorId'] : '0';
$perfil_id = $_SESSION['perfilId'] ? $_SESSION['perfilId'] : '0';


setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

/*
  $queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsula_id.",".$capsulaVersion." ";
  $result = $base_datos->sql_query($queryTipo);
  $row	= $base_datos->sql_fetch_assoc($result);
  $nombre = mb_convert_encoding($row["capsulaNombre"], "ISO-8859-1", "UTF-8");
  $tipo = $row["capsulaTipo"];
  $TemaNom= $row["temaNombre"];
 */
//TO DO: AGREGAR CLIENTEID A LA PANTALLA COMO EN EL ENVIO_00
?>
<div>
    <?
    $queryEnvio = "EXEC envTraeEnvioId " . $clienteId . "";

    $resultE = $base_datos->sql_query($queryEnvio);
    $rowE = $base_datos->sql_fetch_assoc($resultE);
    $envioId = $rowE["envioId"];


    $queryFecha = "EXEC envTraeDuracionCapsula " . $clienteId . "";
    $resultF = $base_datos->sql_query($queryFecha);
    $rowF = $base_datos->sql_fetch_assoc($resultF);
    $dias = $rowF["plazoDias"];
    ?>
    <script type="text/javascript" src="../scripts/tablesort.js"></script>

<?
$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

if ($usuario_id == '') {
    echo "<script>window.location='../index.php';</script>";
    ?>
        <?
    } else {

        $fechaActual = date("d-m-Y");
//Le sumo los dias de la consulta a la fecha actual:
        $date = strtotime(date("d-m-Y", strtotime($fechaActual)) . " +$dias day");
        $fechacierre = date('d-m-Y', $date);


        if ($_SESSION['perfilId'] == 1) {
            $menu1 = "display:block;";
        } else {
            $menu1 = "display:none;";
        }

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha",
            "MANT" => "$menu1"
        ));


        echo $plantilla->show();

        $plantilla->setTemplate("envio_capsula_1");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "CLIENTE" => "$Cliente_id",
            "NOMBRE" => "$nombre",
            "CAPSULA_ID" => "$capsula_id",
            "CAPSULA_VER" => "$capsulaVersion",
            "PERSONALIZACIONID" => "$personalizacionId",
            "ENVIOID" => "$envioId",
            "FECHACIERRE" => "$fechacierre",
            "CAPSULANOM1" => $nombre,
            "TEMANOM1" => $TemaNom
        ));
        echo $plantilla->show();
        
        $capsulaNombre = ($_POST['capsulaNombre'] != "") ? $_POST['capsulaNombre'] : '';
        $temaNombre = ($_POST['temaNombre'] != "") ? $_POST['temaNombre'] : '';                                
    }
    ?>
</div>
    <?
    $plantilla->setTemplate("footer_2");
    echo $plantilla->show();
    ?>
<script>
    var cantidadenvios=0;
    var enviados=0;
    var elRequest2={};
    window.addEvent('domready', function (){
    
        $('nombre_capsula').value = "<? echo $capsulaNombre?>";
        $('nombre_tema').value = "<? echo $temaNombre?>";
        
        traeIdTema();
        traeIdCap();
    
        $('Usuarios').setStyle('display', 'block');
        $('Evaluaciones').setStyle('display', 'none');
        $('Usuarios1').setStyle('cursor','default');
        $('Evaluaciones1').setStyle('cursor','default');
        $('lupaGrupo').setStyle('visibility', 'hidden');
        $('ListarU').setStyle('display', 'block');
        $('ListarE').setStyle('display', 'none');
    });
	
    $('Usuarios1').addEvent('click', function(){
        $('ErrorBusqueda').set('html','');
        $('Usuarios').setStyle('display', 'block');
        $('ListarU').setStyle('display', 'block');
        $('ListarE').setStyle('display', 'none');
        $('Evaluaciones').setStyle('display', 'none');
		
        var tabla1= $('encabezado_grilla');
        tabla1.rows[0].cells[0].getElementsByTagName('input')[0].disabled=false;
    });
	
    $('Evaluaciones1').addEvent('click', function(){
        $('ErrorBusqueda').set('html','');
        $('Usuarios').setStyle('display', 'none');
        $('Evaluaciones').setStyle('display', 'block');
        $('ListarU').setStyle('display', 'none');
        $('ListarE').setStyle('display', 'block');
        var tabla1= $('encabezado_grilla');
        tabla1.rows[0].cells[0].getElementsByTagName('input')[0].disabled=true;
    });

    $('radio2').addEvent('click', function(){
        $('organizacionNombre').value='';
        $('sectorNombre').value='';
        $('areaNombre').value='';
        $('organizacionNombre').disabled = true;
        $('sectorNombre').disabled = true;
        $('areaNombre').disabled = true;
        $('Grupo').disabled = false;
		
        $('lupaGrupo').setStyle('visibility', 'visible');
        $('lupaOrg').setStyle('visibility', 'hidden');
        $('lupaSect').setStyle('visibility', 'hidden');
        $('lupaArea').setStyle('visibility', 'hidden');
    });
	
    $('radio1').addEvent('click', function(){
        $('Grupo').value='';
        $('organizacionNombre').disabled = false;
        $('sectorNombre').disabled = false;
        $('areaNombre').disabled = false;
        $('Grupo').disabled = true;
		
        $('lupaGrupo').setStyle('visibility', 'hidden');
        $('lupaOrg').setStyle('visibility', 'visible');
        $('lupaSect').setStyle('visibility', 'visible');
        $('lupaArea').setStyle('visibility', 'visible');
    });
	
	
    window.addEvent('domready', function (){
	
	
        new Autocompleter.Request.HTML($('Grupo'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'			:	'grupoId',
                'nombre_campo' 		: 	'grupoNombre',
                'nombre_tabla' 		: 	'Grupos',
                'nombre_estado'		:   'grupoEstado'
            }
        });
	
        new Autocompleter.Request.HTML($('Evaluacion'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'			:	'evaluacionId',
                'nombre_campo' 		: 	'evaluacionNombre',
                'nombre_tabla' 		: 	'Evaluaciones',
                'nombre_estado'		:   'evaluacionEstado'
            }
        });
	
	
	
	
    });


    window.addEvent('domready', function() {    
        $('mBoxContainerOrg').set("opacity", 0);    
        $('mBoxContainerSec').set("opacity", 0);     
        $('mBoxContainerAre').set("opacity", 0);   
        $('mBoxContainerGru').set("opacity", 0);         
    });

    window.addEvent('domready', function() {       
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
        
        $('btnCancelarEva').addEvent('click', function(){

            var property = 'opacity';
            var to = "0";

            $('mBoxContainerEva').tween(property, to);

        });
    });


    $('ListarU').addEvent('click', function(){
        $('ListaUsuarios').set('html','');
        $('listado').setStyle('visibility', 'hidden');
        $('ErrorBusqueda').set('html','');
        var valorRadio=$('radio1').checked;
        var orgNombre=$('organizacionNombre').value;
        var secNombre=$('sectorNombre').value;
        var areaNombre=$('areaNombre').value;
	
	
	
        if(valorRadio==true){
			
            var elRequest1 = new Request({
                url		: '../librerias/listar_usuarios_envio_organizaciones.php', 
                method  :'post',
                onSuccess: function(html) {
                    $('ListaUsuarios').set('html','');
                    //Limpiar el Div y recargar la lista!!!
                    $('ListaUsuarios').set('html',html);
                    //var myTable = new HtmlTable($('ListadeUsuarios'), {sortable: true});
                    fdTableSort.init('ListadeUsuarios');
                    $('listado').setStyle('visibility', 'visible');
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest1.send(	"orgNombre=" 		        + orgNombre +
                "&secNombre="				+secNombre +
                "&areaNombre="				+areaNombre 
        );

        }else{
		
            if($('GrupoID').value=='0'||$('GrupoID').value==''){
                $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un nombre de Grupo para buscar</b></span>");
            }else{
                var grupoNombre=$('GrupoID').value;
			
                var elRequest = new Request({
                    url		: '../librerias/listar_usuarios_envio_grupo.php', 
                    method  :'post',
                    onSuccess: function(html) {
                        //Limpiar el Div y recargar la lista!!!
                        $('ListaUsuarios').set('html',html);
                        //var myTable = new HtmlTable($('ListadeUsuarios'), {sortable: true});
                        fdTableSort.init('ListadeUsuarios');
                        $('listado').setStyle('visibility', 'visible');
                    },
                    //Si Falla
                    onFailure: function() {
                        $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
                elRequest.send(	"grupoNombre=" + grupoNombre
            );
            }
        }
    });

    $('ListarE').addEvent('click', function(){
        $('ListaUsuarios').set('html','');
        $('listado').setStyle('visibility', 'hidden');
        $('ErrorBusqueda').set('html','');
	
        if($('EvaluacionID').value==''){
            $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un nombre de Evaluacion para buscar</b></span>");
        }else{
            var evalNombre=$('EvaluacionID').value;
			
            var elRequest = new Request({
                url		: '../librerias/listar_usuarios_envio_evaluaciones.php', 
                method  :'post',
                async       : false,
                onSuccess: function(html) {
                    //Limpiar el Div y recargar la lista!!!
                    $('ListaUsuarios').set('html',html);
                    fdTableSort.init('ListadeUsuarios');
                    $('listado').setStyle('visibility', 'visible');
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"evaluacionId=" + evalNombre
        );
        }
		
	
    });

    $('Grupo').addEvent('blur', function(){
        var elRequest = new Request({
            url		: '../librerias/traer_id_grupo.php', 
            method  :'post',
            async       : false,
            onSuccess: function(html) {
                //Limpiar el Div 
                $('ErrorBusqueda').set('html', "");
							
                if(html=='0'){
                    $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Grupo Existente</b></span>");
                }else{
                    $('GrupoID').set('value',html);
                }
							
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_grupo=" + encodeURIComponent($('Grupo').get('value')) );
    });	
	
    $('Evaluacion').addEvent('blur', function(){
        var elRequest = new Request({
            url		: '../librerias/traer_id_evaluacion.php', 
            method  :'post',
            async       : false,
            onSuccess: function(html) {
                //Limpiar el Div 
                $('ErrorBusqueda').set('html', "");
							
                if(html=='0'){
                    $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Evaluaci&oacute;n Existente</b></span>");
                }else{
                    $('EvaluacionID').set('value',html);
                }
							
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
					
					
        elRequest.send(	"nombre_eval=" + encodeURIComponent($('Evaluacion').get('value')) );
    });	
	
	
    function SeleccionarTodo(valor){
        var tabla=$('ListadeUsuarios');
        var largo=tabla.rows.length;	
        for (i=1;i<largo;i++){
            tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked=valor;
        }
    }
	
    function Validar(){
        //alert("validar");
        $('ErrorBusqueda').set('html', "");
        var tema=$('tema_id').get('value');
        var capsula=$('capsula_id').get('value');
                
        if(tema=="0"|| capsula=="0"){
            $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe Seleccionar un Tema y Cápsula que Existan</b></span>"); 
        }else{
            var chequeados=0;
            var tabla=$('ListadeUsuarios');
            if(tabla!=null){
                var largo=tabla.rows.length;	
                for (i=1;i<largo;i++){
                    if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
                        chequeados=chequeados+1;
                    };

                }
                if(chequeados==0){
                    $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe Seleccionar al menos un Usuario</b></span>");
                }else{
                    $('finalizar').disabled = true;
                    cantidadenvios=chequeados;
                    GenerarEnvios();

                }
            }else{
                $('ErrorBusqueda').set('html', "<span style='color:red'><b>Debe Seleccionar Usuarios</b></span>");
            }
                
        } 
    }
	
    function GenerarEnvios(){
        //se agregan estos dos campos para leerlos del form y no de la sesion:
        var tema=$('tema_id').get('value');
        var capsula=$('capsula_id').get('value');		
        //aca se recorre la tabla y se hace un request por cada envio, una vez realizado el ultimo, se limpia el div y se va a la siguiente pantalla
        var tabla=$('ListadeUsuarios');
        var largo=tabla.rows.length;	
        for (i=1;i<largo;i++){
            if(tabla.rows[i].cells[0].getElementsByTagName('input')[0].checked==true){
				
                var objInput1 = tabla.rows[i].cells[1];                                    
                var usuarioEnvio=(objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent;
                                
                var objInput2 = tabla.rows[i].cells[2];                                    
                var mailenvio=(objInput2.innerText != undefined) ? objInput2.innerText : objInput2.textContent ;
                                
                                
                //var usuarioEnvio=tabla.rows[i].cells[1].innerText;
				
                //var mailenvio=tabla.rows[i].cells[2].innerText;
				
                var usuarioIdEnvio=tabla.rows[i].cells[1].getElementsByTagName('input')[0].value;
                var grupoId=$('Grupo').value;
                if(grupoId==''){
                    grupoId=0;
                }else{
                    grupoId=$('GrupoID').value;
                }
                var envioId=$('envioId').value;
                var fechacierre=$('fecha_fin').value;
				
                divResultado=$('capaT');
				
                var elRequest1 = new Request({
                    url		: '../librerias/guardaEnvioCapsula.php', 
                    method  :'post',
                    link: 'chain',
                    onRequest: function(){
                        divResultado.set('html', "<b>Enviando...</b><img src='../scripts/img/spinner.gif'/>");
                        divResultado.setStyle('display', 'block');
                    },
                    onComplete: function(html) {
                        enviados=enviados+1;
                        divResultado.set('html', '<b>Enviado(s) '+enviados+' de '+cantidadenvios+ ' correo(s)</b><br/><img src="../scripts/img/spinner.gif"/>');
                        $('cantidadEnvios').set('value',enviados);
                        if(enviados==cantidadenvios){
                            divResultado.set('html', '<b>Env&iacute;o Completado</b>');
                            $('siguiente').disabled = false;
                            divResultado.setStyle('display', 'none');
                        }
                    },
						
                    onSuccess: function(html) {
							
                    },
                    //Si Falla
                    onFailure: function() {
                        //$('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
                //alert(capsula);
                elRequest1.send(	"usuarioEnvio=" 		    + encodeURIComponent(usuarioEnvio) +
                    "&mailenvio="				+ mailenvio +
                    "&usuarioIdEnvio="			+ usuarioIdEnvio +
                    "&envioId="					+ envioId +
                    "&grupoId="                 +grupoId+
                    "&fechacierre="				+fechacierre+
                    "&tema="+tema+
                    "&capsula="+capsula
            );
				
				
							
            }
			
        }
		
	
    }
	
    function Siguiente(){
        $('siguienteForm').submit();
    }
	
    function cancelar(){
        if (confirm('Seguro que desea cancelar la Envio de la C\u00e1psula?')==true){
            CancelarEnvioCapsula();
        }
    }
	

    function planSeleccionarOrganizaciones(){
    
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : 'FuncionBuscarOrganizaciones.php',
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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();  
                            
    
    }


    function planSeleccionarOrganizacion(){
        
        tabla = $('tablaListaOrganizaciones');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInput1 = tabla.rows[i].cells[1];            
                $('organizacionNombre').set('value', ((objInput1.innerText != undefined) ? objInput1.innerText : objInput1.textContent ));
                $('organizacionNombre').fireEvent('blur');
                i = filas;
            }           
        }
        
        $('mBoxContainerOrg').tween("opacity", 0);                            
    
    }


    function planSeleccionarSectores(){
    
        var organizacionNombre = ($('organizacionNombre').get('value'));
    
        var property = 'opacity';
        var to = "1";
                                           
        var elRequest = new Request({
            url         : 'FuncionBuscarSectores.php',
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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre));  
                                
    }


    function planSeleccionarSector(){
        
        tabla = $('tablaListaSectores');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInputSec = tabla.rows[i].cells[1];
                //$('organizacionNombre').set('value', ((objInputOrg.innerText != undefined) ? objInputOrg.innerText : objInputOrg.textContent ));
                $('sectorNombre').set('value', ((objInputSec.innerText != undefined) ? objInputSec.innerText : objInputSec.textContent ));
                $('sectorNombre').fireEvent('blur');
                i = filas;
            }           
        }
        
        $('mBoxContainerSec').tween("opacity", 0);                            
    
    }


    function planSeleccionarAreas(){
    
        var organizacionNombre = ($('organizacionNombre').get('value'));
        var sectorNombre = ($('sectorNombre').get('value'));
    
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : 'FuncionBuscarAreas.php',
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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send("organizacionNombre=" + encodeURIComponent(organizacionNombre) + 
            "&sectorNombre=" + encodeURIComponent(sectorNombre)
    );  
    }


    function planSeleccionarArea(){
        
        tabla = $('tablaListaAreas');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                // objInputOrg = tabla.rows[i].cells[1];
                // objInputSec = tabla.rows[i].cells[2];
                objInputAre = tabla.rows[i].cells[1];
                $('areaNombre').set('value', ((objInputAre.innerText != undefined) ? objInputAre.innerText : objInputAre.textContent ));
                $('areaNombre').fireEvent('blur');
                i = filas;
            }           
        }
        
        $('mBoxContainerAre').tween("opacity", 0);                            
    
    }


    function planSeleccionarGrupos(){
            
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : 'FuncionBuscarGrupos.php',
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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();  
    }


    function planSeleccionarGrupo(){
        
        tabla = $('tablaListaGrupos');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInputGru= tabla.rows[i].cells[1];            
                $('Grupo').set('value', ((objInputGru.innerText != undefined) ? objInputGru.innerText : objInputGru.textContent ));
                $('Grupo').fireEvent('blur');
                i = filas;
            }           
        }
        
        $('mBoxContainerGru').tween("opacity", 0);                            
    
    }


    function planSeleccionarEvaluaciones(){
            
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : 'FuncionBuscarEvaluaciones.php',
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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
            }
        });

        elRequest.send();  
    }


    function planSeleccionarEvaluacion(){
        
        tabla = $('tablaListaEvaluaciones');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInputEva = tabla.rows[i].cells[1];            
                $('Evaluacion').set('value', ((objInputEva.innerText != undefined) ? objInputEva.innerText : objInputEva.textContent ));
                $('Evaluacion').fireEvent('blur');
                i = filas;
            }           
        }
        
        $('mBoxContainerEva').tween("opacity", 0);                            
    
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
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',                                        
                            
            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id' : 'sectorId',
                'nombre_campo' : 'sectorNombre',
                'nombre_tablas' :       'From Sectores s (nolock) inner join Organizaciones o (nolock) on s.organizacionId = o.organizacionId',
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
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',                                        
                            
            // send additional POST data, check the PHP code
            'postData': {
                'nombre_id' : 'areaId',
                'nombre_campo' : 'areaNombre',
                'nombre_tablas' :       'From Areas a (nolock) inner join Sectores s (nolock) on a.sectorId = s.sectorId inner join Organizaciones o (nolock) on a.organizacionId = o.organizacionId',
                'nombre_where'      :       "and s.clienteId = " + $('clienteId').get('value') + " and o.organizacionNombre like '" + nombreOrg + "%' and s.sectorNombre like '" + nombreSec + "%' and o.organizacionEstado = 1 and s.sectorEstado = 1 and a.areaEstado = 1"
            }
        }); 
    }

    window.addEvent('domready', function (){
        $('mBoxContainerTema').setStyle('opacity', '0');
        $('mBoxContainerCap').setStyle('opacity', '0');
	
        $('lupaTema').addEvent('click',function(){
            SeleccionarTemas();
        });
	
        $('lupaCapsula').addEvent('click',function(){
            SeleccionarCapsulas();
        });
    });


    function SeleccionarCapsulas(){
            
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : '../librerias/FuncionBuscarCap.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('BoxContentCap').set('html',datos);                                                            
                    $('mBoxContainerCap').tween(property, to);
                    
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
            }
        });

        elRequest.send("temaNombre="+$('nombre_tema').value);  
    }

    function SeleccionarCap(){
        
        tabla = $('tablaListaCap');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInput1 = tabla.rows[i].cells[1].innerHTML;            
                $('nombre_capsula').set('value', objInput1);   // nombre del input tema
                $('nombre_capsula').fireEvent("blur");
                //$('lupaCapsula').focus();
                i = filas;
            }           
        }
        
        $('mBoxContainerCap').tween("opacity", 0);                            
    
    }




    function SeleccionarTemas(){
            
        var property = 'opacity';
        var to = "1";
                                           
    
        var elRequest = new Request({
            url         : '../librerias/FuncionBuscarTemas.php',
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
                alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
            }
        });

        elRequest.send();  
    }

    function SeleccionarTema(){
        
        tabla = $('tablaListaTemas');                
        filas = tabla.rows.length;
    
        for(i = 0; i < filas; i++){        
            objInput0 = tabla.rows[i].cells[0].getElementsByTagName('input')[0];
        
            if(objInput0.checked == true){
        
                objInput1 = tabla.rows[i].cells[1].innerHTML;            
                $('nombre_tema').set('value', objInput1);   // nombre del input tema
                $('nombre_tema').focus();
                $('nombre_capsula').focus();
                i = filas;
            }           
        }
        
        $('mBoxContainerTema').tween("opacity", 0);                            
    
    }


    function CancelaCap(){
        $('mBoxContainerCap').tween("opacity", 0); 
    }

    function CancelaTema(){
        $('mBoxContainerTema').tween("opacity", 0);   
    }

    function traeIdTema(){
        var valorFuncion=true;
        var elRequest = new Request({
            url		: '../librerias/traer_id_tema.php', 
            method  :'post',
            async       : false,
            onSuccess: function(html) {
                //Limpiar el Div 
                $('ErrorBusqueda').set('html', "");
							
                if(html=='0'){
                    if($('nombre_tema').value!=''){
                        $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
                        valorFuncion=fale;
                    }
                    $('tema_id').set('value',0);
                    valorFuncion=true;
                }else{
                    $('tema_id').set('value',html);
                    valorFuncion=true;
                }
							
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_tema=" 		+ $('nombre_tema').get('value') + 
            "&cliente_id=" 	+ $('cliente_id').get('value') +
            "&administrador_id=" + <? echo $administrador_id ?> + 
            "&perfil_id=" + <? echo $perfil_id ?>);
        return(valorFuncion);
    }

    function traeIdCap(){
        var valorFuncion=true;
        var elRequest = new Request({
            url		: '../librerias/verificar_nombre_capsula2.php', 
            method  :'post',
            async       : false,
            onSuccess: function(html) {
                //Limpiar el Div 
                $('nombre_capsula').set('html', "");
                if(html!=0){
                    $('capsula_id').set('value', html);
                    valorFuncion=true;
                }else{
                    $('capsula_id').set('value', html);
                    if($('nombre_capsula').value!=''){
                        $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                        valorFuncion=false;
                    }
                }
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_capsula=" + $('nombre_capsula').get('value') + 
            "&cliente_id=" 	+ $('cliente_id').get('value') +
            "&administrador_id=" + <? echo $administrador_id ?> + 
            "&perfil_id=" + <? echo $perfil_id ?>);

        return(valorFuncion);						
    }

    window.addEvent('domready', function (){

        new Autocompleter.Request.HTML($('nombre_tema'), '../librerias/autocompleter_temas.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'		: 'temaId',
                'nombre_campo' 		: 'temaNombre',
                'nombre_tabla' 		: 'temas',
                'nombre_estado'		: 'temaEstado',
                'clienteId'		: <? echo $cliente_id ?>,
                'administradorId'       : <? echo $administrador_id ?>,
                'perfilId'              : <? echo $perfil_id ?>
            }
        });
	
	
	
        $('nombre_tema').addEvent('blur', function(){
            var elRequest = new Request({
                url		: '../librerias/traer_id_tema.php', 
                method  :'post',
                async       : false,
                onSuccess: function(html) {
                    //Limpiar el Div 
                    $('ErrorBusqueda').set('html', "");
							
                    if(html=='0'){
                        $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
                        $('tema_id').set('value',0);

                    }
                    else{
                        $('tema_id').set('value',html);
                        $('nombre_capsula').set('value','');
	
                    }
							
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"nombre_tema=" 		+ $('nombre_tema').get('value') + 
                "&cliente_id=" 	+ $('cliente_id').get('value') + 
                "&administrador_id=" + <? echo $administrador_id ?> + 
                "&perfil_id=" + <? echo $perfil_id ?>);
        });
	
  
	
       
        
	
        $('nombre_capsula').addEvent('blur', function(){
            if($('nombre_capsula').value!=''){
                var elRequest = new Request({
                    url		: '../librerias/verificar_nombre_capsula2.php', 
                    method  :'post',
                    async       : false,
                    onSuccess: function(html) {
                        //Limpiar el Div 
                        $('ErrorBusqueda').set('html', "");
                        if(html!=0){
                            $('capsula_id').set('value', html);
                        }else{
                            $('capsula_id').set('value', html);
                            $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                        }
                    },
                    //Si Falla
                    onFailure: function() {
                        $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
                elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
                    "&cliente_id=" 	+ $('cliente_id').get('value'));
            }
        });
	
    });

    function TraeCapsulasAuto(){
    
        var temaId = $('tema_id').get('value');
    
        if(temaId == ""){
            temaId = "0";
        }
    
        new Autocompleter.Request.HTML($('nombre_capsula'), '../librerias/autocompleterMantenedores.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',

            'postData': {
                'nombre_id'		:	'capsulaId',
                'nombre_campo' 	: 	'capsulaNombre',
                'nombre_tablas' 	:' From Capsulas  (nolock)',
                'nombre_where'      :' and temaId = ' + temaId 
            }

        });

    }

    function Volver(){
        window.location = '../reportes/ListaEnvios_00.php'
    }
</script>