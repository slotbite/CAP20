<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$evaluacionId = $_POST['evaluacionId'] ? $_POST['evaluacionId']:'';



	if(($cliente_id!= 0)){
	
	$query 	= "EXEC envListarUsuarioEvaluacion '".$evaluacionId."',".$cliente_id." ";
			
				
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeUsuarios' class='tbtexto' cellspadding='0' cellspacing='0'><thead style='display:none'><tr><th></th><th class='sortable'>Usuario</th><th class='sortable'>E-Mail</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th></tr></thead><tbody>";
						
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$lista.= "<tr><td><input type='checkbox' checked disabled/></td><td style='width:185px;'>";
					$lista.=trim(htmlentities($row['nombre']))."<input type='hidden' id='usuarioId' value='".trim($row['usuarioId'])."'/></td><td style='width:150px;'>".trim($row['usuarioEmail'])."</td><td style='width:95px;'>".htmlentities(trim($row['organizacionNombre']))."</td><td style='width:145px;'>".htmlentities(trim($row['sectorNombre']))."</td><td style='width:216px;'>".htmlentities(trim($row['areaNombre']))."</td>
					<td style='width:100px;'>".htmlentities(trim($row['cargoNombre']))."</td></tr>";
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


