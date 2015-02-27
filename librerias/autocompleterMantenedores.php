<?PHP 
include ("../librerias/conexion.php");

$value 		= mb_convert_encoding($_POST['value'], "ISO-8859-1", "UTF-8")           ? mb_convert_encoding($_POST['value'], "ISO-8859-1", "UTF-8")		: '';
$nombre_id	= mb_convert_encoding($_POST['nombre_id'], "ISO-8859-1", "UTF-8") 	? mb_convert_encoding($_POST['nombre_id'], "ISO-8859-1", "UTF-8") 	: '';
$nombre_campo 	= mb_convert_encoding($_POST['nombre_campo'], "ISO-8859-1", "UTF-8") 	? mb_convert_encoding($_POST['nombre_campo'], "ISO-8859-1", "UTF-8") 	: '';
$nombre_tablas 	= mb_convert_encoding($_POST['nombre_tablas'], "ISO-8859-1", "UTF-8") 	? mb_convert_encoding($_POST['nombre_tablas'], "ISO-8859-1", "UTF-8")	: '';
$nombre_where   = mb_convert_encoding($_POST['nombre_where'], "ISO-8859-1", "UTF-8")    ? mb_convert_encoding($_POST['nombre_where'], "ISO-8859-1", "UTF-8")    : '';

$value = str_replace("\'", "''", $value);
$value = str_replace('\"', '"', $value);

$nombre_where = str_replace("\'", "'", $nombre_where);
$nombre_where = str_replace('\"', '"', $nombre_where);


if ($nombre_tablas == ""){
	echo "<li>Error, No existe la tabla</li>";
}
else if ($nombre_campo == ""){
	echo "<li>Error, No existe el campo</li>";
}
else {
	if ($nombre_id == "")
		$query = "SELECT DISTINCT $nombre_campo FROM $nombre_tabla ";
	else
		$query = "SELECT DISTINCT $nombre_campo $nombre_tablas ";					
		$query .= "WHERE $nombre_campo like '$value%' $nombre_where ";
	}
	$query .= "ORDER BY $nombre_campo ";
	
	//echo "esta es la query: ".$query;
	$result = $base_datos->sql_query($query);
	
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		if ($nombre_id != "")
			echo "<li id=\"" . htmlentities($row[$nombre_campo]) . "\">". htmlentities($row[$nombre_campo]) . "</li>";
		else
			echo "<li>" . htmlentities($row[$nombre_campo]) . "</li>";
	}
?>