<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

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
<script type="text/javascript" src="../scripts/tablesort.js"></script>
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
            maxSize: {w:900, h:520},//max dimensions (width,height) - set to null to disable resizing
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
<div>
    <?
    $usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';

    if ($usuario_id == '') {
        echo "<script>window.location='../index.php';</script>";
        ?>
        <?
    } else {

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

        $plantilla->setTemplate("ListaEnvios_00");
        $plantilla->setVars(array("CLIENTE" => "$cliente_id"));
        echo $plantilla->show();
    }
    ?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
<script>
    function nuevo(){
        window.location='../capsulas/envio_capsula_1.php';
    }

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
	
        $('nombre_tema').addEvent('blur', function(){
            var elRequest = new Request({
                url		: '../librerias/traer_id_tema.php', 
                method  :'post',
                onSuccess: function(html) {
                    //Limpiar el Div 
                    $('ErrorBusqueda').set('html', "");
							
                    if(html=='0'){
                        if($('nombre_tema').value!=''){
                            $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de Tema Existente</b></span>");
                        }
                        $('tema_id').set('value',0);
                        $('nombre_capsula').set('value','');
                        $('nombre_capsula').set('disabled','disabled');
                    }else{
                        $('tema_id').set('value',html);
                        $('nombre_capsula').set('value','');
                        $('nombre_capsula').set('disabled','');
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
                                'nombre_where'      :' and temaId =' + $('tema_id').get('value') 
                            }
								
                        });
								
								
                    }
							
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"nombre_tema=" 		+ $('nombre_tema').get('value') + 
                "&cliente_id=" 	+ $('cliente_id').get('value'));
        });
	
	
        $('nombre_capsula').addEvent('change', function(){
            var elRequest = new Request({
                url		: '../librerias/verificar_nombre_capsula2.php', 
                method  :'post',
                onSuccess: function(html) {
                    //Limpiar el Div 
                    $('ErrorBusqueda').set('html', "");
                    if(html!=0){
                        $('capsula_id').set('value', html);
                    }else{
                        $('capsula_id').set('value', html);
                        if($('nombre_capsula').value!=''){
                            $('ErrorBusqueda').set('html', "<span style='color:red'><b>ERROR: Debe ingresar un Nombre de C&aacute;psula Existente</b></span>");
                        }
                    }
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
                "&cliente_id=" 	+ $('cliente_id').get('value'));
        });
	
        $('mBoxContainerTema').setStyle('opacity', '0');
        $('mBoxContainerCap').setStyle('opacity', '0');
	
        $('lupaTema').addEvent('click',function(){
            SeleccionarTemas();
        });
	
        $('lupaCapsula').addEvent('click',function(){
            SeleccionarCapsulas();
        });
	
        Inicial();
	
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
                //$('nombre_capsula').click();
                $('nombre_capsula').fireEvent("blur");
                $('lupaCapsula').focus();
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
                $('nombre_tema').fireEvent("blur")
                i = filas;
            }           
        }
        
        $('mBoxContainerTema').tween("opacity", 0);                            
    
    }

    function ValidaBuscar(){
        $('Busqueda').set('html','')
        var fecha1=$('FechaInicio').value;
        var fecha2=$('FechaTermino').value;
        valido=true;
	
        //traeIdTema();
        //traeIdCap();
	
        if(traeIdTema()==false){
            valido=false;
        }
	
        if(traeIdCap()==false){
            valido=false;
        }
	

        if(fecha1!=''){
            if(fecha2==''){
                alert('Debe ingresar ambas fechas para buscar por rango');
                valido=false;
            }
        }
	
	
        if(fecha2!=''){
            if(fecha1==''){
                alert('Debe ingresar ambas fechas para buscar por rango');
                valido=false;
            }
        }
	
	
        if(fecha1!=''&&fecha2!=''){
            if(compara_fechas(fecha2,fecha1)==false){
                alert('La fecha inicio debe ser anterior a la fecha fin');
                valido=false;
            }
        }
	
        if(valido==true){
	
            var envioId=$('IdEnvio').value;
            var temaId=$('tema_id').value;
            var capsulaId=$('capsula_id').value;
            var inicio=$('FechaInicio').value;
            var fin=$('FechaTermino').value;
            var inicial=0;
	
            //alert(temaId);
            //alert(capsulaId);
		
            var elRequest = new Request({
                url         : '../librerias/listar_Envios.php',
                method      : 'post',
                async       : false,
        
                onSuccess   : function(datos) {
                    if(datos)
                    {                
                        $('Busqueda').set('html',datos)
                        fdTableSort.init('ListadeEnvios');
                    }                        
                },
                //Si Falla
                onFailure: function() {
                    alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
                }
            });

            elRequest.send(
            "&envioId="+envioId+
                "&temaId="+$('tema_id').value+
                "&capsulaId="+$('capsula_id').value+
                "&inicio="+inicio+
                "&fin="+fin+
                "&inicial="+inicial
					
        );  
		
        }
	
    }

    function Inicial(){

        var envioId=$('IdEnvio').value;
        var temaId=0;
        var capsulaId=0;
        var inicio='';
        var fin='';
        var inicial=1;
		
        var elRequest = new Request({
            url         : '../librerias/listar_Envios.php',
            method      : 'post',
            async       : false,
        
            onSuccess   : function(datos) {
                if(datos)
                {                
                    $('Busqueda').set('html',datos)
                    fdTableSort.init('ListadeEnvios');
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
            }
        });

        elRequest.send(
        "&envioId="+envioId+
            "&temaId="+temaId+
            "&capsulaId="+capsulaId+
            "&inicio="+inicio+
            "&fin="+fin+
            "&inicial="+inicial
					
    );  
	
    }

    function VerLog(envio,capsula, version)
    {
        var elRequest = new Request({
            url         : '../librerias/verLogEnvio.php',
            method      : 'post',
            async       : false,
			
            onSuccess   : function(datos) {
                if(datos)
                {            
				   
                    $('envioId').value=envio;
                    $('redir2').submit();
                }                        
            },
            //Si Falla
            onFailure: function() {
                alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
            }
        });
		
        elRequest.send(
        "capsulaId="+capsula+
            "&version="+version
					
    );  
    }

    function CancelaCap(){
        $('mBoxContainerCap').tween("opacity", 0); 
    }

    function CancelaTema(){
        $('mBoxContainerTema').tween("opacity", 0);   
    }

    function Resetear(envioId){
        var link='../capsulas/resetearEnvio.php?envioId='+envioId;
        $('mb14').href=link;
        $('mb14').click();
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
                $('ErrorBusqueda').set('html', "");
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
        elRequest.send(	"nombre_capsula=" 		+ $('nombre_capsula').get('value') + 
            "&cliente_id=" 	+ $('cliente_id').get('value'));

        return(valorFuncion);						
    }

    function Volver(){
        window.location = '../capsulas/adm_capsulas.php'
    }

</script>