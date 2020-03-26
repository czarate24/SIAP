
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
		$this->Cell($pageWidth-100,10,utf8_decode('REPORTE DE CONTROL PRESUPUESTAL DE BECAS'),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('CICLO ESCOLAR 2017 - 2018 '),0,0,'L',false);
    // Salto de línea
		$this->Ln(14);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('NIVEL')+20;
		$x2 = $this->GetStringWidth('COLEGIATURA')+2;
		$x3 = $this->GetStringWidth('INSCRITOS')+2;
		$x4 = $this->GetStringWidth('SIN BECA')+2;
		$x43 = $x1 + $x2 + $x3 + ($x4 * 2);
		$x5 = $this->GetStringWidth('MONTO BECAS')+2;
		$x6 = $this->GetStringWidth('INGR. BRUTOS')+2;
		$x7 = $this->GetStringWidth('INGR. NETOS')+2;
		$x8 = $this->GetStringWidth('PORC. BECA')+2;

		$this->Cell($x43+70,5,'ALUMNOS',0,0,'C',false);
		$this->Line($this->GetX()-$x43-13, $this->GetY()+5,110, $this->GetY()+5);
		$this->Ln(1);

		$this->Cell($x1,15,'NIVEL',0,0,'L',false);
		$this->Cell($x2+10,15,'COLEGIATURA',0,0,'L',false);
		$this->Cell($x3,15,'INSCRITOS',0,0,'L',false);
		$this->Cell($x4,15,'SIN BECA',0,0,'L',false);
		$this->Cell($x4+10,15,'CON BECA',0,0,'L',false);
		$this->Cell($x6,15,'INGR. BRUTOS',0,0,'L',false);
		$this->Cell($x5,15,'MONTO BECAS',0,0,'L',false);
		$this->Cell($x7,15,'INGR. NETOS',0,0,'L',false);
		$this->Cell($x8,15,'% BECA',0,0,'R',false);


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
$pdf->AddPage('P','letter');

$x1 = $pdf->GetStringWidth('NIVEL')+20;
$x2 = $pdf->GetStringWidth('COLEGIATURA')+2;
$x3 = $pdf->GetStringWidth('INSCRITOS')+2;
$x4 = $pdf->GetStringWidth('SIN BECA')+2;
$x5 = $pdf->GetStringWidth('MONTO BECAS')+2;
$x6 = $pdf->GetStringWidth('INGR. BRUTOS')+2;
$x7 = $pdf->GetStringWidth('INGR. NETOS')+2;
$x8 = $pdf->GetStringWidth('PORC. BECA')+2;

$pdf->SetFont('Arial','',7);
$direccion = $_REQUEST['direccion'];
$idcolumna = $_REQUEST['idcolumna'];
$aux = $_REQUEST['aux'];
$sql = $mysqli->query("CALL presupuestobecas_select(".$aux.")");
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell($x1,15,rtrim($r_sql['descripcion'], " "),0,0,'L',false);
		$pdf->SetFont('Arial','',7);
		if($r_sql['colegiatura'] != 0){$pdf->Cell($x2,15,number_format($r_sql['colegiatura'],2),0,0,'R',false);}else{$pdf->Cell($x2,15,'',0,0,'R',false);}
		if($r_sql['total_inscritos'] != 0){$pdf->Cell($x3+10,15,$r_sql['total_inscritos'],0,0,'R',false);}else{$pdf->Cell($x3+10,15,'',0,0,'R',false);}
		if($r_sql['cant_sinbecas'] != 0){$pdf->Cell($x4,15,$r_sql['cant_sinbecas'],0,0,'R',false);}else{$pdf->Cell($x4,15,'',0,0,'R',false);}
		if($r_sql['cant_becas'] != 0){$pdf->Cell($x4+10,15,$r_sql['cant_becas'],0,0,'C',false);}else{$pdf->Cell($x4+10,15,'',0,0,'C',false);}
		if($r_sql['ing_bruto'] * -1 != 0){$pdf->Cell($x6,15,number_format($r_sql['ing_bruto'], 2),0,0,'R',false);}else{$pdf->Cell($x6,15,'',0,0,'R',false);}
		if($r_sql['dinero_beca'] != 0){$pdf->Cell($x5,15,$r_sql['dinero_beca'],0,0,'R',false);}else{$pdf->Cell($x5,15,'',0,0,'R',false);}
		if($r_sql['ing_neto'] != 0){$pdf->Cell($x7,15,number_format($r_sql['ing_neto'],2),0,0,'R',false);}else{$pdf->Cell($x7,15,'',0,0,'R',false);}
		if($r_sql['porcentaje'] != 0){$pdf->Cell($x8,15,number_format($r_sql['porcentaje'],2),0,0,'R',false);}else{$pdf->Cell($x8,15,'',0,0,'R',false);}

		$pdf->Ln(3);
	}
}


$pdf->Output();

?>