<?
session_start();

ini_set("memory_limit","128M");

include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;
$administrador_id=$_SESSION['administradorId'];

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');
set_include_path('../Classes/');


include 'PHPExcel/IOFactory.php';


$fileType = 'Excel5';
$fileName = 'CARGA_CUESTIONARIO.xls';

// LEE EL ARCHIVO
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);

//LIMPIEZA DE  DATOS:
$objPHPExcel->setActiveSheetIndex(3);
for($a=0;$a<500;$a++){
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $a, '');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $a, '');
}

// CREO LA ESTRUCTURA PARA LOS DATOS
$objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('A1', 'TEMANOMBRE')
            ->setCellValue('B1', 'TEMAID');

$perfil_id=$_SESSION['perfilId'];

if($perfil_id!=1){
	$query = "select distinct temaNombre,temaId from Temas  WHERE usuarioCreacion='$administrador_id' ORDER BY TemaNombre";			
}else{
	$query = "select distinct temaNombre,temaId from Temas ORDER BY TemaNombre";
}

$result = $base_datos->sql_query($query);
	$indice=2;
	$valores="'";
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		$valores=$valores.$row["temaNombre"].",";
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, $row["temaNombre"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice, $row["temaId"]);
		$indice=$indice+1;
	}	
	$valores=substr($valores,0,(strlen($valores)-1));
	
	$valores=$valores."'";
	//defino el rango
	$objPHPExcel->addNamedRange( new PHPExcel_NamedRange('TEMANOMBRE', $objPHPExcel->getActiveSheet(), 'A2:A'.$indice.'') );
	
	//INSTRUCCIONES DE LA PLANILLA:
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', utf8_encode('Instrucciones'));
	$objPHPExcel->getActiveSheet()->setCellValue('A2', utf8_encode('Seleccione el Tema de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A3', utf8_encode('Ingrese el nombre de la Cápsula'));
	$objPHPExcel->getActiveSheet()->setCellValue('A4', utf8_encode('Ingrese la Descripción de la Cápsula'));
	$objPHPExcel->getActiveSheet()->setCellValue('A6', utf8_encode('Recuerde no manipular el orden de las hojas de la planilla'));
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	
	$objPHPExcel->setActiveSheetIndex(1);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', utf8_encode('Instrucciones'));
	$objPHPExcel->getActiveSheet()->setCellValue('A2', utf8_encode('Seleccione la Cápsula de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A3', utf8_encode('Ingrese el título del Contenido y su texto'));
	$objPHPExcel->getActiveSheet()->setCellValue('A4', utf8_encode('Recuerde que cada Cápsula debe tener al menos un contenido'));
	$objPHPExcel->getActiveSheet()->setCellValue('A6', utf8_encode('Recuerde no manipular el orden de las hojas de la planilla'));
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', utf8_encode('Instrucciones'));
	$objPHPExcel->getActiveSheet()->setCellValue('A2', utf8_encode('Seleccione la Cápsula de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A3', utf8_encode('Ingrese la pregunta y al menos dos alternativas de respuesta y SÓLO UNA de ellas'));
	$objPHPExcel->getActiveSheet()->setCellValue('A4', utf8_encode('como correcta, seleccionando "SI" de la lista desplegable.'));
	$objPHPExcel->getActiveSheet()->setCellValue('A5', utf8_encode('Todas las altenativas ingresadas deberán marcarse si con correctas, '));
	$objPHPExcel->getActiveSheet()->setCellValue('A6', utf8_encode('seleccionando SI o NO de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A8', utf8_encode('Recuerde no manipular el orden de las hojas de la planilla'));
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('A12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja4!$A$2:$A$'.$indice);
	
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->setDataValidation(clone $objValidation);
		//formula de temaID:
		
	}

	
	//NOMBRE DE CAPSULA A COMBO PARA CONTENIDOS:
	$objPHPExcel->setActiveSheetIndex(1);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('A12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('CAPSULAS!$B$12:$B$35');
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->setDataValidation(clone $objValidation);
	}
	
	
	//NOMBRE DE CAPSULA A COMBO PARA PREGUNTAS:
	$objPHPExcel->setActiveSheetIndex(2);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('A12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('CAPSULAS!$B$12:$B$35');
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->setDataValidation(clone $objValidation);
	}

	
	//SI O NO PARA ALTERNATIVAS DE RESPUESTA:
	$objPHPExcel->setActiveSheetIndex(2);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('D12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja4!$C$2:$C$3');
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->setDataValidation(clone $objValidation);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->setDataValidation(clone $objValidation);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $i)->setDataValidation(clone $objValidation);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $i)->setDataValidation(clone $objValidation);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $i)->setDataValidation(clone $objValidation);
	}
	
	$objPHPExcel->setActiveSheetIndex(3);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2,'1');
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	
// GUARDA EL ARCHIVO
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($fileName);

// FUERZA LA DESCARGA
header("Content-disposition: attachment; filename=$fileName");
header("Content-type: application/octet-stream");
readfile($fileName);

?>