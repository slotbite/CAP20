<?PHP
session_start();
include ("../librerias/conexion.php");
require('clases/informes.class.php');
$objInforme = new informes();

$clienteId = $_SESSION['clienteId'];

if ($clienteId == '') {
    echo "<script>window.location='../index.php';</script>";
}

$sectorNombre = mb_convert_encoding(trim($_POST['nombre']), "ISO-8859-1", "UTF-8");

$consulta = $objInforme->validarSectoresInforme($sectorNombre);

if ($consulta) {
$fila = mssql_fetch_array($consulta);
$sectorId=$fila['sectorId'];
}
echo $sectorId;
?>