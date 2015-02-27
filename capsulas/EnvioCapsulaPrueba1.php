<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2'] : 0;
$capsulaVersion = $_SESSION['capsulaVersion2'] ? $_SESSION['capsulaVersion2'] : 0;
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] : 0;

$plantilla->setTemplate("header_3");

echo $plantilla->show();


$queryTipo = "EXEC capVerCapsula " . $clienteId . "," . $capsula_id . "," . $capsulaVersion . " ";
$result = $base_datos->sql_query($queryTipo);
$row = $base_datos->sql_fetch_assoc($result);
$nombre = $row["capsulaNombre"];
$tipo = $row["capsulaTipo"];


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

// $plantilla->setTemplate("menu2");
// $fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO" =>"$usuario_id",
        // "FECHA"	  =>	"$fecha"
        // ));
// echo $plantilla->show();

        $plantilla->setTemplate("envio_capsula_prueba1");
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
// $plantilla->setTemplate("footer_2");
// echo $plantilla->show();
    ?>
<script>
    function Validar(){
        var email=$('email').value;
	
        $('ErrorEmail').set('html','');


        if(validarEmail(email)==true){
            $('ErrorEmail').set('html', "<span style='color:red'><b>Debe ingresar un correo de destino v&aacute;lido</b></span>");
        }else{
	
	
            var elRequest = new Request({
                url		: '../librerias/EnviarCapsulaPrueba.php', 
                method  :'post',
                onRequest: function(){
                    $('ErrorEmail').set('html', "Enviando...<img src='../scripts/img/loader.gif'/>");
                },
                onComplete: function(html) {
                    if(html==1){
                        $('ErrorEmail').set('html','<b>C&aacute;psula de prueba enviada exitosamente</b>');
                        $('salir').disabled = false;
                    }else{
                        $('ErrorEmail').set('html', "<span style='color:red'><b>Error al Enviar la C&aacute;psula</b></span>");
                        $('salir').disabled = true;
                    }
                },
                //Si Falla
                onFailure: function() {
                    $('ErrorFooter').set('html', 'Error de conexi&oacute;n');
                }
            });
            elRequest.send(	"mailenvio=" + email +"&tipo=<? echo $tipo; ?>" );
	
        }

    }

    function cancelar(){
        if (confirm('Seguro que desea cancelar la Env\u00edo de la C\u00e1psula de Prueba?')==true){
            $('SiguienteForm').action='wizard_capsula_fin1.php';
            $('SiguienteForm').submit();
        }
    }
	

    function Salir(){
        CerrarWizard();
        parent.searchBoxPpal.close();
        //$('SiguienteForm').action='wizard_capsula_fin1.php';
        //$('SiguienteForm').submit();
    }	
	
	
</script>