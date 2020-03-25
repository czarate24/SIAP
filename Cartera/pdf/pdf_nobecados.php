
<?php
require('fpdf/fpdf-1.8.php');
include "conexion.php";

define("DATABASE", 'becas'); //El nombre de la base de datos.
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

class PDF extends FPDF
{
	function Header()
	{
		// global $title;

    // Arial bold 15
		$this->SetFont('Arial','B',12);

		$pageWidth = $this->GetPageWidth();

		$pageHeight = $this->GetPageHeight()/2;
		$this->Image('logoandes.png',$this->GetX(),$this->GetY(),20);
		$this->Image('bachillerato_anahuac.png',$pageWidth-50,$this->GetY(),30);
		
		// $this->Ln(25);
		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,20);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('REPORTE DE ALUMNOS NO BECADOS'),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('CICLO ESCOLAR 2017 - 2018'),0,0,'L',false);
    // Salto de línea
		$this->Ln(14);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('MATRÍCULA');
		$x2 = $this->GetStringWidth('APELLIDO PATERNO')+10;
		$x3 = $this->GetStringWidth('APELLIDO MATERNO')+10;
		$x4 = $this->GetStringWidth('NOMBRE')+30;
		$x5 = $this->GetStringWidth('NIVEL')+10;
		$x6 = $this->GetStringWidth('GRADO')+2;

		$widthTitulo = $x1 + $x2 + $x3 + $x4 + $x5 + $x6 + $x7 + $x8;
		$widthTitulo1 = $x10 + $x11 + $x12;

		// $this->Line($this->GetX(), $this->GetY()+5,160, $this->GetY()+5);
		// $this->Cell($widthTitulo+30,5,'BECAS POR NIVEL',0,0,'C',false);
		// $this->Line($this->GetX()-$widthTitulo+$x1-30, $this->GetY()+5,170, $this->GetY()+5);

		// $this->Cell($widthTitulo1-60,5,'CICLO 2017 - 2018',0,0,'C',false);
		// $this->Line($this->GetX()-$widthTitulo1+30, $this->GetY()+5,163, $this->GetY()+5);
		$this->Ln(1);

		$this->Cell($x1,15,'',0,0,'L',false);
		$this->Cell($x1,15,utf8_decode('MATRÍCULA'),0,0,'L',false);
		$this->Cell($x2,15,'APELLIDO PATERNO',0,0,'L',false);
		$this->Cell($x3,15,'APELLIDO MATERNO',0,0,'L',false);
		$this->Cell($x4,15,'NOMBRE',0,0,'L',false);
		$this->Cell($x5,15,'NIVEL',0,0,'L',false);
		$this->Cell($x6,15,'GRADO',0,0,'L',false);

		$this->Ln(5);
		$this->Line(10, 40,$pageWidth-10, 40);

	}

	function Footer()
	{
    // Posición a 1,5 cm del final
		$this->SetY(-15);
    // Arial itálica 8
		$this->SetFont('Arial','I',8);
    // Color del texto en gris
		$this->SetTextColor(128);
    // Número de página
		$this->Cell(0,10,utf8_decode('Página '.$this->PageNo()),0,0,'C');
	}




}


$pdf = new PDF();
$pdf->AddPage('P','Legal');

$x1 = $pdf->GetStringWidth('MATRÍCULA');
$x2 = $pdf->GetStringWidth('APELLIDO PATERNO')+10;
$x3 = $pdf->GetStringWidth('APELLIDO MATERNO')+10;
$x4 = $pdf->GetStringWidth('NOMBRE')+30;
$x5 = $pdf->GetStringWidth('NIVEL')+10;
$x6 = $pdf->GetStringWidth('GRADO')+2;

$pdf->SetFont('Arial','',7);

$nivel = $_REQUEST['nivel'];
$grado = $_REQUEST['grado'];
$nombre = $_REQUEST['nombre'];
$columna = $_REQUEST['columna'];
$orientacion = $_REQUEST['orientacion'];

$where = "WHERE ISNULL(id_beca) AND fec_baja = '0000-00-00' AND (ctrlesc.alumno.nivel != 'BAC' OR ctrlesc.alumno.grado != 6)";



if($nombre != '' && $nombre != null){

	$where .= "  AND CONCAT(apepat,' ',apemat,' ',alumno.nombre) LIKE '%".$nombre."%' ";	
	
}

if($nivel != '' && $nivel != null){

	$where .= " HAVING cve = '".$nivel."'";
	
}
if($grado != '' && $grado != null){
	if($nivel != '' && $nivel != null){
		$where .= " AND grado = ".$grado;
	}else{
		$where .= " HAVING grado = ".$grado;
	}

	
}

if($columna != '' && $columna != null){
	$orderby = 'ORDER BY '.$columna.' '.$orientacion;
}
else{
	$orderby = 'ORDER BY apepat,apemat,nombre';
}

$sql = $mysqli->query("SELECT CONCAT(LPAD(ctrlesc.alumno.ncontrol,4,'0'),LPAD(ctrlesc.alumno.matricula,2,'0')) AS matricula,apepat,apemat,alumno.nombre,CONCAT(apepat,' ',apemat,' ',alumno.nombre) AS nombre_completo,IF(nuevo != 1,IF(ctrlesc.alumno.grado < 6 AND ctrlesc.nivel.cve = 'PRI','PRIMARIA',IF(ctrlesc.alumno.grado = 6 AND ctrlesc.alumno.nivel = 'PRI','SECUNDARIA',
	IF(ctrlesc.alumno.grado < 3 AND ctrlesc.nivel.cve = 'SEC','SECUNDARIA',
	IF(ctrlesc.alumno.grado = 3 AND ctrlesc.nivel.cve = 'SEC','BACHILLERATO',IF(ctrlesc.nivel.cve = 'MAT','KINDER',IF(ctrlesc.nivel.cve = 'KIN', 'TRANSITORIO',
	IF(ctrlesc.nivel.cve = 'TRA','PREPRIMARIA',IF(ctrlesc.nivel.cve = 'PRE','PRIMARIA',ctrlesc.nivel.descripcion)))))))),ctrlesc.nivel.descripcion) AS nivel,
	IF(nuevo != 1,IF(ctrlesc.alumno.grado < 6 AND ctrlesc.alumno.nivel = 'PRI',ctrlesc.alumno.grado+1,IF(ctrlesc.alumno.grado < 3 AND ctrlesc.alumno.nivel = 'SEC',ctrlesc.alumno.grado+1,
	IF(ctrlesc.alumno.grado < 6 AND ctrlesc.alumno.nivel = 'BAC',ctrlesc.alumno.grado+1,IF(ctrlesc.alumno.nivel != 'EST' AND ctrlesc.alumno.nivel != 'MAT' AND ctrlesc.alumno.nivel != 'TRA' AND 
	ctrlesc.alumno.nivel != 'KIN',1,0)))),ctrlesc.alumno.grado) AS grado,IF(nuevo != 1,IF(ctrlesc.alumno.grado < 6 AND ctrlesc.nivel.cve = 'PRI','PRI',
	IF(ctrlesc.alumno.grado = 6 AND ctrlesc.alumno.nivel = 'PRI','SEC',IF(ctrlesc.alumno.grado < 3 AND ctrlesc.nivel.cve = 'SEC','SEC',
	IF(ctrlesc.alumno.grado = 3 AND ctrlesc.nivel.cve = 'SEC','BAC',IF(ctrlesc.nivel.cve = 'MAT','KIN',IF(ctrlesc.nivel.cve = 'KIN', 'TRA',
	IF(ctrlesc.nivel.cve = 'TRA','PRE',IF(ctrlesc.nivel.cve = 'PRE','PRI',ctrlesc.nivel.cve)))))))),ctrlesc.nivel.cve) AS cve 
	FROM becas
	RIGHT JOIN ctrlesc.alumno ON becas.matricula = CONCAT(LPAD(ctrlesc.alumno.ncontrol,4,'0'),LPAD(ctrlesc.alumno.matricula,2,'0'))
	INNER JOIN ctrlesc.nivel ON ctrlesc.nivel.cve = ctrlesc.alumno.nivel
	".$where."
	" .$orderby."");
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		

		$pdf->Cell($x1,15,'',0,0,'L',false);
		$pdf->Cell($x1,15,rtrim($r_sql['matricula'], " "),0,0,'L',false);
		$pdf->Cell($x2,15,$r_sql['apepat'],0,0,'L',false);
		$pdf->Cell($x3,15,$r_sql['apemat'],0,0,'L',false);
		$pdf->Cell($x4,15,$r_sql['nombre'],0,0,'L',false);
		$pdf->Cell($x5,15,$r_sql['nivel'],0,0,'L',false);
		if($r_sql['grado'] != 0){$pdf->Cell($x6,15,$r_sql['grado'],0,0,'R',false);}else{$pdf->Cell($x6,15,'',0,0,'R',false);}
		$pdf->Ln(3);
	}
}


$pdf->Output();

?>