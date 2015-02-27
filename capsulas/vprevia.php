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
	$comentario = $row["capsulaComentario"];
	$clienteId=$row["clienteId"];
	//echo $queryTipo;
	
	//echo $Capnombre;
}



?>
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
</style>
<div id="container" >
            <div id="head">                               
                <div style="float:right;width:350px;height:70px;background:url(<? echo $logo; ?>) no-repeat right;">
				</div>
				
                <br><br>
				
					<h1 class="title2">C&aacute;psulas de Conocimiento</h1>

            </div> 
            
            <div style="text-align:right;"><!--<a href="cerrarsesion.php">Salir</a>--></div>   
			<div style="text-align:right;"></div>   			
            <img name="image" src="skins/saam/img/line.gif" width="1024" height="10" alt="">
<?
	  
?>
<br/>
<br/>
<table border="0" width="100%">
	<tr>
		<td>
		<h3>Bienvenido(a)</h3>
		<b><? echo $nombre; ?></b>
		<input type='hidden' id='envioId' name='envioId' value='<? echo $envio;?>'/>
		<input type='hidden' id='capsulaId' name='capsulaId' value='<? echo $capsulaid;?>'/>
		<input type='hidden' id='versionId' name='versionId' value='<? echo $version;?>'/>
		<input type='hidden' id='usuarioId' name='usuarioId' value='<? echo $usuario;?>'/>
		<br/>
		<br/> 
		</td>
	</tr>
	<tr>
		<td>
			<h1 class="nombreCapsula"><? echo htmlentities(stripslashes($Capnombre));?></h1>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
				  
			<?
			$query3 	= "EXEC capListarContenidoCapsula ".$clienteId.",".$capsulaid.",".$version." " ;
			$result3 = $base_datos->sql_query($query3);
			//echo $query3;	
			while ($row3 = $base_datos->sql_fetch_assoc($result3)) {
			echo "<br/>";
			switch($row3['contenidoTipo']){
			case 1:
			?>
				<h3><?echo stripslashes(htmlentities($row3['contenidoTitulo']));?></h3>
				<div><? echo stripslashes($row3['contenidoDescripcion']);?></div>
			<?
			break;
			
			case 2:
			?>
			<div align="center"><img src='<? echo htmlentities($row3['contenidoUrl']);?>' style='border:1px solid #135293;'/></br>
			<span style="font-size:12px;"><? echo stripslashes(htmlentities($row3['contenidoTitulo']));?></span>
			</div>
			<?
			break;
			case 3:
			?>
			<h3><?echo htmlentities($row3['contenidoTitulo']);?></h3>
			<div align='center'>
				<div id='mediaspace<? echo $row3['contenidoId']?>'>This text will be replaced</div>
			</div>
			<? 
			
			$url="http://".$_SERVER['HTTP_HOST'].'/capsula20/'.$row3['contenidoUrl'];
			?>
			<script type='text/javascript'>
			  jwplayer('mediaspace<? echo $row3['contenidoId']?>').setup({
				'flashplayer': 'scripts/player.swf',
				'file': '<? echo $url;?>',
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
			
			$url="http://".$_SERVER['HTTP_HOST'].'/capsula20/'.$row3['contenidoUrl'];
			?>
			<script type='text/javascript'>
			  jwplayer('mediaspace<? echo $row3['contenidoId']?>').setup({
				'flashplayer': 'scripts/player.swf',
				'file': '<? echo $url;?>',
				'controlbar': 'bottom',
				'width': '470',
				'height': '320'
			  });
			</script>
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
		//validar Comentarios si Existen:El campo solo parece si la capsula tiene comentario activado
		var campoComentarios=$('Comentario');
		if(campoComentarios!=null){
		
			if(campoComentarios.value==''){
				alert('Debe ingresar su comentario personal');
			}else{
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
												
												$('positivo').setStyle('display', 'block');
												$('validar').set('disabled','disabled');
									}
							}
							
					}
					
					
			}
			//guardo comentarios de la Cápsula si existen:	
			// var elRequest = new Request({
				// url		: 'librerias/comentar_capsula_encuesta.php', 
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
								// "&comentario="		    + campoComentarios.value
								
							// );
													
		}
	}
  }
  

  
</script>

