<?php

error_reporting(E_ALL);
include_once 'Classes/PHPExcel.php';

include "conexion.php";
define("DATABASE", "becas");
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$zonahoraria = date_default_timezone_get();

$objPHPExcel = new PHPExcel();
$objSheet = $objPHPExcel->setActiveSheetIndex(0);


$objSheet->mergeCells('A1:B6');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('logoandes.png');
$objDrawing->setCoordinates('A2');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objSheet);


$objSheet->mergeCells('H1:K6');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('bachillerato_anahuac.png');
$objDrawing->setCoordinates('I2');
$objDrawing->setHeight(80);
$objDrawing->setWorksheet($objSheet);

$objSheet->mergeCells('C2:G2');
$objSheet->setCellValue('C2', 'REPORTE DE CONTROL PRESUPUESTAL DE BECAS');
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objSheet->mergeCells('C3:G3');
$objSheet->setCellValue('C3', 'CICLO ESCOLAR 2017 - 2018');
$objSheet->mergeCells('B1:H1');
$objSheet->mergeCells('B4:H6');

$objSheet->mergeCells('A8:C8');

$objSheet->mergeCells('E8:G8');
$objSheet->setCellValue('E8', 'ALUMNOS');

$objSheet->mergeCells('H8:J8');
$objSheet->setCellValue('H8', 'INGRESOS');

$objSheet->mergeCells('A9:C9');
$objSheet->setCellValue('A9', 'NIVEL')
		 ->setCellValue('D9', 'COLEGIATURA')
		 ->setCellValue('E9', 'INSCRITOS')
		 ->setCellValue('F9', 'SIN BECA')
		 ->setCellValue('G9', 'CON BECA')
		 ->setCellValue('H9', 'BRUTOS')
		 ->setCellValue('I9', 'BECAS')
		 ->setCellValue('J9', 'NETOS')
		 ->setCellValue('K9', '% BECA');


$objPHPExcel->getActiveSheet()->getStyle('A9:K9')->getFont()->setBold(true);


$numero = 9;


$s_sql= $mysqli->query("CALL presupuestobecas_select()");



while ($a_sql = mysqli_fetch_array($s_sql)) {

	$numero++;
	$objSheet->mergeCells('A'.$numero.':C'.$numero.'');
	$objSheet
	->setCellValue('A'.$numero, $a_sql['descripcion'])
	->setCellValue('D'.$numero, $a_sql['colegiatura'])
	->setCellValue('E'.$numero, $a_sql['total_inscritos'])
	->setCellValue('F'.$numero, $a_sql['cant_sinbecas'])
	->setCellValue('G'.$numero, $a_sql['cant_becas'])
	->setCellValue('H'.$numero,$a_sql['ing_bruto'])
	->setCellValue('I'.$numero, $a_sql['dinero_beca'])
	->setCellValue('J'.$numero, $a_sql['ing_neto'])
	->setCellValue('K'.$numero, $a_sql['porcentaje']);

	$objPHPExcel->getActiveSheet()->getStyle('A'.$numero)->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('D'.$numero)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet()->getStyle('H'.$numero)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet()->getStyle('I'.$numero)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet()->getStyle('J'.$numero)->getNumberFormat()->setFormatCode('#,##0.00');




}






$header = array(
	'font'=>array(
		'name'=>'Consolas',
		'bold'=>true,
		'size'=>12),
	'alignment'=>array(
		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
		),
	'fill'=>array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array(
			'rgb'=>'28CA1C')
		),
	'borders'=>array(
		'allborders'=>array(
			'style'=> PHPExcel_Style_Border::BORDER_THIN,
			'color'=>array(
				'rgb'=>'3a2a47')
			)
		)
	);

$header1 = array(
	'font'=>array(
		'name'=>'Consolas',
		'bold'=>true,
		'size'=>12),
	'alignment'=>array(
		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
		),
	'fill'=>array(
		'type'=>PHPExcel_Style_Fill::FILL_SOLID,
		'color'=>array(
			'rgb'=>'D5AE72')
		),
	'borders'=>array(
		'allborders'=>array(
			'style'=> PHPExcel_Style_Border::BORDER_THIN,
			'color'=>array(
				'rgb'=>'3a2a47')
			)
		)
	);
//Terminan estilos

//Aplicas estilos
$objSheet->mergeCells('A7:K7');


$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->applyFromArray($header);
$objPHPExcel->getActiveSheet()->getStyle('A8:K9')->applyFromArray($header1);
// $objPHPExcel->getActiveSheet()->getStyle('A3:G4')->applyFromArray($header);
// $objPHPExcel->getActiveSheet()->getStyle('J2:O2')->applyFromArray($header);
//Termina aplica estilos

//Estilos contenido
$contenido = array(
	'font'=>array(
		'name'=>'Arial',
		'size'=>10),
	'borders'=>array(
		'allborders'=>array(
			'style'=> PHPExcel_Style_Border::BORDER_THIN,
			'color'=>array(
				'rgb'=>'3a2a47')
			)
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('A1:K'.$numero)->applyFromArray($contenido);
// $objPHPExcel->getActiveSheet()->getStyle('J3:O3')->applyFromArray($contenido);
// //Terminan estilos contenido



//Cambiar tamaño a columna
for($numero = 'D'; $numero <= 'K'; $numero++){
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($numero)->setAutoSize(TRUE);
}


$fecha = date('Ymd_Hi');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$fecha.'.xls"');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>