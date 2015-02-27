<?PHP 
include ("../librerias/conexion.php");

$value              = $_POST['value']           ? $_POST['value']           : '';
$nombre_id          = $_POST['nombre_id']       ? $_POST['nombre_id']       : '';
$nombre_campo       = $_POST['nombre_campo']    ? $_POST['nombre_campo']    : '';
$nombre_tabla       = $_POST['nombre_tabla']    ? $_POST['nombre_tabla']    : '';
$nombre_estado      = $_POST['nombre_estado']   ? $_POST['nombre_estado']   : '';
$clienteid          = $_POST['clienteId']       ? $_POST['clienteId']       : 0;
$administradorId    = $_POST['administradorId'] ? $_POST['administradorId'] : 0;
$perfilId           = $_POST['perfilId']        ? $_POST['perfilId'] : 0;


$value =stripslashes(mb_convert_encoding($value, "ISO-8859-1", "UTF-8"));

if ($nombre_tabla == ""){
	echo "<li>Error, No existe la tabla</li>";
}
else if ($nombre_campo == ""){
	echo "<li>Error, No existe el campo</li>";
}
else {
	
	
	if ($nombre_id == "")
		
		$query = "SELECT DISTINCT $nombre_campo FROM $nombre_tabla ";
	else
		$query = "SELECT DISTINCT $nombre_id, $nombre_campo FROM $nombre_tabla ";					
		$query .= "WHERE $nombre_estado = 1 AND $nombre_campo like '$value%' and clienteId = $clienteid";
                
                if($perfilId == "2"){
                    $query .= " and usuarioCreacion = '$administradorId'";
                }
	}
        
	$query .= " ORDER BY $nombre_campo ";
	
	//echo "esta es la query: ".$query;
	$result = $base_datos->sql_query($query);
	
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		if ($nombre_id != "")
			echo "<li id=\"".trim($row[$nombre_id])."\">".htmlentities(trim($row[$nombre_campo]))."</li>";
		else
			echo '<li>'.trim($row['cliente_nombre'])."</li>";
	}

?>