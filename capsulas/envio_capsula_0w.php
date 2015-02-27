<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario       = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$plantilla->setTemplate("header_3");
//
echo $plantilla->show();


$queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsula_id.",".$capsulaVersion." ";
$result = $base_datos->sql_query($queryTipo);
$row	= $base_datos->sql_fetch_assoc($result);
$nombre = $row["capsulaNombre"];
$tipo = $row["capsulaTipo"];


$queryP 	= "EXEC capListarPersonalizacion ".$clienteId." ";
//echo $queryP;
$resultP = $base_datos->sql_query($queryP);
$rowP	= $base_datos->sql_fetch_assoc($resultP);
$personalizacionId=$rowP['personalizacionId'] ? $rowP['personalizacionId']:0;
$subjectPP=$rowP['subject'] ? $rowP['subject']:'';
$encabezado=$rowP['encabezado'] ? $rowP['encabezado']:'';
$footerT=$rowP['footer'] ? $rowP['footer']:'';

$subjectPP=htmlentities(stripslashes($subjectPP));
$encabezado=htmlentities(stripslashes($encabezado));
$footerT=htmlentities(stripslashes($footerT));

?>

<div>
<?
$Cliente_id = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{

// $plantilla->setTemplate("menu2");
// $fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO" =>"$usuario_id",
							// "FECHA"	  =>	"$fecha"
							// ));


// echo $plantilla->show();

$plantilla->setTemplate("envio_capsula_0w");
$plantilla->setVars(array(	"USUARIO"		=> "$usuario_id",
							"CLIENTE"	    => "$Cliente_id",
							"NOMBRE"        => "$nombre",
							"CAPSULA_ID"    => "$capsula_id",
							"CAPSULA_VER"   => "$capsulaVersion",
							"TIPO"          => "$tipo",
							"SUBJECT"		=>"$subjectPP",
							"ENCABEZADO"	=>"$encabezado",
							"FOOTERT"		=>"$footerT",
							"PERSONALIZACIONID"=>"$personalizacionId"
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
	$('ErrorSubject').set('html','');
	$('ErrorEncabezado').set('html','');
	$('ErrorFooter').set('html','');
		
	var subject11=$('subject').get('value');
	var encabezado=$('encabezado').get('value');
	var footer=$('footerT').get('value');
	
	
		
	if(subject11==''){
		$('ErrorSubject').set('html', "<span style='color:red'><b>No ha agregado el Subject del Correo</b></span>");
	}
	if(encabezado==''){
	  $('ErrorEncabezado').set('html', "<span style='color:red'><b>No ha agregado el Texto del Correo</b></span>");
	}
	if(footer==''){
	  $('ErrorFooter').set('html', "<span style='color:red'><b>No ha agregado el Footer del Correo</b></span>");
	}
			
	if(subject11!=''&&encabezado!=''&&footer!=''){	
	//REQUEST QUE GUARDA LA INFO DE PERSONALIZACION
	
	personalizacionID=$('personalizacionID').value;
	if(personalizacionID=='0'){
		UrlRequest='../librerias/insertar_personalizacion_capsula.php';
	}else{
		UrlRequest='../librerias/actualizar_personalizacion_capsula.php';
	}
	
		var elRequest = new Request({
						url		: UrlRequest, 
						method  :'post',
						onSuccess: function(html) {
							//VA AL SIGUIENTE FORMULARIO!!!
							$('SiguienteForm').submit();
						},
						//Si Falla
						onFailure: function() {
							$('ErrorFooter').set('html', 'Error de conexi&oacute;n');
						}
					});
					elRequest.send(	
									"&subject="           + subject11 +
									"&encabezado="        + encabezado +
									"&footer=" 	          + footer +
									"&personalizacionID=" +personalizacionID
									);
	
	}

}

function cancelar(){
		if (confirm('Seguro que desea cancelar la Envio de la C\u00e1psula?')==true){
			$('SiguienteForm').action='wizard_capsula_fin1.php';
			$('SiguienteForm').submit();
		}
	}
</script>