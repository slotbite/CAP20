<?PHP
session_start();
include ("../librerias/conexion.php");
require('clases/informes.class.php');
$objInforme = new informes();

$clienteId = $_SESSION['clienteId'];
$administradorId=$_SESSION['administradorId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$lista = trim($_POST['lista']);
$lista=substr($lista,0,strlen($lista)-1);

//echo $lista;

$objInforme->LimpiarFechasInforme($administradorId,$lista)

?>