<?
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaIDvideo'] 	? $_POST['capsulaIDvideo']      : 0;
$capsulaVersion       = $_POST['versionIDvideo'] 	? $_POST['versionIDvideo'] 	 : 0;
$contenidoTitulo	  = $_POST['tituloVideo'] 	    ? $_POST['tituloVideo']         : '';
$contenidoURL 		  = 'archivos/video/';

ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');
ini_set('max_execution_time','1000');
ini_set('max_input_time','1000');

$contenidoTitulo=mb_convert_encoding($contenidoTitulo, "ISO-8859-1", "UTF-8");

if (isset($_FILES['direccionVideo']['tmp_name'])) {
		 $video=$_FILES['direccionVideo']['tmp_name'];
		 
		 // $query = "EXEC capObtieneNumeroVideo ".$capsulaId.",".$capsulaVersion." ";
		 // $result = $base_datos->sql_query($query);
		 // $row	= $base_datos->sql_fetch_assoc($result);
		 // $numero = $row["numero"] ? $row["numero"] : 1;
				
		 
		 
		 //$to="video_".$numero."cap".$capsulaId	."ver".$capsulaVersion;
		 $nombrevideo=basename(mb_convert_encoding($_FILES['direccionVideo']['name'], "ISO-8859-1", "UTF-8"));
		 
		 $arreglo1=explode(".",$nombrevideo);
		 $nombrevideo=$arreglo1[0].".flv";
		 
		 $to=$nombrevideo;
		 //echo $to;
		 

		$uploaddir = "../multimedia/";
		$anio=date("Y");
		$uploaddir =$uploaddir.$anio."/";
		
		if (file_exists($uploaddir)==false) {
			mkdir($uploaddir, 0700);
		}
		$nombrecarpeta="CAP_".$capsulaId."_".$capsulaVersion ."";
		
		$uploaddir =$uploaddir.$nombrecarpeta."/";
		
		if (file_exists($uploaddir)==false) {
			mkdir($uploaddir, 0700);
		}
		

		 $uploadfile = $uploaddir .$to.".flv";
		 $error = $_FILES['direccionVideo']['error'];
		 $subido = false;
		 if($error==UPLOAD_ERR_OK) { 
		 //REVISAR QUE CUANDO EL NOMBRE ES MUY RARO LA CONVERSION FALLA Y SE QUEDA PEGADO EN ESTA PARTE:
				echo "ffmpeg -i ".$video." -ar 11025 -ab 16 -b 100k -f flv -s 320x240 ".$to.".flv";
		   		   $imagenes=exec("ffmpeg -i ".$video." -ar 11025 -ab 16 -b 100k -f flv -s 320x240 ".$to.".flv",$output);
		           $subido = copy($to.".flv", $uploadfile); 
				   
		  } 
		  

		   if($subido) { 
				unlink($to.".flv");
				$Carpeta="multimedia/".$anio."/".$nombrecarpeta."/";
				//$contenidoURL ="archivos/video/".$to.".flv";
				$contenidoURL =$Carpeta.$to.".flv";
				$query 	= "EXEC capInsertarContenidoMedia ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",'".$contenidoTitulo."','".$contenidoURL."','".$nusuario."',3";
				$result = $base_datos->sql_query($query);
				echo"<script>window.parent.document.getElementById('formVideo').reset();</script>";
				echo"<script>window.parent.recargarListaContenido();window.parent.recargarVistaPrevia();</script>";
				
		   } else {
			echo "<script>alert('Se ha producido un error: " . $error . "')</script>";
		  }

}
?>


