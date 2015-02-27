<?
session_start();
include("../default.php");

ini_set("memory_limit","600M");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

?>
<script type="text/javascript" src="../scripts/overlay.js"></script>
<script type="text/javascript" src="../scripts/multiBox.js"></script>
<link type="text/css" rel="stylesheet" href="../skins/saam/plantillas/multiBox.css" />
<div>
<?
$usuario_id = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
			
			if($usuario_id ==''){
			echo "<script>window.location='../index.php';</script>";
?>
<?
}else{


	if($_SESSION['perfilId']==1){
		$menu1="display:block;";
	}else{
		$menu1="display:none;";
	}
	
$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"FECHA"	=>	"$fecha",
							"MANT"=>"$menu1"
							));

echo $plantilla->show();

$plantilla->setTemplate("cargaCap");
echo $plantilla->show();

}
?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>

<?
//pregunta si el formulario hizo postback:
if (isset($_POST['CargararchivoReal'])){

	/** Include path **/
	set_include_path('../Classes/');

	/** PHPExcel_IOFactory */
	include 'PHPExcel/IOFactory.php';


	$inputFileType = 'Excel5';
	//	$inputFileType = 'Excel2007';
	//	$inputFileType = 'Excel2003XML';
	//	$inputFileType = 'OOCalc';
	//	$inputFileType = 'Gnumeric';

	if (isset($_FILES['archivo']['tmp_name'])) {
	 if($error==UPLOAD_ERR_OK) { 
		$inputFileName = $_FILES['archivo']['tmp_name'];
	 }
	}





	$sheetnames = array('CAPSULAS','CONTENIDOS','PREGUNTAS','Hoja4');

	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objReader->setLoadSheetsOnly($sheetnames);

	try {
	$objPHPExcel = $objReader->load($inputFileName);



		$loadedSheetNames = $objPHPExcel->getSheetNames();
		//obtengo el tipo:
		$tipo=0;
		$objPHPExcel->setActiveSheetIndex(3);
		$tipo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 2)->getValue();
		
		$objPHPExcel->setActiveSheetIndex(0);
		$cantcapsulas=0;
		$cantcapsulasOk=0;
		$max=$objPHPExcel->getActiveSheet()->getHighestRow();
		for ($row = 12; $row <= $max; $row++){
			$objPHPExcel->setActiveSheetIndex(0);
			$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
			if($capsula!=''){
				$cantcapsulas=$cantcapsulas+1;
				$tema=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
				$descripcion=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
				$tema_id=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getCalculatedValue();
				
				 if(ValidaContenidos($objPHPExcel,$capsula)==true){
					 if(ValidaPreguntas($objPHPExcel,$capsula,$tipo)==true){
						 $cantcapsulasOk=$cantcapsulasOk+1;
					 }else{
						 echo "<script>alert('Debe ingresar al menos una pregunta para cada C\u00e1psula');</script>";
					 }
					
				 }else{
					 echo "<script>alert('Debe ingresar al menos un contenido para cada C\u00e1psula');<script>";
				}
			}
		}
		
		//$cantcapsulas=$cantcapsulasOk;
				
		if($cantcapsulas==$cantcapsulasOk){
			//ahora que ya prevalid� el archivo, hago la carga de las capsulas, contenidos, preguntas y respuestas en la base de datos:
			
			
			$objPHPExcel->setActiveSheetIndex(0);
			$max=$objPHPExcel->getActiveSheet()->getHighestRow();
			
			for ($fila = 12; $fila <= $max; $fila++){
				$objPHPExcel->setActiveSheetIndex(0);
				$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $fila)->getValue();
					
					// if($capsula!=''){
						// ECHO $capsula."<BR/>";
					// }
					
					if($capsula!=''){
						$tema=0;
						$descripcion='';
						$tema_id=0;
						$query='';
						
						
						$tema=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $fila)->getValue();
						$descripcion=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $fila)->getValue();
						
							$nombre_tema= mb_convert_encoding($tema, "ISO-8859-1", "UTF-8");
							$queryTema 	= "EXEC capTraerTemaId '".$nombre_tema."',".$cliente_id." ";
							//echo $queryTema ;
							$resultTema = $base_datos->sql_query($queryTema);
							$rowTema	= $base_datos->sql_fetch_assoc($resultTema);
							$errorIns = $rowTema["temaId"] ? $rowTema["temaId"] : 0;						
							$administrador_id=$_SESSION['administradorId'];
							$capnombre=mb_convert_encoding($capsula, "ISO-8859-1", "UTF-8");
						//$tema_id=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $fila)->getCalculatedValue();
						$tema_id=$errorIns;

						$query 	= "EXEC capInsertarCapsula ".$cliente_id.",".$tema_id.",'".$capnombre."','".$descripcion."',".$tipo.",'".$administrador_id."' ";
						//ECHO $query."<BR/>";
						 $result = $base_datos->sql_query($query);
						 $rowIns	= $base_datos->sql_fetch_assoc($result);
						
						 $errorIns = $rowIns["errorIns"] ? $rowIns["errorIns"] : 0;
						 $id = $rowIns["capsulaId"] ? $rowIns["capsulaId"] : 0;
						
							 if($errorIns ==0){
									$capsulaId=$id;
								//CONTENIDOS DE LA CAPSULA:
								$nombreCapsula=$capsula;
								$objPHPExcel->setActiveSheetIndex(1);
								$maxCont=$objPHPExcel->getActiveSheet()->getHighestRow();
									for ($rowCont = 12; $rowCont <= $maxCont; ++$rowCont){
										$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $rowCont)->getValue();
										if($nombreCapsula==$capsula){
											$capsulaVersion=1;
											$titulo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $rowCont)->getValue();
											$texto=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $rowCont)->getValue();
											$titulo=mb_convert_encoding($titulo, "ISO-8859-1", "UTF-8");
											$texto=mb_convert_encoding($texto, "ISO-8859-1", "UTF-8");
											
											if($titulo!=''){
												$query2 	= "EXEC capInsertarContenidoTexto ".$cliente_id.",".$capsulaId.",".$capsulaVersion.",'".$titulo."','".$texto."','".$nusuario."' ";
												//echo $query2."<br/>";
												$result = $base_datos->sql_query($query2);
											}
										}
									}
								// //PREGUNTAS DE LA CAPSULA:
								
								$objPHPExcel->setActiveSheetIndex(2);
								$maxPreg=$objPHPExcel->getActiveSheet()->getHighestRow();
									for ($rowPreg = 12; $rowPreg <= $maxPreg; ++$rowPreg){
										$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $rowPreg)->getValue();
										if($nombreCapsula==$capsula){
											$capsulaVersion=1;
											if($tipo==1){
												$pregunta=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $rowPreg)->getValue();
												$alt1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $rowPreg)->getValue();
												$corr1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $rowPreg)->getValue();
												$alt2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $rowPreg)->getValue();
												$corr2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $rowPreg)->getValue();
												$alt3=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $rowPreg)->getValue();
												$corr3=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $rowPreg)->getValue();
												$alt4=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $rowPreg)->getValue();
												$corr4=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $rowPreg)->getValue();
												$alt5=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $rowPreg)->getValue();
												$corr5=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $rowPreg)->getValue();
												$positivo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $rowPreg)->getValue();
												$negativo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $rowPreg)->getValue();
											}else{
												$pregunta=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $rowPreg)->getValue();
												$alt1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $rowPreg)->getValue();
												$corr1='';
												$alt2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $rowPreg)->getValue();
												$corr2='';
												$alt3=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $rowPreg)->getValue();
												$corr3='';
												$alt4=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $rowPreg)->getValue();
												$corr4='';
												$alt5=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $rowPreg)->getValue();
												$corr5='';
												$positivo='';
												$negativo='';
											}
											$preguntaTexto=mb_convert_encoding($pregunta, "ISO-8859-1", "UTF-8");
											$mensajePositivo=mb_convert_encoding($positivo, "ISO-8859-1", "UTF-8");
											$mensajeNegativo=mb_convert_encoding($negativo, "ISO-8859-1", "UTF-8");
											
											if($pregunta!=''){
												$queryP 	= "EXEC capInsertarPregunta ".$capsulaId.",".$capsulaVersion.",'".$preguntaTexto."','".$mensajePositivo."','".$mensajeNegativo."','".$nusuario."' ";
												$resultP = $base_datos->sql_query($queryP);
												$rowP	= $base_datos->sql_fetch_assoc($resultP);
												$preguntaId = $rowP["preguntaId"] ? $rowP["preguntaId"] : 0;
												
												//echo $queryP."<br/>";
								
												if($alt1!=''){
													$Texto=mb_convert_encoding($alt1, "ISO-8859-1", "UTF-8");
													$resp_correcta=$corr1;
													$queryR = "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."' ";
													//echo $queryR."<br/>";
													$resultR = $base_datos->sql_query($queryR);
												}
												if($alt2!=''){
													$Texto=mb_convert_encoding($alt2, "ISO-8859-1", "UTF-8");
													$resp_correcta=$corr2;
													$queryR = "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."' ";
													//echo $queryR."<br/>";
													$resultR = $base_datos->sql_query($queryR);
												}
												if($alt3!=''){
													$Texto=mb_convert_encoding($alt3, "ISO-8859-1", "UTF-8");
													$resp_correcta=$corr3;
													$queryR = "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."' ";
													//echo $queryR."<br/>";
													$resultR = $base_datos->sql_query($queryR);
												}
												if($alt4!=''){
													$Texto=mb_convert_encoding($alt4, "ISO-8859-1", "UTF-8");
													$resp_correcta=$corr4;
													$queryR = "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."' ";
													//echo $queryR."<br/>";
													$resultR = $base_datos->sql_query($queryR);
												}
												if($alt5!=''){
													$Texto=mb_convert_encoding($alt5, "ISO-8859-1", "UTF-8");
													$resp_correcta=$corr5;
													$queryR = "EXEC capInsertarRespuesta ".$preguntaId.",".$capsulaId.",".$capsulaVersion.",'".$Texto."','".$resp_correcta."','".$nusuario."' ";
													//echo $queryR."<br/>";
													$resultR = $base_datos->sql_query($queryR);
												}
											
											}
											
											$q1="update Capsulas set capsulaEstado=1 WHERE clienteId=$cliente_id and capsulaId=$capsulaId AND capsulaVersion=$capsulaVersion";
											$rr = $base_datos->sql_query($q1);
											
										}
									}
							 }
					}
			}
			
			echo "<script>alert('Las c\u00e1psulas han sido cargadas exitosamente');</script>";
			
		}else{
			//echo "<script>alert('Debe ingresar contenido y preguntas a todas las c\u00e1psulas\nCorrija su archivo e intentelo de nuevo');</script>";
		}
		


	}catch(Exception $e) {
	   //echo 'Error: '.$e->getMessage();
	}

}

function ValidaContenidos($objPHPExcel,$nombreCapsula){
//valido que exista al menos un contenido completo para la c�psula:
	$cantidad=0;
	$objPHPExcel->setActiveSheetIndex(1);
	$max=$objPHPExcel->getActiveSheet()->getHighestRow();
	for ($row = 12; $row <= $max; ++ $row){
		$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
		if($nombreCapsula==$capsula){
			$titulo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
			$texto=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
			if($titulo!=''&&$texto!=''){
				$cantidad=$cantidad+1;
			}
		}
	}
	if($cantidad>=1){
		return(true);
	}else{
		return(false);
	}
}


function ValidaPreguntas($objPHPExcel,$nombreCapsula,$tipo){
//valido que exista al menos una pregunta completa para la c�psula:
	if($tipo==1){
		$cantidad=0;
		$objPHPExcel->setActiveSheetIndex(2);
		$max=$objPHPExcel->getActiveSheet()->getHighestRow();
		for ($row = 12; $row <= $max; ++ $row){
			$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
			if($nombreCapsula==$capsula){
				$pregunta=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
				$alt1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
				$corr1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
				$alt2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
				$corr2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
				$positivo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $row)->getValue();
				$negativo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $row)->getValue();
				
				if(
					$pregunta!='' &&
					$alt1!='' &&
					$corr1!='' &&
					$alt2!='' &&
					$corr2!='' &&
					$positivo!='' &&
					$negativo!=''
				){
					$cantidad=$cantidad+1;
				}
			}
		}
		//echo "cantidad:"+$cantidad+"</br>";
		
		if($cantidad>=1){
			return(true);
		}else{
			return(false);
		}
	}else{
		$cantidad=0;
		$objPHPExcel->setActiveSheetIndex(2);
		$max=$objPHPExcel->getActiveSheet()->getHighestRow();
		for ($row = 12; $row <= $max; ++ $row){
			$capsula=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
			if($nombreCapsula==$capsula){
				$pregunta=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
				$alt1=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
				$alt2=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
				
				
				if(
					$pregunta!='' &&
					$alt1!='' &&
					$alt2!=''
				){
					$cantidad=$cantidad+1;
				}
			}
		}
		//echo "cantidad:"+$cantidad+"</br>";
		
		if($cantidad>=1){
			return(true);
		}else{
			return(false);
		}
	}
}

?>


<script>
function Salir(){
$('redir').submit()
}

function ValidarCargaArchivo(){
	   archivo1=$('archivo');
	   $('erroresCarga').set('html','');
	   if(archivo1.value==''){
			$('erroresCarga').set('html', "<span style='color:red'><b>Seleccione un archivo para subir</b></span>");
		}else{
			fullName=archivo1.value;
			splitName = fullName.split(".");
			fileType = splitName[1];
			fileType = fileType.toLowerCase();
			if(fileType!='xls'){
				$('erroresCarga').set('html', "<span style='color:red'><b>Solo se permiten archivos Excel (.xls) </b></span>");
			}else{
			//submit!!!
				document.getElementById('CargararchivoReal').click();
				}
		}
}
function descargaCuestionario(){
 // var elRequest = new Request({
        // url         : 'creaPlantillaCuestionario.php',
        // method      : 'post',
        // async       : false,
        
        // onSuccess   : function(datos) {
                             
        // },
        // //Si Falla
        // onFailure: function() {
            // alert("Se ha producido un error. Por favor, int�ntelo m�s tarde.");
        // }
    // });

    // elRequest.send();  

}
function Volver(){
	window.location = '../capsulas/adm_capsulas.php'
}

</script>
