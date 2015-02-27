<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
//$capsula_id     = $_POST['capsulaId2'] ? $_POST['capsulaId2']:0;
//$capsulaVersion = $_POST['capsulaVersion2']? $_POST['capsulaVersion2']:0;
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
  $nombre = $row["capsulaNombre"];
  $tipo = $row["capsulaTipo"];
 */

// $queryP 	= "EXEC capListarPersonalizacion ".$clienteId." ";
// $resultP = $base_datos->sql_query($queryP);
// $rowP	= $base_datos->sql_fetch_assoc($resultP);
// $personalizacionId=$rowP['personalizacionId'] ? $rowP['personalizacionId']:0;
$subject = 'Capsula de Prueba';
$encabezado = 'Esta es una C&aacute:psula de prueba';
$footerT = '';
?>

<div>
    <?
    $Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";
        ?>
        <?
    } else {

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha"
        ));


        echo $plantilla->show();

        $plantilla->setTemplate("envio_capsula_prueba_menu");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "CLIENTE" => "$Cliente_id",
            "NOMBRE" => "$nombre",
            "CAPSULA_ID" => "$capsula_id",
            "CAPSULA_VER" => "$capsulaVersion",
            "TIPO" => "$tipo",
            "SUBJECT" => "$subject",
            "ENCABEZADO" => "$encabezado",
            "FOOTERT" => "$footerT",
            "PERSONALIZACIONID" => "$personalizacionId"
        ));
        echo $plantilla->show();
    }
    ?>
</div>
    <?
    $plantilla->setTemplate("footer_2");
    echo $plantilla->show();
    ?>
<script>

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
                    $('ErrorTema').set('html', "");
							
                    if(html=='0'){
                        $('ErrorTema').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
                        $('tema_id').set('value',0);
                    }
                    else{
                        $('tema_id').set('value',html);
                        $('nombre_capsula').set('value','');
                    }
							
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorTema').set('html', 'Error de conexi&oacute;n');
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
                        $('ErrorNombre').set('html', "");
                        if(html!=0){
                            $('capsula_id').set('value', html);
                        }else{
                            $('capsula_id').set('value', html);
                            $('ErrorNombre').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                        }
                    },
                    //Si Falla
                    onFailure: function() {
                        $('ErrorNombre').set('html', 'Error de conexi&oacute;n');
                    }
                });
                elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
                    "&cliente_id=" 	+ $('cliente_id').get('value'));
            }
        });
	
    });

    function Validar(){
        var email=$('email').value;
	
        //$('ErrorEmail').set('html','');

        var nombrecap=$('nombre_capsula').value;
        var nombretem=$('nombre_tema').value;
        var capsulaid=$('capsula_id').value;
        var temaid=$('tema_id').value;
	

        if(validarEmail(email)==true){
            $('ErrorEmail').set('html', "<span style='color:red'><b>Debe ingresar un correo de destino v&aacute;lido</b></span>");
        }else{
	
            if(nombretem==''||temaid==0){
                $('ErrorTema').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
            }
		
            if(nombrecap==''||capsulaid==0){
                $('ErrorNombre').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
            }
		
            if(nombretem!=''&& nombrecap!='' &&temaid!=0 && capsulaid!=0){
                if(traeIdTema()==true&&traeIdCap()==true){
                    var elRequest = new Request({
                        url		: '../librerias/EnviarCapsulaPrueba_menu.php', 
                        method  :'post',
                        async: 'true',
                        onRequest: function(){
                            $('ErrorEmail').set('html', "Enviando...<img src='../scripts/img/loader.gif'/>");
                        },
                        onComplete: function(html) {
                            if(html==1){
                                $('ErrorEmail').set('html','<b>C&aacute;psula de prueba enviada exitosamente</b>');
                            }else{
                                $('ErrorEmail').set('html', "<span style='color:red'><b>Error al Enviar la C&aacute;psula</b></span>");
                            }
							
                            //$('ErrorEmail').set('html',html);
                        },
                        //Si Falla
                        onFailure: function() {
                            $('ErrorFooter').set('html', 'Error de conexi&oacute;n');
                        }
                    });
                    elRequest.send(	"mailenvio=" + email +"&capsula_id="+capsulaid);
                }
            }
        }

    }

    function cancelar(){
        if (confirm('Seguro que desea cancelar la Envio de la C\u00e1psula?')==true){
            $('SiguienteForm').action='adm_capsulas.php';
            $('SiguienteForm').submit();
        }
    }


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
                alert("Se ha producido un error. Por favor, int?ntelo m?s tarde.");
            }
        });

        elRequest.send("temaNombre="+$('nombre_tema').value);  
    }

    function SeleccionarCap(){
        
        var tabla = $('tablaListaCap');                
        var filas = tabla.rows.length;
    
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
                alert("Se ha producido un error. Por favor, int?ntelo m?s tarde.");
            }
        });

        elRequest.send();  
    }

    function SeleccionarTema(){
        
        var tabla = $('tablaListaTemas');                
        var filas = tabla.rows.length;
    
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
                $('ErrorTema').set('html', "");
							
                if(html=='0'){
                    if($('nombre_tema').value!=''){
                        $('ErrorTema').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
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
                $('ErrorTema').set('html', 'Error de conexi&oacute;n');
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
                        $('nombre_capsula').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                        valorFuncion=false;
                    }
                }
            },
            //Si Falla
            onFailure: function() {
                $('nombre_capsula').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest.send(	"nombre_capsula=" + $('nombre_capsula').get('value') + 
            "&cliente_id=" 	+ $('cliente_id').get('value') +
            "&administrador_id=" + <? echo $administrador_id ?> + 
            "&perfil_id=" + <? echo $perfil_id ?>);

        return(valorFuncion);						
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

    function completar(){
        var temaid=$('tema_id').get('value');
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
                'nombre_where'      :' and temaId =' +  temaid
            }
								
        });
    }
</script>