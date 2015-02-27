<?

include ("librerias/conexion.php");
include ("librerias/crypt.php");

$encrypted= urldecode($_GET['hash']);
$iv="brains12";
	
$i4=enc_dec($encrypted); 


$arreglo1=explode("&",$i4);
if(count($arreglo1)==4){
	$capsulaid1=explode("=",$arreglo1[0]);
	$capsulaid=$capsulaid1[1];

	$version1=explode("=",$arreglo1[1]);
	$version=$version1[1];

	$usuario1=explode("=",$arreglo1[2]);
	$usuario=$usuario1[1];
	
	$envio1=explode("=",$arreglo1[3]);
	$envio=$envio1[1];
        
                
        if((is_numeric($capsulaid))&&(is_numeric($version))&&(is_numeric($usuario))&&(is_numeric($envio)))
        {
            
        $queryEX 	= "EXEC CapVerificaEnvio ".$envio.",".$capsulaid.",".$usuario."";
	$resultEX = $base_datos->sql_query($queryEX);
	$rowEX	= $base_datos->sql_fetch_assoc($resultEX);
        
        $existe=$rowEX['existe'] ? $rowEX['existe']:0;
        if($existe==0){
            ?>
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
                <table border="0" width="100%" height="100%" align="center">
                <tr>
                <td width="15%" style="background-color:#BFD1E5"></td>
                <td Width="70%" height="100%">


                                    <table border="1"  CELLSPACING='0' CELLSPADDING='0' Width="60%" align="center" style="font-size:14px;">
                                        <tr>
                                            <td style="background-color:#4B6C9F;color:white;vertical-align:middle;">
                                                <img src="skins/saam/img/delete.png" border='0' style="padding-top:4px;padding-right:5px;"/><b>Error</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="height:200px;">Error: El envio ya no esta disponible</td>
                                        </tr>
                                        </table>  
                </td>
                <td  width="15%" style="background-color:#BFD1E5;">&nbsp;</td>
                </tr>
                </table>        
                
        <?
            
        }else{
            
            
	$queryL 	= "EXEC envTraeLogoUsuario ".$usuario." ";
	$resultL = $base_datos->sql_query($queryL);
	$rowL	= $base_datos->sql_fetch_assoc($resultL);
	$logo=$rowL['organizacionLogo'] ? $rowL['organizacionLogo']:'';
	$nombreOrg=$rowL['organizacionNombre'] ? $rowL['organizacionNombre']:'';

	$logo=substr($logo,3,strlen($logo));
	
	$query2 	= "EXEC envTraeDatosUsuario ".$usuario." ";
	$result2= $base_datos->sql_query($query2);
	$row2	= $base_datos->sql_fetch_assoc($result2);
	$nombre=$row2['nombres'] ? $row2['nombres']:'';
	$clienteId=$row2['clienteId'] ? $row2['clienteId']:'';
	
	$queryTipo 	= "EXEC capVerCapsula ".$clienteId.",".$capsulaid.",".$version." ";
	$result = $base_datos->sql_query($queryTipo);
	$row	= $base_datos->sql_fetch_assoc($result);
	$Capnombre = $row["capsulaNombre"];
	$tipo = $row["capsulaTipo"];
        $encabezado=$row["temaImagen"];
	//$comentario = $row["capsulaComentario"];
	$comentario=1;
	$clienteId=$row["clienteId"];
        $estadoCapsula = $row["capsulaEstado"];
	//echo "envio:".$envio;
	
        if($estadoCapsula==2){?>
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
                <table border="0" width="100%" height="100%" align="center">
                <tr>
                <td width="15%" style="background-color:#BFD1E5"></td>
                <td Width="70%" height="100%">


                                    <table border="1"  CELLSPACING='0' CELLSPADDING='0' Width="60%" align="center" style="font-size:14px;">
                                        <tr>
                                            <td style="background-color:#4B6C9F;color:white;vertical-align:middle;">
                                                <img src="skins/saam/img/delete.png" border='0' style="padding-top:4px;padding-right:5px;"/><b>Error</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="height:200px;">Error: La c&aacute;psula ha sido anulada y ya no est&aacute; disponible</td>
                                        </tr>
                                        </table>  
                </td>
                <td  width="15%" style="background-color:#BFD1E5;">&nbsp;</td>
                </tr>
                </table> 
                
        <?
        }else{
	
	$queryDur 	= "EXEC capVerificaCierre ".$clienteId.",".$capsulaid.",".$version.",".$envio."";
	$resultD = $base_datos->sql_query($queryDur);
	$rowD	= $base_datos->sql_fetch_assoc($resultD);
	$plazo = $rowD["plazo"];
	//echo $queryDur;

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
<? if($tipo!=3){?>
<div style='width:99%;background-color:#BFD1E5;height:20px;padding-top:4px;padding-left:5px;font-size:16px' >
		<?
			if ($plazo>0){
				echo "<b>Recuerde:</b> Usted tiene <b>$plazo</b> d&iacute;a";
				if($plazo>1){
					echo "s";
				}
				echo " para responder esta C&aacute;psula";
				$cerrada=0;
			}else{
				echo "<b>ATENCI&Oacute;N:</b> Esta C&aacute;psula ya ha sido cerrada";
				$cerrada=1;
			}
		?>
</div>
<? } ?>
<br/>
<table border="0" width="100%">
	<tr>
		<td align="center">
		<div style="text-align:justify;width:760px;word-wrap: break-word">
			<h3>Bienvenido(a)</h3>
					
			<b><? echo htmlentities($nombre); ?></b></br>
			
			<input type='hidden' id='envioId' name='envioId' value='<? echo $envio;?>'/>
			<input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid;?>'/>
			<input type='hidden' id='versionId' name='versionId' value='<? echo $version;?>'/>
			<input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuario;?>'/>
			<input type="hidden" id='cerrada' value='<? echo $cerrada;?>'/>
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
			$query3 	= "EXEC capListarContenidoCapsula ".$clienteId.",".$capsulaid.",".$version." " ;
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
				<div style="text-align:justify;width:760px;word-wrap: break-word;font-size:14px;"><? echo stripslashes($descripcion);?></div>
			<?
			break;
			
			case 2:
			?>
			<div align='center'><img src='<? echo  htmlentities($row3['contenidoUrl']);?>' alt='<? echo htmlentities($row3['contenidoTitulo']);?>' title='<? echo htmlentities($row3['contenidoTitulo']);?>' style='border:1px solid #135293;'/></div>
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
			$urlyoutube= substr($arreglo1[1],0,11);

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
	<td align="left">
	<?
	switch($tipo){
	case 1: 
		include ("cuestionario.php");
		break;
	case 2:
		include ("encuesta.php");
	    break;
	case 3:
	//guardo acuse de recibo:
	$queryConf 	= "EXEC capGuardaAcuseReciboContenido ".$envio.",".$capsulaid.",".$version.",".$usuario." ";
    $resultConf = $base_datos->sql_query($queryConf);
	
	?>
	<br/>
	<br/>
	<br/>
	<?
	break;
	
	if($plazo<0){
	//echo "<script>bloqueaCapsula();</script>";
	}
	
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
				var elRequest = new Request({
					url		: 'librerias/responder_capsula_cuestionario.php', 
					method  :'post',
					link   : 'chain',
					onSuccess: function(html) {
					},
					onFailure: function() {
						$('Listado_Preguntas').set('html', 'Error de conexi&oacute;n');
					}
				});
				 
				 elRequest.send(	"envioId=" 		        + envioId + 
									"&capsulaId=" 	        + capsulaId+
									"&capsulaVersion="		+ versionId +
									"&usuarioId="			+ usuarioId +
									"&preguntaId="			+ numero +
									"&respuestaUsuario="	+ respuestaUsuario
									
								);

		}
		}
	}
	
  }
  //RECORDAR QUITAR VALIDACION CAMPO COMENTARIOS!!!! Y AGREGAR SIEMPRE EL CAMPO A LA ENCUESTA Y A LA DE PRUEBA
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
		//validar Comentarios si Existen:El campo solo parece si la capsula tiene comentario activado
		var campoComentarios=$('Comentario');
		// if(campoComentarios!=null){
		
			// if(campoComentarios.value==''){
				// alert('Debe ingresar su comentario personal');
			// }else{
				//Recorre por pregunta para ir guardando las respuestas:
					for(i=0;i<largo;i++){
						j=i+1;
						alternativas=$$(nobjeto+' .alternativas .Respuesta__'+j);
						
						cantAlternativas=alternativas.length;
							for(f=0;f<cantAlternativas;f++){
								
									if(alternativas[f].checked==true){
										var respuestaUsuario=alternativas[f].value;
										
										
										var padre=alternativas[f].getParent('div').getParent('div').id;
										var preguntaId=padre.replace('Pregunta_','')
												
												var elRequest = new Request({
												url		: 'librerias/responder_capsula_encuesta.php', 
												method  :'post',
												link   : 'chain',
												onSuccess: function(html) {

												},
												onFailure: function() {
													$('Listado_Preguntas').set('html', 'Error de conexi&oacute;n');
												}
											});
											 
											 elRequest.send(	"envioId=" 		        + envioId + 
																"&capsulaId=" 	        + capsulaId+
																"&capsulaVersion="		+ versionId +
																"&usuarioId="			+ usuarioId +
																"&preguntaId="			+ preguntaId +
																"&respuestaUsuario="		+ respuestaUsuario
																
															);
													
									}
							}
							
					}
					
					
			//}
			//guardo comentarios de la C�psula si existen:	
			var elRequest = new Request({
				url		: 'librerias/comentar_capsula_encuesta.php', 
				method  :'post',
				link   : 'chain',
				onSuccess: function(html) {

				},
				onFailure: function() {
					$('Listado_Preguntas').set('html', 'Error de conexi&oacute;n');
				}
			});

			 elRequest.send(	"envioId=" 		        + envioId + 
								"&capsulaId=" 	        + capsulaId+
								"&capsulaVersion="		+ versionId +
								"&usuarioId="			+ usuarioId +
								"&comentario="		    + campoComentarios.value
								
							);
													
		
		$('positivo').setStyle('display', 'block');
		$('validar').set('disabled','disabled');
		
		//}
	}
  }
  
  
  
  //Esta funci�n bloquea los controles de la c�psula cuando est� cerrada:
    window.addEvent('domready', function(){
		var cerrada=$('cerrada').value;
		if(cerrada==1){
			elementos = $$("input");
			for (i=0; i<elementos.length; i++){
				elementos[i].disabled=true;
			}
			
			if( $('Comentario')!=null){
				$('Comentario').disabled=true;
				}
				
		}
    })
  </script>

<?
 }
 }       
}else{
    $logo="images/EnvioPrueba.jpg";
  ?>
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
<table border="0" width="100%" height="100%" align="center">
<tr>
<td width="15%" style="background-color:#BFD1E5"></td>
<td Width="70%" height="100%">


                      <table border="1"  CELLSPACING='0' CELLSPADDING='0' Width="60%" align="center" style="font-size:14px;">
                          <tr>
                              <td style="background-color:#4B6C9F;color:white;vertical-align:middle;">
                                  <img src="skins/saam/img/delete.png" border='0' style="padding-top:4px;padding-right:5px;"/><b>Error</b>
                              </td>
                          </tr>
                          <tr>
                              <td align="center" style="height:200px;">Error: No debe manipular la URL entregada por la aplicaci&oacute;n</td>
                          </tr>
			</table>  
</td>
 <td  width="15%" style="background-color:#BFD1E5;">&nbsp;</td>
 </tr>
 </table>
                           

<?            
        }
        }
        
?>