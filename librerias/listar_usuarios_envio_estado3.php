<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$envioId = $_POST['envioId'] ? $_POST['envioId']:0;
$estado = $_POST['estado'] ? $_POST['estado']:0;
$capsula_id     = $_SESSION['capsulaId2'] ? $_SESSION['capsulaId2']:0;
$capsulaVersion = $_SESSION['capsulaVersion2']? $_SESSION['capsulaVersion2']:0;




	if(($cliente_id!= 0)){
	
	$query 	= "EXEC envListarResultadoEnvio ".$envioId.",".$estado.",".$capsula_id.",".$capsulaVersion." ";
			
				
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeUsuarios2' class='tabla' cellspadding='0' cellspacing='0' border='0'><thead style='display:none'><tr><th class='sortable'>Usuario</th><th class='sortable'>E-Mail</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";
		
				
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$lista.= "<tr><td style='width:185px;'>";
					$lista.=htmlentities(trim($row['nombre']))."<input type='hidden' id='usuarioId' value='".trim($row['usuarioId'])."'/></td><td style='width:150px;'>".trim($row['usuarioEmail'])."</td><td style='width:95px;'>".htmlentities(trim($row['organizacionNombre']))."</td><td style='width:145px;'>".htmlentities(trim($row['sectorNombre']))."</td><td style='width:216px;'>".htmlentities(trim($row['areaNombre']))."</td>
					<td style='width:100px;'>".htmlentities(trim($row['cargoNombre']))."</td></tr>";
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
	
	unset ($_SESSION['capsulaId2']);
	unset ($_SESSION['capsulaVersion2']);
?>


