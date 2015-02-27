<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$temaId = $_POST['temaId'] ? $_POST['temaId']:0;
$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId']:0;




	if(($cliente_id!= 0)){
	
	$query 	= "EXEC capListarTemas ".$temaId.",".$cliente_id."";
					
				$result = $base_datos->sql_query($query);
				//
				$lista="<table id='ListadeCapsulas' class='tabla' cellspadding='0' cellspacing='0' style='width:auto;margin:0 auto;'><thead style='display:'><tr><th class='sortable'>Tema</th><th class='sortable'>Descripci&oacute;n</th><th class='sortable'>F.Creaci&oacute;n</th><th class='sortable'>F.Edici&oacute;n</th><th class='sortable'>Estado</th><th></th><th></th><th></th></tr></thead><tbody>";
						
				while ($row = $base_datos->sql_fetch_assoc($result)) {

					$lista.= "<tr><td style='width:350px;'>".trim(htmlentities($row['temaNombre']))."<input type='hidden' id='temaId' value='".trim($row['temaId'])."'/>";
                                        $lista.="</td><td style='width:480px;'>";
					$lista.=trim(htmlentities($row['temaDescripcion']))."</td>";
                                        $lista.="<td style='width:80px;'>".$row['fechaCreacion']."</td><td style='width:80px;'>".$row['fechaModificacion']."</td>";
                                        $lista.="<td style='width:50px;'>".trim($row['temaEstado'])."</td>";
					$lista.="<td style='width:30px;'><a rel='lightbox[url 1150 500]' href='verTema.php?tema=" . trim($row['temaId'])."' target='_blank'>Ver</a></td>";
					$lista.="<td style='width:40px;'><A href='#' onclick='EditarTema(".trim($row['temaId']).")'>Editar</a></td>";
                                        $lista.="<td style='width:40px;'><A href='#' onclick='AnularTema(".trim($row['temaId']).")'>Anular</a></td>";
						
//						if(trim($row['temaEstado'])=="Vigente"){
//							$lista.="&nbsp;&nbsp;<A href='#' onclick='CambiarEstadoTema(".trim($row['temaId']).",2)' style='font-size:9px;'>Anular";
//						}else{
//							$lista.="&nbsp;&nbsp;<A href='#' onclick='CambiarEstadoTema(".trim($row['temaId']).",1)' style='font-size:9px;'>Activar";
//						}
//						
//					
//						$lista.="</a>";
						
					
					//$lista.="<a href='../librerias/vprevia_Capsula.php?capsulaId=".trim($row['capsulaId'])."&version=".trim($row['capsulaVersion'])."'>Ver</a>"
					$lista.="</tr>";
					
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


