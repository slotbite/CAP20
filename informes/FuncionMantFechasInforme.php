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

$idFila = trim($_POST['idFila']);
$mesFila=trim($_POST['mesFila']);
$fechaFila=trim($_POST['fechaFila']);


$objInforme->guardarFechasInforme($idFila,$mesFila,$fechaFila,$administradorId);

?>