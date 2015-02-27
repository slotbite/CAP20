<?PHP

include ("../librerias/conexion.php");

$nombre_tema        = $_POST['nombre_tema']         ? $_POST['nombre_tema']         : '';
$cliente_id         = $_POST['cliente_id']          ? $_POST['cliente_id']          : '';
$perfilId           = $_POST['perfil_id']           ? $_POST['perfil_id']           : '0';
$administradorId    = $_POST['administrador_id']    ? $_POST['administrador_id']    : '0';


$nombre_tema = mb_convert_encoding($nombre_tema, "ISO-8859-1", "UTF-8");

if (($_POST["nombre_tema"] != "") && ($_POST["cliente_id"] != "")) {

    $query = "  Select ISNULL(temaId, 0) as 'temaId'
                From Temas t (nolock)
                Where   temaNombre = '" . $nombre_tema . "'
                and     clienteId = " . $cliente_id . ""; 
    
    //$query = "EXEC capTraerTemaId '" . $nombre_tema . "'," . $cliente_id . " ";
    
    if($perfilId == "2"){
        $query = $query . " and usuarioCreacion = '" . $administradorId . "'";
    }           
    
    $result = $base_datos->sql_query($query);
    $row = $base_datos->sql_fetch_assoc($result);
    $errorIns = $row["temaId"] ? $row["temaId"] : 0;
    echo $errorIns;
}
?>