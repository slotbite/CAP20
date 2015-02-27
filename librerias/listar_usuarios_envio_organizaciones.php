<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$orgNombre = $_POST['orgNombre'] ? $_POST['orgNombre']:'';
$secNombre = $_POST['secNombre'] ? $_POST['secNombre']:'';
$areaNombre = $_POST['areaNombre'] ? $_POST['areaNombre']:'';

$orgNombre=mb_convert_encoding($orgNombre, "ISO-8859-1", "UTF-8");
$secNombre =mb_convert_encoding($secNombre, "ISO-8859-1", "UTF-8");
$areaNombre=mb_convert_encoding($areaNombre, "ISO-8859-1", "UTF-8");


	if(($cliente_id!= 0)){
	
	$query 	= "EXEC envListarUsuarioOrganizacion '".$orgNombre."','".$secNombre."','".$areaNombre."',".$cliente_id." ";
			//ECHO $query;
				
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeUsuarios' class='tbtexto' cellspadding='0' cellspacing='0' border='0'><thead style='display:none'><tr><th></th><th class='sortable'>Usuario</th><th class='sortable'>E-Mail</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";
						
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$lista.= "<tr><td><input type='checkbox'/></td><td style='width:185px;'>";
					$lista.=trim(htmlentities($row['nombre']))."<input type='hidden' id='usuarioId' value='".trim($row['usuarioId'])."'/></td><td style='width:150px;'>".trim($row['usuarioEmail'])."</td><td style='width:95px;'>".htmlentities(trim($row['organizacionNombre']))."</td><td style='width:145px;'>".htmlentities(trim($row['sectorNombre']))."</td><td style='width:216px;'>".htmlentities(trim($row['areaNombre']))."</td>
					<td style='width:100px;'>".htmlentities(trim($row['cargoNombre']))."</td></tr>";
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


