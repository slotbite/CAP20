<?
session_start();
include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

$capsulaId		      = $_POST['capsulaIDimagen'] 	? $_POST['capsulaIDimagen']      : 0;
$capsulaVersion       = $_POST['versionIDimagen'] 	? $_POST['versionIDimagen'] 	 : 0;
$contenidoTitulo	  = $_POST['tituloImagen'] 	    ? $_POST['tituloImagen']         : '';
$contenidoURL 		  = 'archivos/img/';

ini_set('post_max_size','100M');
ini_set('upload_max_filesize','100M');
ini_set('max_execution_time','1000');
ini_set('max_input_time','1000');

$contenidoTitulo=mb_convert_encoding($contenidoTitulo, "ISO-8859-1", "UTF-8");

if (isset($_FILES['direccionImagen']['tmp_name'])) {
		 $uploaddir = "../archivos/img/";
		 $uploadfile = $uploaddir . basename(mb_convert_encoding($_FILES['direccionImagen']['name'], "ISO-8859-1", "UTF-8"));
		 $error = $_FILES['direccionImagen']['error'];
		 $subido = false;
		 if($error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['direccionImagen']['tmp_name'], $uploadfile); 
		  } 
		   if($subido) { 
				$contenidoURL ="archivos/img/".basename(mb_convert_encoding($_FILES['direccionImagen']['name'], "ISO-8859-1", "UTF-8"));
				$query 	= "EXEC capEditarContenidoMedia ".$contenidoId.",'".$contenidoURL."','".$contenidoTitulo."','".$nusuario."',2";
				$result = $base_datos->sql_query($query);
				echo"<script>window.parent.document.getElementById('formImagen').reset();</script>";
				echo"<script>window.parent.recargarListaContenido()</script>";
				
		   } else {
			//echo "Se ha producido un error: ".$error;
		  }

}else{
//no cambio el archivo...
		$contenidoURL='';
		$query 	= "EXEC capEditarContenidoMedia ".$contenidoId.",'".$contenidoURL."','".$contenidoTitulo."','".$nusuario."',2";
		$result = $base_datos->sql_query($query);
		echo"<script>window.parent.document.getElementById('formImagen').reset();</script>";
		echo"<script>window.parent.recargarListaContenido()</script>";

}
?>


