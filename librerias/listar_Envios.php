<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$envioId= $_POST['envioId'] ? $_POST['envioId']:0;
$temaId = $_POST['temaId'] ? $_POST['temaId']:0;
$capsulaId = $_POST['capsulaId'] ? $_POST['capsulaId']:0;
$inicio= $_POST['inicio'] ? $_POST['inicio']:'';
$fin= $_POST['fin'] ? $_POST['fin']:'';
$inicial=$_POST['inicial'] ? $_POST['inicial']:1;



	if(($cliente_id!= 0)){
	
	$query 	= "EXEC envListaEnvios ".$envioId.",".$temaId.",".$capsulaId.",'".$inicio."','".$fin."',".$inicial.",".$cliente_id."";
    //echo $query ;				
				$result = $base_datos->sql_query($query);
				
				$lista="<table id='ListadeEnvios' class='tabla' cellspadding='0' cellspacing='0' style='width:854px;'><thead style='display:none'><tr><th class='sortable'>Nenvio</th><th class='sortable'>Tema</th><th class='sortable'>Capsula</th><th class='sortable'>F.Envio</th><th class='sortable'>F.Cierre</th><th></th></tr></thead><tbody>";
						
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$tipo=$row['capsulaTipo'];
				
					$lista.= "<tr><td>".$row['envioId']."</td><td style='width:250px;'>".trim(htmlentities($row['temaNombre']))."<input type='hidden' id='temaId' value='".trim($row['temaId'])."'/></td><td style='width:310px;'>";
					$lista.=trim(htmlentities($row['capsulaNombre']))."<input type='hidden' id='capsulaId' value='".trim($row['capsulaId'])."'/></td><td style='width:90px;'>".trim($row['fechaEnvio'])."</td><td style='width:90px;'>".trim($row['fechaCierre'])."</td>";
					$lista.="<td style='width:110px;'><A href='#' onclick='VerLog(".trim($row['envioId']).",".trim($row['capsulaId']).",".trim($row['capsulaVersion']).")' style='font-size:9px;'>Ver Log</a>";
					
					if($tipo==2)
					{
						$lista.="&nbsp; <a href='#' style='font-size:9px;' onclick='Resetear(".trim($row['envioId']).")'>Resetear</a>";
					}
					
					$lista.="</td></tr>";
					
				}
				$lista.="</tbody></table>";
				echo $lista;
	}
?>


