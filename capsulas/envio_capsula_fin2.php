<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2'] : 0;
$capsulaVersion = $_SESSION['capsulaVersion2'] ? $_SESSION['capsulaVersion2'] : 0;
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

$envioId = $_POST['envioId'] ? $_POST['envioId'] : 0;


$queryTipo = "EXEC capVerCapsula " . $clienteId . "," . $capsula_id . "," . $capsulaVersion . " ";
$result = $base_datos->sql_query($queryTipo);
$row = $base_datos->sql_fetch_assoc($result);
$nombre = mb_convert_encoding($row["capsulaNombre"], "UTF-8", "ISO-8859-1");
$tipo = $row["capsulaTipo"];
?>
<div>
    <?
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

        $plantilla->setTemplate("menu2");
        $fecha = date("d-m-Y");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "FECHA" => "$fecha"
        ));


        echo $plantilla->show();

        $plantilla->setTemplate("envio_capsula_fin2");
        $plantilla->setVars(array("USUARIO" => "$usuario_id",
            "CLIENTE" => "$Cliente_id",
            "NOMBRE" => "$nombre",
            "CAPSULA_ID" => "$capsula_id",
            "CAPSULA_VER" => "$capsulaVersion",
            "PERSONALIZACIONID" => "$personalizacionId",
            "ENVIOID" => "$envioId"
        ));
        echo $plantilla->show();
    }
    ?>
</div>
<script>
    window.addEvent('domready', function (){
        //estilos para mis "TABS":
        $('Correctos').setStyle('display', 'block');
        $('Incorrectos').setStyle('display', 'none');
        // $('Usuarios1').setStyle('background-color','#003366');
        // $('Usuarios1').setStyle('color','white');
	
        // $('Evaluaciones1').setStyle('background-color','white');
        // $('Evaluaciones1').setStyle('color','black');
	
        $('Usuarios1').setStyle('cursor','default');
        $('Evaluaciones1').setStyle('cursor','default');
    });
	
    $('Usuarios1').addEvent('click', function(){
        $('Correctos').setStyle('display', 'block');
        $('Incorrectos').setStyle('display', 'none');
        // $('Usuarios1').setStyle('background-color','#003366');
        // $('Usuarios1').setStyle('color','white');
        // $('Evaluaciones1').setStyle('background-color','white');
        // $('Evaluaciones1').setStyle('color','black');
    });
	
    $('Evaluaciones1').addEvent('click', function(){
        $('Correctos').setStyle('display', 'none');
        $('Incorrectos').setStyle('display', 'block');
        // $('Evaluaciones1').setStyle('background-color','#003366');
        // $('Evaluaciones1').setStyle('color','white');
        // $('Usuarios1').setStyle('background-color','white');
        // $('Usuarios1').setStyle('color','black');
    });
	
    window.addEvent('domready', function (){	
				
        var elRequest1 = new Request({
            url		: '../librerias/listar_usuarios_envio_estado.php', 
            method  :'post',
            async:'false',
            onSuccess: function(html) {
                $('ListaUsuarios').set('html','');
                //Limpiar el Div y recargar la lista!!!
                $('ListaUsuarios').set('html',html);
                tabla=$('ListadeUsuarios');
                catnRegistros=tabla.rows.length-1;
                fdTableSort.init('ListadeUsuarios');
                if(catnRegistros!=0){
                    $('correctos1').setStyle('display', 'block');
                    $('correctosError').setStyle('display', 'none');
                }else{
                    $('correctos1').setStyle('display', 'none');
                    $('correctosError').setStyle('display', 'block');
                }
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest1.send(	"envioId=" 		        + <? echo $envioId; ?> +
            "&estado="				+1+
            "&capsulaId="           +<? echo $capsula_id; ?>+
        "&capsulaVerdion="		+<? echo $capsulaVersion; ?>
    );
							

							
        var elRequest2 = new Request({
            url		: '../librerias/listar_usuarios_envio_estado2.php', 
            method  :'post',
            async:'false',
            onSuccess: function(html) {
                $('ListaUsuarios2').set('html','');
                //Limpiar el Div y recargar la lista!!!
                $('ListaUsuarios2').set('html',html);
                tabla=$('ListadeUsuarios2');
                cant=tabla.rows.length-1;
                fdTableSort.init('ListadeUsuarios2');
                if(cant!=0){
                    $('fallidos').setStyle('display', 'block');
                    $('fallidosError').setStyle('display', 'none');
                }else{
                    $('fallidos').setStyle('display', 'none');
                    $('fallidosError').setStyle('display', 'block');
                }
            },
            //Si Falla
            onFailure: function() {
                $('ErrorBusqueda').set('html', 'Error de conexi&oacute;n');
            }
        });
        elRequest2.send(	"envioId=" 		        + <? echo $envioId; ?> +
            "&estado="				+2+
            "&capsulaId="           +<? echo $capsula_id; ?>+
        "&capsulaVerdion="		+<? echo $capsulaVersion; ?>
    );

    });
	
    function Finalizar(){
        CerrarWizard();
        window.location='../reportes/ListaEnvios_00.php';
    }
</script>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>