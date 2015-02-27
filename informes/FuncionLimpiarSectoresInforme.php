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

$sector_id = trim($_POST['sector_id']);

$objInforme->limpiarSectoresInforme($sector_id,$administradorId);

?>