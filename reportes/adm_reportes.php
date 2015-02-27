<?
session_start();

include("../default.php");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();
?>

<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<div>
<?
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{
$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"FECHA"	=>	"$fecha"
							));


echo $plantilla->show();

$plantilla->setTemplate("adm_reportes");
echo $plantilla->show();

}
?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>

<script>
function Envio(){
	$('redir').submit();
}
</script>