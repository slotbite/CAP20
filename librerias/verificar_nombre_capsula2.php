<?PHP

include ("../librerias/conexion.php");

$nombre_capsula = $_POST['nombre_capsula'] ? $_POST['nombre_capsula'] : '';
$cliente_id = $_POST['cliente_id'] ? $_POST['cliente_id'] : '';

$administrador_id = $_POST['administrador_id'] ? $_POST['administrador_id'] : '0';
$perfil_id = $_POST['perfil_id'] ? $_POST['perfil_id'] : '0';

$nombre_capsula = mb_convert_encoding($nombre_capsula, "ISO-8859-1", "UTF-8");


if (($_POST["nombre_capsula"] != "") && ($_POST["cliente_id"] != "")) {
    //$query = "EXEC capVerificarNombreCapsula '" . $nombre_capsula . "'," . $cliente_id . " ";
    
    
    $query = "  DECLARE @existe INT
                DECLARE @idCapsula INT
                SET @existe=0
                SET @idCapsula=0
	
                IF EXISTS( SELECT * FROM Capsulas WHERE CASE WHEN capsulaNumero is NULL or LTRIM(RTRIM(capsulaNumero)) = '' THEN capsulaNombre ELSE LTRIM(RTRIM(capsulaNumero)) + '.- ' + capsulaNombre END ='$nombre_capsula' AND clienteId=$cliente_id )
                BEGIN
                    SET @existe=1
	
                	SELECT @idCapsula=capsulaId	
                        FROM Capsulas
                        WHERE CASE WHEN capsulaNumero is NULL or LTRIM(RTRIM(capsulaNumero)) = '' THEN capsulaNombre ELSE LTRIM(RTRIM(capsulaNumero)) + '.- ' + capsulaNombre END = '$nombre_capsula'
                        AND clienteId=$cliente_id";
		      
    if($perfil_id == "2"){
        
        $query = $query + " and usuarioCreacion = '" . $administrador_id . "'";
        
    }
        
    $query = $query . " END
                        
                        Select @existe as ExisteNombre,@idCapsula AS idCapsula";
               
    $result = $base_datos->sql_query($query);
    $row = $base_datos->sql_fetch_assoc($result);
    //$errorIns = $row["ExisteNombre"] ? $row["ExisteNombre"] : 1;
    $CapsulaId = $row["idCapsula"] ? $row["idCapsula"] : 0;

    //echo $query;
    echo $CapsulaId;
}
?>