
<?php

include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

$envioId = $_POST['envioId'];
$capsulaId = $_POST['capsulaId'];
$capsulaVersion = $_POST['capsulaVersion'];
$usuarioId = $_POST['usuarioId'];

$objReg = new Registro();

$objReg->EliminarRegistrosDuplicados($envioId, $capsulaId, $capsulaVersion, $usuarioId);

?>
