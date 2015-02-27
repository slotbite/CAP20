
<?php

include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

$fecha1 = $_POST['fecha_desde'];
$fecha2 = $_POST['fecha_hasta'];

echo $fecha1;
echo $fecha2;


$objReg = new Registro();


$objReg->EliminarRegistrosInactivosFechas($fecha_desde , $fecha_hasta);

?>

