
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
		$this->Cell($pageWidth-100,10,utf8_decode('REPORTE DE ALUMNOS BECADOS POR ORIGEN'),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('CICLO ESCOLAR 2017 - 2018 '),0,0,'L',false);
    // Salto de línea
		$this->Ln(14);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('ORIGEN')+30;
		$x2 = $this->GetStringWidth('MATERNAL')+2;
		$x3 = $this->GetStringWidth('KINDER')+2;
		$x4 = $this->GetStringWidth('TRANSITORIO')+2;
		$x5 = $this->GetStringWidth('PREPRIMARIA')+2;
		$x6 = $this->GetStringWidth('PRIMARIA')+2;
		$x7 = $this->GetStringWidth('SECUNDARIA')+2;
		$x8 = $this->GetStringWidth('BACHILLERATO')+2;
		$x10 = $this->GetStringWidth('BECADOS')+2;

		$widthTitulo = $x1 + $x2 + $x3 + $x4 + $x5 + $x6 + $x7 + $x8;
		$widthTitulo1 = $x10 + $x11 + $x12;

		// $this->Line($this->GetX(), $this->GetY()+5,160, $this->GetY()+5);
		$this->Cell($widthTitulo+30,5,'BECAS POR NIVEL',0,0,'C',false);
		$this->Line($this->GetX()-$widthTitulo+$x1-30, $this->GetY()+5,170, $this->GetY()+5);

		// $this->Cell($widthTitulo1-60,5,'CICLO 2017 - 2018',0,0,'C',false);
		// $this->Line($this->GetX()-$widthTitulo1+30, $this->GetY()+5,163, $this->GetY()+5);
		$this->Ln(1);


		$this->Cell($x1,15,'ORIGEN',0,0,'L',false);
		$this->Cell($x2,15,'MATERNAL',0,0,'L',false);
		$this->Cell($x3,15,'KINDER',0,0,'L',false);
		$this->Cell($x4,15,'TRANSITORIO',0,0,'L',false);
		$this->Cell($x5,15,'PREPRIMARIA',0,0,'L',false);
		$this->Cell($x6,15,'PRIMARIA',0,0,'L',false);
		$this->Cell($x7,15,'SECUNDARIA',0,0,'L',false);
		$this->Cell($x8,15,'BACHILLERATO',0,0,'L',false);
		$this->Cell($x10,15,'BECADOS',0,0,'L',false);

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

$x1 = $pdf->GetStringWidth('ORIGEN')+30;
$x2 = $pdf->GetStringWidth('MATERNAL')+2;
$x3 = $pdf->GetStringWidth('KINDER')+2;
$x4 = $pdf->GetStringWidth('TRANSITORIO')+2;
$x5 = $pdf->GetStringWidth('PREPRIMARIA')+2;
$x6 = $pdf->GetStringWidth('PRIMARIA')+2;
$x7 = $pdf->GetStringWidth('SECUNDARIA')+2;
$x8 = $pdf->GetStringWidth('BACHILLERATO')+2;
$x10 = $pdf->GetStringWidth('BECADOS')+2;

$pdf->SetFont('Arial','',7);
$direccion = $_REQUEST['direccion'];
$idcolumna = $_REQUEST['idcolumna'];
$sql = $mysqli->query("CALL origenbeca_select()");
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell($x1,15,rtrim($r_sql['empresa'], " "),0,0,'L',false);
		$pdf->SetFont('Arial','',7);

		if($r_sql['maternal'] != 0){$pdf->Cell($x2,15,$r_sql['maternal'],0,0,'C',false);}else{$pdf->Cell($x2,15,'',0,0,'C',false);}
		if($r_sql['transitorio'] != 0){$pdf->Cell($x3,15,$r_sql['transitorio'],0,0,'C',false);}else{$pdf->Cell($x3,15,'',0,0,'C',false);}
		if($r_sql['kinder'] != 0){$pdf->Cell($x4,15,$r_sql['kinder'],0,0,'R',false);}else{$pdf->Cell($x4,15,'',0,0,'C',false);}
		if($r_sql['preprimaria'] != 0){$pdf->Cell($x5,15,$r_sql['preprimaria'],0,0,'C',false);}else{$pdf->Cell($x5,15,'',0,0,'C',false);}
		if($r_sql['primaria'] != 0){$pdf->Cell($x6,15,$r_sql['primaria'],0,0,'C',false);}else{$pdf->Cell($x6,15,'',0,0,'C',false);}
		if($r_sql['secundaria'] != 0){$pdf->Cell($x7,15,$r_sql['secundaria'],0,0,'C',false);}else{$pdf->Cell($x7,15,'',0,0,'C',false);}
		if($r_sql['bachillerato'] != 0){$pdf->Cell($x8,15,$r_sql['bachillerato'],0,0,'C',false);}else{$pdf->Cell($x8,15,'',0,0,'C',false);}
		if($r_sql['alumnosbecados'] != 0){$pdf->Cell($x10,15,$r_sql['alumnosbecados'],0,0,'C',false);}else{$pdf->Cell($x10,15,'',0,0,'C',false);}

		$pdf->Ln(3);
	}
}


$pdf->Output();

?>