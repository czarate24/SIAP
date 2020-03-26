<?php

error_reporting(E_ALL);
include_once 'Classes/PHPExcel.php';

include "conexion.php";
define("DATABASE", "coop");
$global = $_REQUEST['global'];
 if ($global=='true'){
 	$texto = "IMPRESION GLOBAL";
 }else{
 	$texto = "IMPRESION CON DIFERENCIAS";
 }
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


$objSheet->mergeCells('M1:O6');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath('bachillerato_anahuac.png');
$objDrawing->setCoordinates('M1');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objSheet);

$objSheet->mergeCells('C2:L2');
$objSheet->setCellValue('C2', 'COMPARATIVA DE INVENTARIO FISICO VS KARDEX');
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
$objSheet->mergeCells('C3:L3');
$objSheet->setCellValue('C3', 'INVENTARIO FISICO '.date('F j, Y'));
$objSheet->mergeCells('C4:L4');
$objSheet->setCellValue('C4', $texto);
$objSheet->mergeCells('B1:L1');
$objSheet->mergeCells('B5:L6');
//$objSheet->setCellValue('C4', 'TIPO');
//$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);

$objSheet->mergeCells('A8:G8');
$objSheet->mergeCells('H8:I8');
$objSheet->setCellValue('H8', 'KARDEX');
$objSheet->mergeCells('J8:K8');
$objSheet->setCellValue('J8', 'FISICO');
$objSheet->mergeCells('L8:M8');
$objSheet->setCellValue('L8', 'FALTANTE');
$objSheet->mergeCells('N8:O8');
$objSheet->setCellValue('N8', 'SOBRANTE');

$objSheet->mergeCells('D9:G9');
//$objSheet->mergeCells('Q9:R9');

$objSheet->setCellValue('A9', 'CONSECUTIVO')
->setCellValue('B9', 'GRUPO')
->setCellValue('C9', 'CODIGO DE BARRAS')
->setCellValue('D9', 'ARTICULO')
->setCellValue('H9', 'EXISTENCIA')
->setCellValue('I9', 'COSTO')
->setCellValue('J9', 'EXISTENCIA')
->setCellValue('K9', 'COSTO')
->setCellValue('L9', 'EXISTENCIA')
->setCellValue('M9', 'COSTO')
->setCellValue('N9', 'EXISTENCIA')
->setCellValue('O9', 'COSTO');


		 // ->setCellValue('L9', '70')
		 // ->setCellValue('K9', '80')
		 // ->setCellValue('L9', '90')
		 // ->setCellValue('M9', '100')
		 // ->setCellValue('N9', 'CANT')
		 // ->setCellValue('O9', '%')
		 // ->setCellValue('P9', 'CANT')
		 // ->setCellValue('Q9', '%')
		 // ->setCellValue('R9', 'NO INSCRITOS');


$objPHPExcel->getActiveSheet()->getStyle('A9:H9')->getFont()->setBold(true);


$numero = 9;


$global = $_REQUEST['global'];

        if ($global=='true'){
            $cond = "art.sta<>'B'";
        }
        else {
            $cond = "art.sta<>'B' and (art.exi_fis<>art.existe OR art.cto_fis<> art.costo)";
         } 


$sql = $mysqli->query("SELECT  art.id AS id_articulo,
        art.grupo,
        gpo.descrip AS gpo_descrip,
        art.cod_barr,
        art.descrip,
        art.marca,
        art.existe,
        art.costo,
        IF(art.exi_fis>art.existe,art.exi_fis-art.existe,0) AS faltante_exi,
        IF(art.cto_fis>art.costo,art.cto_fis-art.costo,0) AS faltante_cto,
        IF(art.exi_fis<art.existe,art.existe-art.exi_fis,0) AS sobrante_exi,
        IF(art.cto_fis<art.costo,art.costo-art.cto_fis,0) AS sobrante_cto,
        IF(art.exi_fis = art.existe and art.costo=art.cto_fis,0,1) as diferencia, 
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
        WHERE ".$cond." 
        ORDER BY gpo_descrip,descrip");



while ($a_sql = mysqli_fetch_array($sql)) {

	$numero++;


	$objSheet->mergeCells('D'.$numero.':G'.$numero.'');
	$objSheet->setCellValue('A'.$numero, $a_sql['consec']);
	$objSheet->setCellValue('B'.$numero, $a_sql['gpo_descrip']);
	$objSheet->setCellValue('C'.$numero, $a_sql['codigob']);
	$objSheet->setCellValue('D'.$numero, utf8_decode($a_sql['descrip']));
	$objSheet->setCellValue('H'.$numero, $a_sql['existe']);
	$objSheet->setCellValue('I'.$numero, $a_sql['costo']);
	$objSheet->setCellValue('J'.$numero, $a_sql['exi_fis']);
	$objSheet->setCellValue('K'.$numero, $a_sql['cto_fis']);
	$objSheet->setCellValue('L'.$numero, $a_sql['faltante_exi']);
	$objSheet->setCellValue('M'.$numero, $a_sql['faltante_cto']);
	$objSheet->setCellValue('N'.$numero, $a_sql['sobrante_exi']);
	$objSheet->setCellValue('O'.$numero, $a_sql['sobrante_cto']);

	


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
$objSheet->mergeCells('A7:O7');


$objPHPExcel->getActiveSheet()->getStyle('A7:O7')->applyFromArray($header);
$objPHPExcel->getActiveSheet()->getStyle('A8:O9')->applyFromArray($header1);
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

$objPHPExcel->getActiveSheet()->getStyle('A1:O'.$numero)->applyFromArray($contenido);
// $objPHPExcel->getActiveSheet()->getStyle('J3:O3')->applyFromArray($contenido);
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