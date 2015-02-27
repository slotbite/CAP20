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
$personal_id=trim($_POST['personal_id']);

$objInforme->guardarSectoresInforme($sector_id,$personal_id,$administradorId);

?>