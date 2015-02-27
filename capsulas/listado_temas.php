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



<script type="text/javascript" src="../scripts/mediabox/mediaboxAdv-1.2.5b.js"></script>
<link rel="stylesheet" type="text/css" href="../scripts/mediabox/mediaboxAdvBlack21.css"/>

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

        $queryL = "Select LTRIM(RTRIM(clienteNombres)) + ' ' + LTRIM(RTRIM(clienteApellidos)) as 'clienteNombreCompleto'
                            From Clientes c (nolock)                        
                            Where c.clienteId = " . $clienteId . "";


        $resultL = $base_datos->sql_query($queryL);
        $rowL = $base_datos->sql_fetch_assoc($resultL);
        $clienteNombre = $rowL['clienteNombreCompleto'] ? $rowL['clienteNombreCompleto'] : '';


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

        //echo $plantilla->show();

        $plantilla->setTemplate("listado_temas");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "CLIENTE" => "$Cliente_id",
            "CLIENTENOMBRE" => "$clienteNombre"
        ));
        echo $plantilla->show();
    }
    ?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>


<script type="text/javascript" type="text/javascript">

    window.addEvent('domready', function (){
        
        validar();

        new Autocompleter.Request.HTML($('nombre_tema'), '../librerias/autocompleter.php', {
            // class added to the input during request
            'indicatorClass': 'autocompleter-loading', 
            'minLength': 1,
            'overflow': true,
            'selectMode': 'type-ahead',
            'postData': {
                'nombre_id'		:'temaId',
                'nombre_campo' 		:'temaNombre',
                'nombre_tabla' 		:'temas',
                'nombre_estado'		:'temaEstado'
            }
        });
        //ACA DEBE USAR EL NOMBRE Y NO EL ID, PORQUE EL REQUEST NO SE LA PUEDE PARA TRAER ANTES DE EJECUTAR EL REQUEST DE LA BUSQUEDA.

								

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
                    }else{
                        $('tema_id').set('value',html);
                    }
							
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorTema').set('html', "");
                }
            });
            elRequest.send(	"nombre_tema=" 		+ $('nombre_tema').get('value') + 
                "&cliente_id=" 	+ $('cliente_id').get('value'));
        });			        		
    });

    window.addEvent('domready', function (){
        $('mBoxContainerTema').setStyle('opacity', '0');

        $('lupaTema').addEvent('click',function(){
            SeleccionarTemas();            
        });
	
    });


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
                alert("Se ha producido un error. Por favor, intentelo mas tarde.");
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
                i = filas;
            }           
        }
        
        $('mBoxContainerTema').tween("opacity", 0);                            
    
    }



    function CancelaTema(){
        $('mBoxContainerTema').tween("opacity", 0);   
    }

    function traeIdTema(){
        var valorFuncion=true;
        var elRequest = new Request({
            url	: '../librerias/traer_id_tema.php', 
            method  :'post',
            async   : false,
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

    function Volver(){
        window.location = 'adm_capsulas.php'
    }

    function Nuevo(){
        window.location = 'crear_tema_Mant.php'
    }

    function validar(){
        var nombre_tema=$('nombre_tema').value;
        var tema_id=$('tema_id').value;
	
	
        if(nombre_tema!=''&&tema_id=='0'){
            $('ErrorTema').set('html', "<span style='color:red'><b>Debe ingresar un Nombre de Tema Existente</b></span>");
        }else{
			
            if(traeIdTema()==true){
                var elRequest1 = new Request({
                    url		: '../librerias/listar_temas_m.php', 
                    method  :'post',
                    async       : false,
                    onSuccess: function(html) {

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
                        
                        Mediabox.scanPage();
                        
                    },
                    //Si Falla
                    onFailure: function() {
                        $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
                    }
                });
						
						
						
						
                elRequest1.send(	"temaId=" 		        + $('tema_id').value);
            }
        }
			

    }

    function verTema(temaId){
        var link='verTema.php?tema='+temaId;
        $('mb14').href=link;
        $('mb14').click();
        
    }

    function EditarTema(temaId){
        $('tema').value=temaId;
        $('redir').submit();
        
    }


</script>