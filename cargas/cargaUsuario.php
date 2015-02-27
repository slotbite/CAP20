<?
session_start();
include("../default.php");

ini_set("memory_limit","600M");

setlocale(LC_TIME, "spanish");
$fechaActual = htmlentities(strftime("%A %d de %B del %Y"));
$fechaActual = ucfirst($fechaActual);

$nusuario = $_SESSION['usuario'];
$plantilla->setPath('../skins/saam/plantillas/');
$plantilla->setTemplate("header_2");
$plantilla->setVars(array("USUARIO" => " $nusuario ",
    "FECHA" => "$fechaActual"));
echo $plantilla->show();

$nusuario=$_SESSION['usuario'] ? $_SESSION['usuario'] : '';

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;



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
$plantilla->setTemplate("menu2");
$fecha=date("d-m-Y");
$plantilla->setVars(array(	"USUARIO"		=>"$usuario_id",
							"FECHA"	=>	"$fecha"
							));


echo $plantilla->show();

$plantilla->setTemplate("cargaUsuario");
echo $plantilla->show();

}
?>
</div>
<?
$plantilla->setTemplate("footer_2");
echo $plantilla->show();
?>
<?
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
	
	$sheetnames = array('USUARIOS','Matriz de Carga','Hoja2');

	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objReader->setLoadSheetsOnly($sheetnames);
	
	$cant_usuariosOK=0;
	
	try {
	$objPHPExcel = $objReader->load($inputFileName);
		
		// echo "<table border='1'>";
		
		$objPHPExcel->setActiveSheetIndex(0);
		$max=$objPHPExcel->getActiveSheet()->getHighestRow();
		for ($row = 12; $row <= $max; $row++){
			$objPHPExcel->setActiveSheetIndex(0);
			$nombres=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
			if($nombres!=''){
			    $cant_usuarios=$cant_usuarios+1;
				$apellidos=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
				$rut=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
				$email=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
				
				$org=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
				$sect=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
				$area=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
				$cargo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
				
				if(
					$apellidos!='' &&
					$rut!='' &&
					$email!='' &&
					$org!='' &&
					$sect!='' &&
					$cargo!='' 
				){
					
							$org= mb_convert_encoding($org, "ISO-8859-1", "UTF-8");
							$queryOrg 	= "EXEC carTraeOrganizacionId ".$cliente_id.",'".$org."'";
							$resultOrg = $base_datos->sql_query($queryOrg);
							$rowOrg	= $base_datos->sql_fetch_assoc($resultOrg);
							$organizacion_id = $rowOrg["organizacionId"] ? $rowOrg["organizacionId"] : 0;	
							if($organizacion_id!=0){
								if($sect!=''){
								
									$sect= mb_convert_encoding($sect, "ISO-8859-1", "UTF-8");
									$querySec 	= "EXEC carTraeSectorId ".$cliente_id.",".$organizacion_id.",'".$sect."'";
									$resultSec = $base_datos->sql_query($querySec);
									$rowSec	= $base_datos->sql_fetch_assoc($resultSec);
									$sector_id = $rowSec["sectorId"] ? $rowSec["sectorId"] : 0;	
										if($sector_id==0){
											$sector_id=-1;
										}
										
								}else{
									$sector_id=0;
								}
								
								if($area!=''){
									if($sector_id!=0){
										$area= mb_convert_encoding($area, "ISO-8859-1", "UTF-8");
										$queryArea 	= "EXEC carTraeAreaId ".$cliente_id.",".$organizacion_id.",".$sector_id.",'".$$area."'";
										$resultArea = $base_datos->sql_query($queryArea);
										$rowArea	= $base_datos->sql_fetch_assoc($resultArea);
										$area_id = $rowSec["areaId"] ? $rowSec["areaId"] : 0;
											if($area_id==0){
												$area_id=-1;
											}
									}
								}else{
									$area_id=0;
								}
								
							}
					
					if(
					$organizacion_id!=-1&&
					$sector_id!=-1&&
					$area!=-1
					){
						$cant_usuariosOK=$cant_usuariosOK+1;
					}
				}
			}
		}
		
		//echo "usu:".$cant_usuarios.":ok:".$cant_usuariosOK;
		
		if($cant_usuarios==$cant_usuariosOK){
			//PROCESO DE CARGA:
			
			$objPHPExcel->setActiveSheetIndex(0);
			$max=$objPHPExcel->getActiveSheet()->getHighestRow();
			for ($row = 12; $row <= $max; $row++){
				$objPHPExcel->setActiveSheetIndex(0);
				$nombres=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
				if($nombres!=''){
					$apellidos=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
					$rut=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
					$email=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
					
					$org=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
					$sect=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
					$area=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
					$cargo=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
					
					$org= mb_convert_encoding($org, "ISO-8859-1", "UTF-8");
					$queryOrg 	= "EXEC carTraeOrganizacionId ".$cliente_id.",'".$org."'";
					$resultOrg = $base_datos->sql_query($queryOrg);
					$rowOrg	= $base_datos->sql_fetch_assoc($resultOrg);
					$organizacion_id = $rowOrg["organizacionId"] ? $rowOrg["organizacionId"] : 0;	
					
					if($sect!=''){
					
						$sect= mb_convert_encoding($sect, "ISO-8859-1", "UTF-8");
						$querySec 	= "EXEC carTraeSectorId ".$cliente_id.",".$organizacion_id.",'".$sect."'";
						$resultSec = $base_datos->sql_query($querySec);
						$rowSec	= $base_datos->sql_fetch_assoc($resultSec);
						$sector_id = $rowSec["sectorId"] ? $rowSec["sectorId"] : 0;	
						
							
					}else{
						$sector_id=0;
					}
					
					if($area!=''){
						if($sector_id!=0){
							$area= mb_convert_encoding($area, "ISO-8859-1", "UTF-8");
							$queryArea 	= "EXEC carTraeAreaId ".$cliente_id.",".$organizacion_id.",".$sector_id.",'".$area."'";
							//echo $queryArea;
							$resultArea = $base_datos->sql_query($queryArea);
							$rowArea	= $base_datos->sql_fetch_assoc($resultArea);
							$area_id = $rowArea["areaId"] ? $rowArea["areaId"] : 0;
							
						}
					}else{
						$area_id=0;
					}
					
					$cargo= mb_convert_encoding($cargo, "ISO-8859-1", "UTF-8");
							$queryCargo 	= "EXEC carTraeCargoId ".$cliente_id.",'".$cargo."'";
							$resultCargo = $base_datos->sql_query($queryCargo);
							$rowCargo	= $base_datos->sql_fetch_assoc($resultCargo);
							$cargo_id = $rowCargo["cargoId"] ? $rowCargo["cargoId"] : 0;
					
					
					$nombres=mb_convert_encoding($nombres, "ISO-8859-1", "UTF-8");
					$apellidos=mb_convert_encoding($apellidos, "ISO-8859-1", "UTF-8");
					$qq="exec carInsertarUsuario $cliente_id,'$rut','$nombres','$apellidos','$email',$cargo_id,$organizacion_id,$sector_id,$area_id";
					//echo $qq."<br>";
					$rr = $base_datos->sql_query($qq);
	
				}
			}
			
			echo "<script>alert('Los usuarios han sido cargados exitosamente');</script>";
		}else{
			echo "<script>alert('Existen problemas en su planilla. Verifique la Matriz de Carga, complete todos los datos requeridos e intentelo de nuevo');</script>";
		}
		// echo "</table>";
		
		
		
	}catch(Exception $e) {
	   //echo 'Error: '.$e->getMessage();
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
	window.location = '../mantenedores/admMantenedores.php'
}
</script>
