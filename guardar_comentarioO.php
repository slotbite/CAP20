<?php

include ("librerias/conexion.php");
include ("librerias/crypt.php");
session_start();

$answer = $_POST["json"];

$envio = $_SESSION["cap_envio"];
$capsulaId = $_SESSION["cap_capsulaid"];
$capsulaVersion = $_SESSION["cap_version"];
$usuarioId = $_SESSION["cap_usuarioId"];

$contenidoId = $answer[0]["contenidoId"][0];
$comentario = mb_convert_encoding(trim($answer[0]["comentario"][0]), "ISO-8859-1", "UTF-8");

$comentario = str_replace("\'", "'", $comentario);
$comentario = str_replace('\"', '"', $comentario);


$stmt = mssql_init("AcapGuardarComentarioUsuario");

mssql_bind($stmt, '@envioId', $envio, SQLVARCHAR);
mssql_bind($stmt, '@capsulaId', $capsulaId, SQLVARCHAR);
mssql_bind($stmt, '@capsulaVersion', $capsulaVersion, SQLVARCHAR);
mssql_bind($stmt, '@contenidoId', $contenidoId, SQLVARCHAR);
mssql_bind($stmt, '@usuarioId', $usuarioId, SQLVARCHAR);
mssql_bind($stmt, '@comentario', $comentario, SQLTEXT);

$base_datos->sql_ejecutar_sp($stmt);



?>