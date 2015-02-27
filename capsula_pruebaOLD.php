<?
include ("librerias/conexion.php");
include ("librerias/crypt.php");

$encrypted= urldecode($_GET['hash']);
$iv="brains12";

/*$i4 = decryptData("blowfish",
                  "cfb",
                  "ThisIsDemo",
                  $iv,
*/
$i4=enc_dec($encrypted); 

//echo($i4);
$arreglo1=explode("&",$i4);
if(count($arreglo1)==4){
	$capsulaid1=explode("=",$arreglo1[0]);
	$capsulaid=$capsulaid1[1];

	$version1=explode("=",$arreglo1[1]);
	$version=$version1[1];
	
	//aca el usuario es el mail, y no viene en el hash!!!
	//$usuario1=explode("=",$arreglo1[2]);
	//$usuario=$usuario1[1];
	
	$envio1=explode("=",$arreglo1[2]);
	$envio=$envio1[1];

	//aca agregue el tipo al hash, para saber en que tabla esta la capsula:
	$tipo1=explode("=",$arreglo1[3]);
	$tipo=$tipo1[1];
	
	
	$logo="images/EnvioPrueba.jpg";

	
	$nombre="NOMBRE DEL USUARIO";
	
	$queryTipo 	= "EXEC capVerCapsulaPrueba ".$envio.",".$capsulaid.",".$version.",".$tipo."";
	$result = $base_datos->sql_query($queryTipo);
	$row	= $base_datos->sql_fetch_assoc($result);
	$Capnombre = $row["capsulaNombre"];
        $encabezado=$row["temaImagen"];
	//$comentario = $row["capsulaComentario"];
	$comentario=1;
	$clienteId=$row["clienteId"];
	//echo $queryTipo;
	
	//echo $Capnombre;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1980 05:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="expires" content="0">
<title>C&aacute;psulas de Conocimiento</title>
 
 <link rel="stylesheet" type="text/css" href="skins/saam/plantillas/style.css" media="screen" />
 <script type="text/javascript" src="scripts/jwplayer.js"></script> 
 <script type="text/javascript" src="scripts/mootools-1.2.5-core.js"></script>
<script type="text/javascript" src="scripts/Observer.js"></script>
<script type="text/javascript" src="scripts/Autocompleter.js"></script>
<script type="text/javascript" src="scripts/Autocompleter.Request.js"></script>
<script type="text/javascript" src="scripts/Funciones.js"></script>
<link rel="stylesheet" type="text/css" href="skins/saam/Autocompleter.css" />
</script>
<style>
ul {
	list-style-type: disc;
	list-style-position:inside;
}
ul li
{
	list-style-type: disc;
	list-style-position:inside;
}
p{font-size:14px;}
</style>
<table border="0" width="100%">
<tr>
<td width="15%" style="background-color:#BFD1E5"></td>
<td>

               <table border="0" style='border:1px solid #4B6C9F;margin:0px;border-collapse:collapse;' CELLSPACING='0' CELLSPADDING='0'>
			<tr>

			<? if(trim($encabezado)==""){?>
                            <td style='background-color:#4B6C9F;color:white;vertical-align:middle;width:700px;border-bottom:1px solid #4B6C9F;text-align:center;height:75px;' height='75px'>
                            <span style='font-weight:bolder;font-size:16px;'>C&Aacute;PSULAS DE CONOCIMIENTO</span>
                            </td>
                        <? }else{ ?>
			    <td style="width:700px;height:75px;border:1px solid #4B6C9F;"><img src="<? echo '/CAP20'.$encabezado; ?>" style="width:700px;"/></td>
                        <? } ?>    
                            
				<td style='width:250px;height:75px;border:1px solid #4B6C9F;' align='center'>
					<img src='<? echo $logo; ?>' border='0' height='90%'>
				</td>
			</tr>			
			</table>
<?
	  
?>
<br/>
<br/>
<table border="0" width="100%">
	<tr>
		<td align="center">
		<div style="text-align:justify;width:760px;word-wrap: break-word">
		<h3>Bienvenido(a)</h3>
		<b><? echo $nombre; ?></b>
		<input type='hidden' id='envioId' name='envioId' value='<? echo $envio;?>'/>
		<input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid;?>'/>
		<input type='hidden' id='versionId' name='versionId' value='<? echo $version;?>'/>
		<input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuario;?>'/>
		<br/>
		<br/>
		</div>
		</td>
	</tr>
	<tr>
		<td>
                    <? if($tipo!=3){?>
			<h1 class="nombreCapsula"><? echo htmlentities(stripslashes($Capnombre));?></h1>
			<hr>
                    <? } ?>
		</td>
	</tr>
	<tr>
		<td align="center">
				  
			<?
			$query3 	= "EXEC capListarContenidoCapsula ".$clienteId.",".$capsulaid.",".$version."" ;
			//echo $query3;	
			$result3 = $base_datos->sql_query($query3);
			//echo $query3;	
			while ($row3 = $base_datos->sql_fetch_assoc($result3)) {
			echo "<br/>";
			switch($row3['contenidoTipo']){
			case 1:
			$descripcion=$row3['contenidoDescripcion'];
			$descripcion=mb_convert_encoding($descripcion, "UTF-8","ISO-8859-1");
			?>
				<h3><?echo stripslashes(htmlentities($row3['contenidoTitulo']));?></h3>
				<div style="text-align:justify;width:760px;word-wrap: break-word;"><? echo stripslashes($descripcion);?></div>
			<?
			break;
			
			case 2:
			?>
			<div align="center"><img src='<? echo htmlentities($row3['contenidoUrl']);?>' alt='<? echo $row3['contenidoTitulo'];?>' title='<? echo htmlentities($row3['contenidoTitulo']);?>' style='border:1px solid #135293;'/></div>
			<?
			break;
			case 3:
			?>
			<h3><?echo htmlentities($row3['contenidoTitulo']);?></h3>
			<div align='center'>
				<div id='mediaspace<? echo $row3['contenidoId']?>'>This text will be replaced</div>
			</div>
			<? 
			$direccion=mb_convert_encoding($row3['contenidoUrl'], "UTF-8","ISO-8859-1");
			$arreglo1=explode("/",$direccion);
			$archivo=$arreglo1[3];
			
			$direccion_final=$arreglo1[0]."/".$arreglo1[1]."/".$arreglo1[2]."/";
			$url="http://".$_SERVER['HTTP_HOST'].'/CAP20/'.$direccion_final;
			$urlfinal= $url.rawurlencode($archivo);
			
			?>
			<script type='text/javascript'>
			  jwplayer('mediaspace<? echo $row3['contenidoId']?>').setup({
				'flashplayer': 'scripts/player.swf',
				'file': '<? echo $urlfinal;?>',
				'controlbar': 'bottom',
				'width': '470',
				'height': '320'
			  });
			</script>
			
			<?
			break;
			case 4:
			?>
			<h3><?echo htmlentities($row3['contenidoTitulo']);?></h3>
			<div align='center'>
				<div id='mediaspace<? echo $row3['contenidoId']?>'>This text will be replaced</div>
			</div>
			<? 
			$direccion=mb_convert_encoding($row3['contenidoUrl'], "UTF-8","ISO-8859-1");
			$arreglo1=explode("/",$direccion);
			$archivo=$arreglo1[3];
			
			$direccion_final=$arreglo1[0]."/".$arreglo1[1]."/".$arreglo1[2]."/";
			$url="http://".$_SERVER['HTTP_HOST'].'/CAP20/'.$direccion_final;
			$urlfinal= $url.rawurlencode($archivo);
			
			?>
			<script type='text/javascript'>
			  jwplayer('mediaspace<? echo $row3['contenidoId']?>').setup({
				'flashplayer': 'scripts/player.swf',
				'file': '<? echo $urlfinal;?>',
				'controlbar': 'bottom',
				'width': '470',
				'height': '24'
			  });
			  
			</script>
			<?
			break;
			case 5:
			//parseo para que me devuelva el id del video
			$direccion=mb_convert_encoding($row3['contenidoUrl'], "UTF-8","ISO-8859-1");
			$arreglo1=explode("=",$direccion);
			//echo $arreglo1[1];
			$urlyoutube= substr($arreglo1[1],0,11);
			
			//echo $urlyoutube;
			
			
			?>
			<h3><?echo htmlentities($row3['contenidoTitulo']);?></h3>
			<div align='center'>
			<object width="470" height="320" type="application/x-shockwave-flash" data="http://www.youtube.com/v/<? echo $urlyoutube;?>">
			<param name="allowFullScreen" value="true"></param>
			<param name="movie" value="http://www.youtube.com/v/<? echo $urlyoutube;?>" />
			</object>
			</div>
			<?
			break;
			}
			}
			?>
			
		</td>
	</tr>
	<tr>
	<td align="center">
	<?
	switch($tipo){
	case 1: 
		include ("cuestionario_prueba.php");
		break;
	case 2:
		include ("encuesta_prueba.php");
	    break;
	case 3:
		
	?>
	<br/>
	<br/>
	<br/>
	<?
	break;
	}
	?>
	</td>
	</tr>


</table>

</td>
<td  width="15%" style="background-color:#BFD1E5;">&nbsp;</td>
</tr>
</table>

<script src="scripts/DD_roundies.js"></script>
<script>

  DD_roundies.addRule('.pregunta, .positivo, .negativo', '5px', true);
  
  function ValidarC(objeto,numero){
	var envioId =$('envioId').value;
	var capsulaId =$('capsulaId').value; 
	var versionId =$('versionId').value;
	var usuarioId =$('usuarioId').value;
	
	var nobjeto='.'+objeto;
	var arreglo=$$(nobjeto);
	var largo=arreglo.length;
	var chequeado=0; 
	var radio;
	var correcta;
	var nombreDiv='';
        var nombreCorrecta='';
	var nombreBoton='Validar'+numero;	 
	 for (i=0;i<largo;i++){
		radio=arreglo[i].checked;
		if(radio==true){
			chequeado=chequeado+1;
		}
	 }
	if(chequeado==0){
		alert('Escoja una opcion');
	}else{
		for (i=0;i<largo;i++){
		radio=arreglo[i];
		if(radio.checked==true){
			correcta=radio.getNext().value;
				if(correcta==1){
					nombreDiv='Positivo'+numero;
				}else{
					nombreDiv='Negativo'+numero;
				}
				nombreCorrecta='RespuestaCorrecta'+numero;
				$(nombreCorrecta).setStyle('visibility', 'visible');
				$(nombreDiv).setStyle('display', 'block');
				$(nombreBoton).set('disabled','disabled');
				var respuestaUsuario=radio.value;
				// var elRequest = new Request({
					// url		: 'librerias/responder_capsula_cuestionario.php', 
					// method  :'post',
					// link   : 'chain',
					// onSuccess: function(html) {
					// },
					// onFailure: function() {
						// $('Listado_Preguntas').set('html', 'Error de conexi&oacute;n');
					// }
				// });
				 
				 // elRequest.send(	"envioId=" 		        + envioId + 
									// "&capsulaId=" 	        + capsulaId+
									// "&capsulaVersion="		+ versionId +
									// "&usuarioId="			+ usuarioId +
									// "&preguntaId="			+ numero +
									// "&respuestaUsuario="	+ respuestaUsuario
									
								// );

		}
		}
	}
	
  }
  
  function ValidarE(listadoID){
	//Validar que haya respondido todas las preguntas:
	var nobjeto='.'+listadoID;
	var arreglo=$$(nobjeto);
	var largo=arreglo.length;
	var chequeadas=0;
	
	var envioId =$('envioId').value;
	var capsulaId =$('capsulaId').value; 
	var versionId =$('versionId').value;
	var usuarioId =$('usuarioId').value;
	
	for(i=0;i<largo;i++){
		var j=i+1;
		var alternativas=$$(nobjeto+' .alternativas .Respuesta__'+j);
		var cantAlternativas=alternativas.length;
			for(f=0;f<cantAlternativas;f++){
					if(alternativas[f].checked==true){
						chequeadas=chequeadas+1;
					}
			}
			
	}
	if(chequeadas!=largo){
		alert('Debe responder todas las preguntas');
	}else{
		$('Positivo').setStyle('display', 'block');
		$('Validar').set('disabled','disabled');
	}
  }
  

  
</script>

