<?PHP

ini_set('mssql.charset', 'UTF-8');

/* **************************************************************************
 *																			*
 * Archivo de configuracion: Datos Host o servidor y base de datos 			*
 *																			*
 * **************************************************************************/

$bd = 'sqlserver2000'; // Base de datos

$server = '192.168.74.99\brainsdev'; // Direcci�n del servidor
$dbase = 'CAP20_PRO';   // Nombre de la base de datos
$user = 'sa';    // Nombre del Usuario (User) de la base de datos
$pass = 'bd02.ti'; // Contrase�a (Password) del usuario de la base de datos
// Selecciona la base de datos a utilizar por el sistema
switch ($bd) {
    case 'sqlserver2000':
        include("sqlserver2000.php");
        break;
}

$base_datos = new sql_db($server, $dbase, $user, $pass);

if (!$base_datos->conexion_id) {
    die("No se puede conectar con la base de datos.");
}
?>