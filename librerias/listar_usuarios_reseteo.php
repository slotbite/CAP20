<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$envioId = $_POST['envioId'] ? $_POST['envioId']:0;

	if(($cliente_id!= 0)){
	
		$query 	= "EXEC envDatosEnvioUsuarios ".$cliente_id.",".$envioId." ";
			
				
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeUsuarios' class='tabla' cellspadding='0' cellspacing='0' border='0'><thead style='display:none'><tr><th></th><th class='sortable'>Usuario</th><th class='sortable'>Organizacion</th><th class='sortable'>Gerencia/Agencia</th><th class='sortable'>Area</th><th class='sortable'>Cargo</th></tr></thead><tbody>";
				
				while ($row = $base_datos->sql_fetch_assoc($result)) {
				 $org=$row['organizacionNombre'];
				 if($org==' '){
					$org="&nbsp;";
				 }else{
					$org=htmlentities(trim($org));
				 }
				 
				 $sec=$row['sectorNombre'];
				 if($sec==' '){
					$sec="&nbsp;";
				 }else{
					$sec=htmlentities(trim($sec));
				 }
				 
				 $area=$row['areaNombre'];
				 if($area==' '){
					$area="&nbsp;";
				 }else{
					$area=htmlentities(trim($area));
				 }
				 
					$lista.= "<tr><td><input type='checkbox'/></td><td style='width:204px;'>";
					$lista.=htmlentities(trim($row['nombre']))."<input type='hidden' id='usuarioId' value='".trim($row['usuarioId'])."'/></td><td style='width:95px;'>".$org."</td><td style='width:145px;'>".$sec."</td><td style='width:216px;'>".$area."</td>
					<td style='width:100px;'>".htmlentities(trim($row['cargoNombre']))."</td></tr>";
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


