<?
session_start();
include("../default.php");
$plantilla->setPath('../skins/saam/plantillas/');

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$plantilla->setTemplate("header_3");
//
echo $plantilla->show();?>
<script type="text/javascript" type="text/css">
function siguiente(){
window.location='wizard_capsula_1.php';
}
function cancelar(){
if (confirm('Seguro que desea cancelar la creaci\u00f3n de la C\u00e1psula?')==true){
parent.searchBoxPpal.close();
}
}
</script>
<div>
<?
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{
//$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");
// $plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							// "FECHA"	=>	"$fecha"
							// ));


//echo $plantilla->show();

$plantilla->setTemplate("wizard_capsula_0");
echo $plantilla->show();

}
?>
</div>
<?
//$plantilla->setTemplate("footer_2");
//echo $plantilla->show();
?>