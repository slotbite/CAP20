<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();
?>

<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />

<script type="text/javascript" language="javascript">
    var searchBoxPpal;
    window.addEvent('domready', function(){
        searchBoxPpal = new multiBox({
            mbClass: '.mb',//class you need to add links that you want to trigger multiBox with (remember and update CSS files)
            container: $(document.body),//where to inject multiBox
            showNumbers: false,
            descClassName: 'multiBoxDesc',//the class name of the description divs
            path: './Files/',//path to mp3 and flv players
            useOverlay: true,//use a semi-transparent background. default: false;
            maxSize: {w:1250, h:540},//max dimensions (width,height) - set to null to disable resizing
            addDownload: false,//do you want the files to be downloadable?
            pathToDownloadScript: './Scripts/forceDownload.asp',//if above is true, specify path to download script (classicASP and ASP.NET versions included)
            addRollover: true,//add rollover fade to each multibox link
            addOverlayIcon: true,//adds overlay icons to images within multibox links
            addChain: true,//cycle through all images fading them out then in
            recalcTop: true,//subtract the height of controls panel from top position
            addTips: false,//adds MooTools built in 'Tips' class to each element (see: http://mootools.net/docs/Plugins/Tips)
            autoOpen: 0//to auto open a multiBox element on page load change to (1, 2, or 3 etc)
        });
	
    });
</script>
<script type="text/javascript" src="../scripts/tablesort.js"></script>
<div>
    <?
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";
        ?>
        <?
    } else {
        $Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");

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

        $plantilla->setTemplate("listado_capsulas");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "CLIENTE" => "$Cliente_id"
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
    var acCap;

    window.addEvent('domready', function (){

        new Autocompleter.Request.HTML($('nombre_tema'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',
            'postData': {
                'nombre_id'			:	'temaId',
                'nombre_campo' 		: 	'temaNombre',
                'nombre_tabla' 		: 	'temas',
                'nombre_estado'		:   'temaEstado'
            }
        });
        //ACA DEBE USAR EL NOMBRE Y NO EL ID, PORQUE EL REQUEST NO SE LA PUEDE PARA TRAER ANTES DE EJECUTAR EL REQUEST DE LA BUSUQEDA.

								

        $('nombre_tema').addEvent('blur', function(){
            var elRequest = new Request({
                url		: '../librerias/traer_id_tema.php', 
                method  :'post',
                async       : false,
                onSuccess: function(html) {
                    //Limpiar el Div 
                    $('ErrorTema').set('html', "");
							
                    if(html=='0'){
                        $('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema Existente</b></span>");
                        $('tema_id').set('value','0');
                        $('nombre_capsula').set('value','');
                        $('ErrorNombre').set('html', '');
                        $('nombre_capsula').removeEvents();
                    }else{
                        $('tema_id').set('value',html);
                        $('nombre_capsula').set('value','');
								
                        acCap=new Autocompleter.Request.HTML($('nombre_capsula'), '../librerias/autocompleterMantenedores.php', {
                            // class added to the input during request
                            'indicatorClass': 'autocompleter-loading', 
                            'minLength': 1,
                            'overflow': true,
                            'selectMode': 'type-ahead',
								
                            'postData': {
                                'nombre_id'		:	'capsulaId',
                                'nombre_campo' 	: 	'capsulaNombre',
                                'nombre_tablas' 	:' From Capsulas  (nolock)',
                                'nombre_where'      :' and temaId =' + $('tema_id').get('value') 
                            }
                        });
									
                        $('nombre_capsula').addEvent('blur', function(){
                            $('ErrorNombre').set('html', '');
                            $('capsula_id').set('value', '0');
                            if($('nombre_capsula').get('value')!=''){
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
                                            $('ErrorNombre').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                                        }
                                    },
                                    //Si Falla
                                    onFailure: function() {
                                        $('ErrorNombre').set('html', 'Error de conexi&oacute;n');
                                    }
                                });
												
                                elRequest.send(	"nombre_capsula="+ $('nombre_capsula').get('value') + 
                                    "&cliente_id=" 	+ $('cliente_id').get('value'));
                            }							
                        });
									
					
                    }
							
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorNombre').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"nombre_tema=" 		+ $('nombre_tema').get('value') + 
                "&cliente_id=" 	+ $('cliente_id').get('value'));
        });	
	
	
	
	
	
    });

    function validar(){
        var nombre_tema=$('nombre_tema').value;
        var tema_id=$('tema_id').value;
        var nombre_capsula=$('nombre_capsula').value;
        var capsula_id=$('capsula_id').value;
	
        if(nombre_tema!=''&&tema_id=='0'){
            $('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema Existente</b></span>");
        }else{
            if(nombre_capsula!=''&&capsula_id=='0'){
                $('ErrorNombre').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
            }else{
				
                if(nombre_capsula==''){
                    capsula_id=0;
                }
			
                if(traeIdTema()==true&&traeIdCap()==true){
                    var elRequest1 = new Request({
                        url		: '../librerias/listar_capsulas.php', 
                        method  :'post',
                        async       : false,
                        onSuccess: function(html) {
                            $('ListaUsuarios').set('html','');
                            //Limpiar el Div y recargar la lista!!!
                            $('ListaUsuarios').set('html',html);
                            fdTableSort.init('ListadeCapsulas');
                            var filastabla=$('ListadeCapsulas').rows.length;
                            if(filastabla==1){
                                $('ErrorListado').setStyle('display', 'block');
                                $('listado').setStyle('display', 'none');
                            }else{
                                $('ErrorListado').setStyle('display', 'none');
                                $('listado').setStyle('display', 'block');
                            }
                        },
                        //Si Falla
                        onFailure: function() {
                            $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                        }
                    });
						
						
						
						
                    elRequest1.send(	"temaId=" 		        + $('tema_id').value +
                        "&capsulaId="			+$('capsula_id').value
                );
                }
            }
        }
		
		
	
	

	

    }

    function CambiarEstadoCapsula(tema,capsula,version,accion){
        var mensaje="";
        if(accion==2){
            mensaje='Anular';
        }else{
            mensaje='Activar';
        }

        if(confirm("Desea " + mensaje + " la C\u00e1psula?")==true){
            //alert('tema:'+tema+'cap:'+capsula+'version:'+version);
		
            var elRequest1 = new Request({
                url		: '../librerias/anular_capsula.php', 
                method  :'post',
                onSuccess: function(html) {
                    validar();
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest1.send(	"temaId=" 		        + tema    +
                "&capsulaId="			+ capsula +
                "&version="				+ version +
                "&estado="				+ accion
        );
        }

    }

    function EditarCapsula(tema,capsula,version,enviado,planificado) {
        var mensaje="Esta C\u00e1psula ya ha sido ";
        var mensaje1="";
        var confirmar=false;
	
        if(enviado==1){
            mensaje1=" Enviada ";
            confirmar=true;
            if(planificado==1){
                mensaje1=" Enviada y Planificada";
            }
        }else{
            if(planificado==1){
                mensaje1=" Planificada";
                confirmar=true;
            }
        }
	
	
        mensaje= mensaje+ mensaje1+'\nSi la Edita crear\u00e1 una nueva version\n\u00bfDesea Continuar?';
        if(confirmar==true){
            if(confirm(mensaje)==true){
                
                $('capsulaIdAsistente').value = capsula;
                $('capsulaVersionAsistente').value = version;
                $('btnCapsulaAsistente').click();
                
                //var link='wizard_capsula_1_ed.php?tema='+tema+'&capsula='+capsula+'&version='+version;
                //$('mb14').href=link;
                //$('mb14').click();
            }
        }else{
            //var link='wizard_capsula_1_ed.php?tema='+tema+'&capsula='+capsula+'&version='+version;
            //$('mb14').href=link;
            //$('mb14').click();

            $('capsulaIdAsistente').value = capsula;
            $('capsulaVersionAsistente').value = version;
            $('btnCapsulaAsistente').click();
        }
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
            url         : '../librerias/FuncionBuscarCap2.php',
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

        elRequest.send("temaId="+$('tema_id').value);  
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
            "&cliente_id=" 	+ $('cliente_id').get('value'));
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
        elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
            "&cliente_id=" 	+ $('cliente_id').get('value'));

        return(valorFuncion);						
    }

    function Volver(){
        window.location = 'adm_capsulas.php'
    }

    function verCap(capsulaID,version){
        var link='../librerias/vprevia_Capsula.php?capsulaId='+capsulaID+'&version='+version;
        $('mb14').href=link;
        $('mb14').click();
    }
</script>


<form id="frmEdiCap" name="frmEdiCap" method="post" action="../capsula/capAsistente.php" style="display:none">
    <input id="capsulaIdAsistente" name="capsulaId" type="text"/> 
    <input id="capsulaVersionAsistente" name="capsulaVersion" type="text"/>     
    <input id="btnCapsulaAsistente" type="submit"/>
</form>



