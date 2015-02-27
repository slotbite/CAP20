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

<div>
<?
$usuario_id =$_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{
$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");

	if($_SESSION['perfilId']==1){
		$menu1="display:block;";
	}else{
		$menu1="display:none;";
	}
	
$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"FECHA"	=>	"$fecha",
							"MANT"=>"$menu1"
							));


echo $plantilla->show();

$plantilla->setTemplate("admMantenedores");
echo $plantilla->show();

}
?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
<script>
function Volver(){
	window.location = '../index.php'
}
</script>