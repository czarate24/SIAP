<?php

error_reporting(E_ALL);
include_once 'Classes/PHPExcel.php';

include "conexion.php";
define("DATABASE", "coop");
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
$zonahoraria = date_default_timezone_get();

$objPHPExcel = new PHPExcel();
$objSheet = $objPHPExcel->setActiveSheetIndex(0);


$objSheet->mergeCells('A1:B6');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('logoandes.png');
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objSheet);


$objSheet->mergeCells('F1:H6');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('bachillerato_anahuac.png');
$objDrawing->setCoordinates('F1');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objSheet);

$objSheet->mergeCells('C2:G2');
$objSheet->setCellValue('C2', 'LISTADO DE ARTICULOS');
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objSheet->mergeCells('C3:G3');
$objSheet->setCellValue('C3', 'INVENTARIO FISICO '.date('F j, Y'));
$objSheet->mergeCells('B1:G1');
$objSheet->mergeCells('B4:G6');

$objSheet->mergeCells('A8:H8');
$objSheet->mergeCells('D9:G9');


$objSheet->setCellValue('A9', 'CONSECUTIVO')
->setCellValue('B9', 'GRUPO')
->setCellValue('C9', 'CODIGO DE BARRAS')
->setCellValue('D9', 'ARTICULO')
->setCellValue('H9', 'CONTEO');

$objPHPExcel->getActiveSheet()->getStyle('A9:H9')->getFont()->setBold(true);

$numero = 9;
$sql = $mysqli->query("SELECT  art.id AS id_articulo,
        art.grupo,
        gpo.descrip AS gpo_descrip,
        art.cod_barr,
        art.descrip,
        art.marca,
        art.existe,
        art.costo,
        art.pvta,
        art.fec_alta,
        art.sta,
        IF(art.sta='B','INACTIVO','ACTIVO') AS sta_descrip,
        art.exi_fis,
        art.cto_fis,
        art.uni_med,
        art.f_cant,
        art.f_costo,
        art.cont_fis,
        art.codigob,
        art.consec
        FROM inv_fis AS art
        INNER JOIN grupo AS gpo
        ON art.grupo=gpo.grupo
        WHERE art.sta<>'B'
        ORDER BY gpo_descrip,descrip");


while ($a_sql = mysqli_fetch_array($sql)) {

	$numero++;
	$objSheet->mergeCells('D'.$numero.':G'.$numero.'');
	$objSheet->setCellValue('A'.$numero, $a_sql['consec']);
	$objSheet->setCellValue('B'.$numero, $a_sql['gpo_descrip']);
	$objSheet->setCellValue('C'.$numero, $a_sql['codigob']);
	$objSheet->setCellValue('D'.$numero, utf8_decode($a_sql['descrip']));
	


	$derecha = array(
		'alignment'=>array(
			'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
			)
		);

	$objPHPExcel->getActiveSheet()->getStyle('B'.$numero)->applyFromArray($derecha);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$numero)->getFont()->setBold(true); 


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
$objSheet->mergeCells('A7:H7');


$objPHPExcel->getActiveSheet()->getStyle('A7:H7')->applyFromArray($header);
$objPHPExcel->getActiveSheet()->getStyle('A8:H9')->applyFromArray($header1);

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

$objPHPExcel->getActiveSheet()->getStyle('A1:H'.$numero)->applyFromArray($contenido);
// //Terminan estilos contenido



//Cambiar tamaño a columna
for($numero = 'D'; $numero <= 'P'; $numero++){
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($numero)->setAutoSize(TRUE);
}


$fecha = date('Ymd_Hi');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$fecha.'.xls"');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>