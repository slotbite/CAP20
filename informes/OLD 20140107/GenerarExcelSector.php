<?php
//ini_set("memory_limit","256M");
//
//include ("../librerias/conexion.php");
//
//date_default_timezone_set('Europe/London');
//set_include_path('../Classes/');
//
//include 'PHPExcel/IOFactory.php';
//
//$sector=17;
//$sectorNombre="CHACABUCO";
//$mm=8;
//$yyyy=2013;

error_reporting(E_ALL);
set_time_limit(0);


$fileType = 'Excel5';
$fileName = 'plantillaRepGerencia.xls';
$fileName2 = 'RepGerencia_'.$sectorNombre.'.xls';
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);


//<editor-fold>
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A1','ID_USUARIO')
            ->setCellValue('B1','NOMBRE_USUARIO')
            ->setCellValue('C1','ID_TEMA')
            ->setCellValue('D1','TEMA')
            ->setCellValue('E1','ID_CAPSULA')
            ->setCellValue('F1','CAPSULA')
            ->setCellValue('G1','ID_PREGUNTA')
            ->setCellValue('H1','PREGUNTA')
            ->setCellValue('I1','ID_RESPUESTA')
            ->setCellValue('J1','RESPUESTA')
            ->setCellValue('K1','FECHA_RESPUESTA')
            ->setCellValue('L1','FECHA_ENVIO')
            ->setCellValue('M1','FECHA_CIERRE')
            ->setCellValue('N1','organizacionNombre')
            ->setCellValue('O1','sectorNombre')
            ->setCellValue('P1','areaNombre');

//CORRECTAS
$query="EXEC listarDatosReporteGA $sector,$mm,$yyyy,1";
$result = $base_datos->sql_query($query);
	$indice=2;
	$valores="'";
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, $row["ID_USUARIO"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice, utf8_encode($row["NOMBRE_USUARIO"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $indice, $row["ID_TEMA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $indice, stripslashes(utf8_encode($row["TEMA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $indice, $row["ID_CAPSULA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $indice, stripslashes(utf8_encode($row["CAPSULA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $indice, $row["ID_PREGUNTA"]);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $indice, stripslashes(utf8_encode($row["PREGUNTA"])));
                                               
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $indice, $row["ID_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $indice, $row["RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $indice, $row["FECHA_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $indice, $row["FECHA_ENVIO"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $indice, $row["FECHA_CIERRE"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $indice, utf8_encode($row["organizacionNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $indice, utf8_encode($row["sectorNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $indice, utf8_encode($row["areaNombre"]));
                
                $objPHPExcel->getActiveSheet()->getStyle('H'.$indice)->getAlignment()->setWrapText(true);
                //$objPHPExcel->getActiveSheet()->getRowDimension($indice)->setRowHeight(-1);
                //$objPHPExcel->getActiveSheet()->getStyle('A'.$indice.':Q'.$indice)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $indice=$indice+1;
	}	
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12); //->setAutoSize(true);
        
                
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
//</editor-fold>  

        
//<editor-fold>
$objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('A1','ID_USUARIO')
            ->setCellValue('B1','NOMBRE_USUARIO')
            ->setCellValue('C1','ID_TEMA')
            ->setCellValue('D1','TEMA')
            ->setCellValue('E1','ID_CAPSULA')
            ->setCellValue('F1','CAPSULA')
            ->setCellValue('G1','ID_PREGUNTA')
            ->setCellValue('H1','PREGUNTA')
            ->setCellValue('I1','ID_RESPUESTA')
            ->setCellValue('J1','RESPUESTA')
            ->setCellValue('K1','FECHA_RESPUESTA')
            ->setCellValue('L1','FECHA_ENVIO')
            ->setCellValue('M1','FECHA_CIERRE')
            ->setCellValue('N1','organizacionNombre')
            ->setCellValue('O1','sectorNombre')
            ->setCellValue('P1','areaNombre');

//INCORRECTAS

$query="EXEC listarDatosReporteGA $sector,$mm,$yyyy,2";
$result = $base_datos->sql_query($query);
	$indice=2;
	$valores="'";
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, $row["ID_USUARIO"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice, utf8_encode($row["NOMBRE_USUARIO"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $indice, $row["ID_TEMA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $indice, stripslashes(utf8_encode($row["TEMA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $indice, stripslashes(utf8_encode($row["ID_CAPSULA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $indice, stripslashes(utf8_encode($row["CAPSULA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $indice, $row["ID_PREGUNTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $indice, stripslashes(utf8_encode($row["PREGUNTA"])));
                
                $objPHPExcel->getActiveSheet()->getStyle('H'.$indice)->getAlignment()->setWrapText(true);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $indice, $row["ID_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $indice, $row["RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $indice, $row["FECHA_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $indice, $row["FECHA_ENVIO"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $indice, $row["FECHA_CIERRE"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $indice, utf8_encode($row["organizacionNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $indice, utf8_encode($row["sectorNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $indice, utf8_encode($row["areaNombre"]));
                
                //$objPHPExcel->getActiveSheet()->getRowDimension($indice)->setRowHeight(-1);                
                //$objPHPExcel->getActiveSheet()->getStyle('A'.$indice.':Q'.$indice)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $indice=$indice+1;
	}	
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);//->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);      

//</editor-fold>
        
   
//<editor-fold>
//SIN RESPUESTAS:

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1','ID_USUARIO')
            ->setCellValue('B1','NOMBRE_USUARIO')
            ->setCellValue('C1','ID_TEMA')
            ->setCellValue('D1','TEMA')
            ->setCellValue('E1','ID_CAPSULA')
            ->setCellValue('F1','CAPSULA')
            ->setCellValue('G1','ID_PREGUNTA')
            ->setCellValue('H1','PREGUNTA')
            ->setCellValue('I1','ID_RESPUESTA')
            ->setCellValue('J1','RESPUESTA')
            ->setCellValue('K1','FECHA_RESPUESTA')
            ->setCellValue('L1','FECHA_ENVIO')
            ->setCellValue('M1','FECHA_CIERRE')
            ->setCellValue('N1','organizacionNombre')
            ->setCellValue('O1','sectorNombre')
            ->setCellValue('P1','areaNombre');


$query="EXEC listarDatosReporteGA $sector,$mm,$yyyy,3";
$result = $base_datos->sql_query($query);
	$indice=2;
	$valores="'";
	while ($row = $base_datos->sql_fetch_assoc($result)) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, $row["ID_USUARIO"]);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $indice, utf8_encode($row["NOMBRE_USUARIO"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $indice, $row["ID_TEMA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $indice, stripslashes(utf8_encode($row["TEMA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $indice, $row["ID_CAPSULA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $indice, stripslashes(utf8_encode($row["CAPSULA"])));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $indice, $row["ID_PREGUNTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $indice, stripslashes(utf8_encode($row["PREGUNTA"])));
                
                $objPHPExcel->getActiveSheet()->getStyle('H'.$indice)->getAlignment()->setWrapText(true);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $indice, $row["ID_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $indice, $row["RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $indice, $row["FECHA_RESPUESTA"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $indice, $row["FECHA_ENVIO"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $indice, $row["FECHA_CIERRE"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $indice, utf8_encode($row["organizacionNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $indice, utf8_encode($row["sectorNombre"]));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $indice, utf8_encode($row["areaNombre"]));
                
                //$objPHPExcel->getActiveSheet()->getRowDimension($indice)->setRowHeight(-1);
                //$objPHPExcel->getActiveSheet()->getStyle('A'.$indice.':Q'.$indice)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                
                $indice=$indice+1;
	}	
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);//->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
  
        
//</editor-fold>
        
//Página "RESUMEN":
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1','RESPUESTAS');

$query="EXEC listarDatosReporteGAResumen $sector";
$result = mssql_query($query);
$n_campos=mssql_num_fields($result);
//TITULOS DESDE LOS NOMBRES DE LOS CAMPOS:
for ($i = 0; $i < mssql_num_fields($result); ++$i) {
    $field = mssql_fetch_field($result, $i);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$field->name);
}

$indice=2;
$result = mssql_query($query);

//RESULTADOS DE LAS FILAS DEL SP:
while ($row = mssql_fetch_array($result)) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice, stripslashes(utf8_encode($row[0])));
    for ($i = 1; $i < $n_campos; ++$i) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $indice, stripslashes(utf8_encode($row[$i])));
    }
    $indice=$indice+1;
}


for ($i = 1; $i < $n_campos; ++$i) {
  //TOTALES GENERALES POR COLUMNA:
 $letra=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, $indice)->getColumn();
 $indice2=$indice-1;
 $formula='=SUM('.$letra.'2:'.$letra.$indice2.')';
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $indice,$formula );
 }
 
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $indice,'Total General' );
 
 //NEGRITA DE LOS TITULOS:
 $objPHPExcel->getActiveSheet()->getStyle("A1:".$letra.'1')->applyFromArray(array("font" => array( "bold" => true)));
 

 

 for ($i = 1; $i < $n_campos; ++$i) {
 //CALCULO DE PORCENTAJE:
 $letra=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, $indice+1)->getColumn();
 $indice2=$indice-2;
 $formula='=SUM('.$letra.'2:'.$letra.$indice2.')/'.$letra.'5';
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $indice+1,$formula );
 //echo $sectorNombre.":".$formula."<br>";
  
 
  
 //FORMATO PORCENTAJE SIN DECIMALES:
 $objPHPExcel->getActiveSheet()->getStyle($letra.($indice+1))->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
 //NEGRITA PARA PORCENTAJES:
 $objPHPExcel->getActiveSheet()->getStyle($letra.($indice+1))->applyFromArray(array("font" => array( "bold" => true)));
 
 //ANCHO DE COLUMNA:
 $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(12);
 }
 
 
  
//<editor-fold>
    for ($i = 1; $i < $n_campos; ++$i) {
        $letra=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 5)->getColumn();
        $valorcelda=0;

        $color='FFFFFF';
        
        $valor2=$objPHPExcel->getActiveSheet()->getCell($letra.'2')->getValue();
        $valor3=$objPHPExcel->getActiveSheet()->getCell($letra.'3')->getValue();
        $valor4=$objPHPExcel->getActiveSheet()->getCell($letra.'4')->getValue();
        
        //Se calcula manualmente el porcentaje:
        //(Correctas+Incorrectas) / (Total Capsulas Enviadas)
        
        $valorcelda=($valor2+$valor3)/($valor2+$valor3+$valor4);
        
        // como da decimal, se multiplica por 100, para obtener el porcentaje:
        $valorcelda=$valorcelda*100;
        
        //echo $sectorNombre.":".$valorcelda."<br>";

        $queryColores 	= "EXEC CapXLSColores ".$valorcelda."";
        $resultColores = $base_datos->sql_query($queryColores);
        $rowColores	= $base_datos->sql_fetch_assoc($resultColores);
        $color=$rowColores['color'] ? $rowColores['color'] : 'FFFFFF';
        mssql_free_result($resultColores);

        $colorletras="000000";
        
        if($color<>"FFFF00"){
            $colorletras="FFFFFF";
        }
        
        $colores = array(
                        'font' => array(
                            'color' => array('rgb'=>$colorletras),
                	),
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb'=>$color),
                        )
            
            );

        $objPHPExcel->getActiveSheet()->getStyle($letra.'6')->applyFromArray($colores);
 
 }
 
 

 
 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(24);
 
  $styleArray = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '0000000'),
		),
	),
);
 
 
 $objPHPExcel->getActiveSheet()->getStyle("A1:".$letra.'6')->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->setShowGridlines(false); 
 
 //</editor-fold>
 
 //<editor-fold>
 //Leyenda del semaforo para todos los Reportes:
 
 $objPHPExcel->getActiveSheet()->getStyle("B9:B11")->applyFromArray($styleArray);
 
 
  $styleArray = array(
	'borders' => array(
		'vertical' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '0000000'),
		),
	),
);
  
 $objPHPExcel->getActiveSheet()->getStyle("A9:A11")->applyFromArray($styleArray);
 
 
  $styleArray = array(
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '0000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle("A9")->applyFromArray($styleArray);


  $styleArray = array(
	'borders' => array(
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '0000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle("A11")->applyFromArray($styleArray);


 
  $objPHPExcel->getActiveSheet()->setCellValue('A9',' ');
  $objPHPExcel->getActiveSheet()->setCellValue('A10','Estándar');
  $objPHPExcel->getActiveSheet()->setCellValue('A11',' ');
 
 
$querySem="select REPLACE(COLOR,'#','') AS COLOR,valor_desde,valor_hasta from Indicadores_Semaforo where indicador='CAP'";
$resultSem = mssql_query($querySem);
$indice=9;
$a=0;
$minimo=0;
 while ($rowSem = mssql_fetch_array($resultSem)) {
    $a=$a+1;
    $color=$rowSem[0];
    
    
        $colorletras="000000";
        
        if($color<>"FFFF00"){
            $colorletras="FFFFFF";
        }
    
    
    $colores = array(    
                        'font' => array(
                            'bold'=>true,
                            'color' => array('rgb'=>$colorletras),
                	),
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb'=>$color),
                        ));

    $objPHPExcel->getActiveSheet()->getStyle('B'.$indice)->applyFromArray($colores);
    //$objPHPExcel->getActiveSheet()->setCellValue('A'.$indice,utf8_encode($rowSem[0]).'%');
    //texto=""
    if($a==2){
        $minimo=$rowSem[1];
    }
    
    if($a<3){
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$indice,utf8_encode($rowSem[1]).'% a '.utf8_encode($rowSem[2]).'%');
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$indice,'Menor a '.$minimo.'%');
    }
    //$objPHPExcel->getActiveSheet()->setCellValue('C'.$indice,utf8_encode($rowSem[2]).'%');
    
    $indice=$indice+1;
}
//</editor-fold> 
 
 
// GUARDA EL ARCHIVO
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save($fileName2);
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
?>
