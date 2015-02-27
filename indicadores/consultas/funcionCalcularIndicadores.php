<?php

include ("../../librerias/conexion.php");
require('../clases/registro.class.php');

session_start();

$yyyy = $_POST['yyyy'];
$mm = $_POST['mm'];

$objReg = new Registro();

$indicadores = $objReg->recalcularIndicadoresMensual($yyyy, $mm, $personalId, $usuarioNombre);


