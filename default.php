<?php
/***************************************************************************
 *
 *	Nombre:	default.php
 *   	Este archivo, es comun para las páginas.
 * 	En el se encuentran aspectos de seguridad, y de configuracion del sitio
 *
 ***************************************************************************/

// Archivos comunes usados por cada página.
include ("librerias/Template.php");
include ("librerias/conexion.php");

//include("librerias/config_sqlserver2000.php");
//include ("librerias/config_sqlserv2000.php");

// Protege contra hackeos usando variables globales 
if (isset($HTTP_POST_VARS['GLOBALS']) || isset($HTTP_POST_FILES['GLOBALS']) || 
	isset($HTTP_GET_VARS['GLOBALS']) || isset($HTTP_COOKIE_VARS['GLOBALS'])) {
	die("Intento de hackeo");
}

// Protege contra hackeos usando variables de sesión
if (isset($HTTP_SESSION_VARS) && !is_array($HTTP_SESSION_VARS)) {
	die("Intento de Hackeo");
}

$client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : 
			 ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
//$user_ip = encode_ip($client_ip);

$aplicacion = "SRC";
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';


/*  Crear un archivo de configuracion de estilos */
$plantilla 			= new Template();

// Select a tabla de configuración.

?>