<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$temaId = $_POST['temaId'] ? $_POST['temaId']:0;
$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId']:0;




	if(($cliente_id!= 0)){
	
	$query 	= "EXEC capListarCapsulas ".$temaId.",".$capsulaId.",".$cliente_id."";
					
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeCapsulas' class='tbtexto' cellspadding='0' cellspacing='0' style='width:850px;'><thead style='display:none'><tr><th class='sortable'>Tema</th><th class='sortable'>Capsula</th><th class='sortable'>Tipo</th><th class='sortable'>Estado</th><th></th></tr></thead><tbody>";
						
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$enviado=$row['enviado'];
					$planificado=$row['planificado'];
					$lista.= "<tr><td style='width:250px;'>".trim(htmlentities($row['temaNombre']))."<input type='hidden' id='temaId' value='".trim($row['temaId'])."'/></td><td style='width:300px;'>";
					$lista.=trim(htmlentities($row['capsulaNombre']))."<input type='hidden' id='capsulaId' value='".trim($row['capsulaId'])."'/></td><td style='width:90px;'>".trim($row['capsulaTipo'])."</td><td style='width:100px;'>".trim($row['capsulaEstado'])."</td>";
					$lista.="<td>";
					$lista.="<A href='#' onclick='verCap(".trim($row['capsulaId']).",".trim($row['capsulaVersion']).",".$enviado.",".$planificado.")' style='font-size:9px;'>Ver</a>&nbsp;&nbsp;";
					$lista.="<A href='#' onclick='EditarCapsula(".trim($row['temaId']).",".trim($row['capsulaId']).",".trim($row['capsulaVersion']).",".$enviado.",".$planificado.")' style='font-size:9px;'>Editar</a>";
					$lista.="<input type='hidden' id='enviado".trim($row['capsulaId'])."' value='$enviado'/>";
					$lista.="<input type='hidden' id='planificado".trim($row['capsulaId'])."' value='$planificado'/>";
					if(trim($row['capsulaEstado'])!="Borrador" ){
						
						
						if(trim($row['capsulaEstado'])=="Vigente"){
							if( $planificado!=1){
								$lista.="&nbsp;&nbsp;<A href='#' onclick='CambiarEstadoCapsula(".trim($row['temaId']).",".trim($row['capsulaId']).",".trim($row['capsulaVersion']).",2)' style='font-size:9px;'>Anular";
							}
						}else{
							$lista.="&nbsp;&nbsp;<A href='#' onclick='CambiarEstadoCapsula(".trim($row['temaId']).",".trim($row['capsulaId']).",".trim($row['capsulaVersion']).",1)' style='font-size:9px;'>Activar";
						}
						
					
						$lista.="</a>";
						
					}else{
						$lista.="&nbsp;";
					}
					//$lista.="<a href='../librerias/vprevia_Capsula.php?capsulaId=".trim($row['capsulaId'])."&version=".trim($row['capsulaVersion'])."'>Ver</a>"
					$lista.="</td></tr>";
					
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


