<?PHP 
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaId'] 		? $_POST['capsulaId'] 	                 : 0;
$capsulaVersion       = $_POST['capsulaVersion'] 	? $_POST['capsulaVersion'] 	             : 0;



	if(($_POST['capsulaId'] != 0)&&($_POST['capsulaVersion']!= 0)){
	
	$query 	= "EXEC capListarContenidoCapsula ".$cliente_id.",".$capsulaId.",".$capsulaVersion." ";
			
				
				$result = $base_datos->sql_query($query);
			
				while ($row = $base_datos->sql_fetch_assoc($result)) {
					$lista= '<li><input type="hidden" id="contenidoId" value="'.trim($row['contenidoId']).'"/><input type="checkbox"/>';
					$lista.='<img src="';
					
					if($row['contenidoTipo']==1){
						$lista.='../images/text.png';
					}
					
					if($row['contenidoTipo']==2){
						$lista.='../images/image.png';
					}
					
					if($row['contenidoTipo']==3){
						$lista.='../images/video.png';
					}
					
					if($row['contenidoTipo']==4){
						$lista.='../images/music.png';
					}
					
					$lista.='" border="0" />';
					$lista.=htmlentities(trim($row['contenidoTitulo']))."&nbsp;&nbsp;&nbsp;&nbsp;";
					
					if($row['contenidoTipo']==1){
						$lista.="<a href='#' onclick='EditarContenido(".trim($row['contenidoId']).",".$row['contenidoTipo'].")' style='font-size:9px;'>Editar</a>";
					}
					
					$lista.="</li>";
					echo $lista;
				}
	}
?>


