<?

session_start();

ini_set("memory_limit","128M");

include ("../librerias/conexion.php");

$nusuario   = $_SESSION['usuario'] ? $_SESSION['usuario'] : '';
$cliente_id = $_SESSION['clienteId'] ? $_SESSION['clienteId'] :0;


error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');
set_include_path('../Classes/');


include 'PHPExcel/IOFactory.php';


$fileType = 'Excel5';
$fileName = 'CARGA_USUARIOS.xls';


$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);

// CREO LA ESTRUCTURA PARA LOS DATOS

//ORGANIZACIONES
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A1', 'ORGANIZACION_NOMBRE');
			
$query = "select distinct organizacionNombre,organizacionId from Organizaciones where OrganizacionEstado=1 and clienteId=$cliente_id ORDER BY organizacionNombre";
$result = $base_datos->sql_query($query);
	$indice=2;
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, utf8_encode($row["organizacionNombre"]));
		$indice=$indice+1;
	}	

//LIMPIEZA DE  DATOS:
$objPHPExcel->setActiveSheetIndex(2);
for($a=0;$a<500;$a++){
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $a, '');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $a, '');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $a, '');
}	
	
//SECTORES	
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('b1', 'SECTOR_NOMBRE');
			
$queryS = "SELECT DISTINCT sectorNombre FROM Sectores WHERE sectorEstado=1 AND clienteId=$cliente_id ORDER BY sectorNombre";
$resultS = $base_datos->sql_query($queryS);
	$indice2=2;
	while ($rowS = $base_datos->sql_fetch_assoc($resultS)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice2, utf8_encode($rowS["sectorNombre"]));
		$indice2=$indice2+1;
	}		

//AREAS
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C1', 'AREA_NOMBRE');
			
$queryS = "SELECT DISTINCT areaNombre FROM AREAS WHERE areaEstado=1 AND clienteId=$cliente_id ORDER BY areaNombre";
$resultS = $base_datos->sql_query($queryS);
	$indice3=2;
	while ($rowS = $base_datos->sql_fetch_assoc($resultS)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $indice3, utf8_encode($rowS["areaNombre"]));
		$indice3=$indice3+1;
	}
//CARGOS:
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('D1', 'CARGO_NOMBRE');
			
$queryS = "SELECT DISTINCT cargoNombre FROM Cargos WHERE cargoEstado=1 AND clienteId=$cliente_id ORDER BY cargoNombre";
$resultS = $base_datos->sql_query($queryS);
	$indice4=2;
	while ($rowS = $base_datos->sql_fetch_assoc($resultS)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $indice4, utf8_encode($rowS["cargoNombre"]));
		$indice4=$indice4+1;
	}

//VALIDACIONES DE DATOS CON LISTAS DEPLEGABLES:

//ORGANIZACION:
	$objPHPExcel->setActiveSheetIndex(0);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('E12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja2!$A$2:$A$'.$indice);
	
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->setDataValidation(clone $objValidation);
	}

//SECTOR:
$objPHPExcel->setActiveSheetIndex(0);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('F12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja2!$B$2:$B$'.$indice2);
	
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->setDataValidation(clone $objValidation);
	}

//AREA:
$objPHPExcel->setActiveSheetIndex(0);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('G12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja2!$C$2:$C$'.$indice3);
	
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $i)->setDataValidation(clone $objValidation);
	}
	
//CARGO:
$objPHPExcel->setActiveSheetIndex(0);
	$objValidation = $objPHPExcel->getActiveSheet()->getCell('H12')->getDataValidation();
	$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
	$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
	$objValidation->setAllowBlank(false);
	$objValidation->setShowInputMessage(true);
	$objValidation->setShowErrorMessage(true);
	$objValidation->setShowDropDown(true);
	$objValidation->setFormula1('Hoja2!$D$2:$D$'.$indice4);
	
	for ($i = 12; $i<= 35; $i++){
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $i)->setDataValidation(clone $objValidation);
	}

//MATRIZ DE CARGA:
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('B2', utf8_encode('Organización'))
			->setCellValue('C2', utf8_encode('Gerencia/Agencia'))
			->setCellValue('D2', utf8_encode('Area'));
			
$queryS1 = "EXEC carMuestraMatrizCarga $cliente_id";
$resultS1 = $base_datos->sql_query($queryS1);
	$indice5=3;
	while ($rowS1 = $base_datos->sql_fetch_assoc($resultS1)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice5, utf8_encode($rowS1["organizacionNombre"]));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $indice5, utf8_encode($rowS1["sectorNombre"]));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $indice5, utf8_encode($rowS1["areaNombre"]));
		$indice5=$indice5+1;
	}
	
	$styleArray = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '0000000'),
		),
	),
);
$indice5=$indice5-1;

$rango='B2:D'.$indice5;
//echo $rango;
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);


//INSTRUCCIONES DE LA PLANILLA:
	$objPHPExcel->setActiveSheetIndex(0);
	for($n=0;$n<10;$n++){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $n, '');
	}	
	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', utf8_encode('Instrucciones'));
	$objPHPExcel->getActiveSheet()->setCellValue('A2', utf8_encode('Ingrese los Nombres,Apellidos,rut y e-mail del usuario '));
	$objPHPExcel->getActiveSheet()->setCellValue('A3', utf8_encode('Seleccione la Organización de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A4', utf8_encode('Seleccione la Gerencia/Agencia de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A5', utf8_encode('Seleccione el Área de la lista desplegable'));
	$objPHPExcel->getActiveSheet()->setCellValue('A6', utf8_encode('Revise si existe la asociación de Organización, Gerencia/Agencia y Área en la Hoja de Matriz de Carga'));
	$objPHPExcel->getActiveSheet()->setCellValue('A7', utf8_encode('Seleccione el Cargo de la lista desplegable'));
	
	$objPHPExcel->getActiveSheet()->setCellValue('A9', utf8_encode('Recuerde no manipular el orden de las hojas de la planilla'));
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);	
	
	$objPHPExcel->setActiveSheetIndex(0);
// GUARDA EL ARCHIVO
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($fileName);

// FUERZA LA DESCARGA
header("Content-disposition: attachment; filename=$fileName");
header("Content-type: application/octet-stream");
readfile($fileName);	
	
	
	
	

?>