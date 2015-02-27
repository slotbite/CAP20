<?PHP 
session_start();
include ("../librerias/conexion.php");

$nombre_sect = $_POST['nombre_sect'] 	? $_POST['nombre_sect']: '';
$cliente_id	 = $_SESSION["clienteId"] ? $_SESSION["clienteId"] : 0;
$organizacionId= $_POST['organizacionId']? $_POST['organizacionId']: 0;

	if(($nombre_sect != "")&&($cliente_id != 0)){
	$query 	= "EXEC capVerificaSector '".$nombre_sect."',".$cliente_id.",".$organizacionId."";
	 echo $query;
				$result = $base_datos->sql_query($query);
				$row	= $base_datos->sql_fetch_assoc($result);
				$sectorId = $row["sectorId"] ? $row["sectorId"] : 0;
				
				echo $sectorId;
	}else{
		echo "0";
	}
	
?>