<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario       = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;
$cliente_id		= $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
// echo ":";
// echo $capsula_id;

$plantilla->setTemplate("header_3");
//
echo $plantilla->show();


$queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsula_id.",".$capsulaVersion." ";
$result = $base_datos->sql_query($queryTipo);
$row	= $base_datos->sql_fetch_assoc($result);
$nombre = mb_convert_encoding($row["capsulaNombre"], "UTF-8","ISO-8859-1");
$tipo = $row["capsulaTipo"];
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

$plantilla->setTemplate("wizard_capsula_finEd");
$plantilla->setVars(array(	"USUARIO"		=> "$usuario_id",
							"CLIENTE"	    => "$Cliente_id",
							"NOMBRE"        => "$nombre",
							"CAPSULA_ID"     => "$capsula_id",
							"CAPSULA_VER"       => "$capsulaVersion",
							"TIPO"          => "$tipo"
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
function EnvioCapsula(){
$('EnvioCapsula').submit();
}

function EnvioCapsulaPrueba(){
$('EnvioCapsula').action='EnvioCapsulaPrueba1.php'
$('EnvioCapsula').submit();
}

function Nueva(){
CerrarWizard();
window.location="wizard_capsula_1.php"
}


</script>