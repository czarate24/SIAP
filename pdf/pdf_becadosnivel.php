
<?php
require('fpdf/fpdf-1.8.php');
include "conexion.php";

class PDF extends FPDF
{

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
$pdf->AddPage('L','Legal');


$x1 = $pdf->GetStringWidth('NIVEL')+20;
$x2 = $pdf->GetStringWidth('10%')+2;
$x3 = $pdf->GetStringWidth('100%')+2;
$x32 = ($x2 * 10) + $x3 + $x1;
$x4 = $pdf->GetStringWidth('CANTIDAD')+2;
$x5 = $pdf->GetStringWidth('PORCENTAJE')+2;
$x6 = $pdf->GetStringWidth('TOTAL INSCRITOS')+2;

$pdf->SetFont('Arial','B',12);
$pageWidth = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight()/2;
$pdf->Image('logoandes.png',$pdf->GetX(),$pdf->GetY(),20);
$pdf->Image('bachillerato_anahuac.png',$pageWidth-50,$pdf->GetY(),30);

		// $pdf->Ln(25);
		// $pdf->Rect($pdf->GetX(),$pdf->GetY(),$pageWidth-20,20);
$pdf->Cell(20,10,'',0,0,'L',false);
$pdf->Cell($pageWidth-100,10,utf8_decode('REPORTE DE ALUMNOS BECADOS POR NIVEL'),0,0,'L',false);
$pdf->Ln(5);	
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,10,'',0,0,'L',false);
$pdf->Cell($pageWidth-100,10,utf8_decode('CICLO ESCOLAR 2017 - 2018 '),0,0,'L',false);
    // Salto de línea
$pdf->Ln(14);

		// $pdf->Rect($pdf->GetX(),$pdf->GetY(),$pageWidth-20,$pageHeight+45);
mysqli_close($mysqli);
		define("DATABASE", 'becas'); //El nombre de la base de datos.
		$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
		$sql = $mysqli->query("CALL becasnivel_select()");
		
		$pdf->SetFont('Arial','B',7);
		$x1 = $pdf->GetStringWidth('NIVEL')+22;
		$x2 = $pdf->GetStringWidth('10%')+2;
		$x3 = $pdf->GetStringWidth('100%')+2;
		$x32 = ($x2 * 10) + $x3 + $x1;
		$x4 = $pdf->GetStringWidth('CANTIDAD')+2;
		$x5 = $pdf->GetStringWidth('PORCENTAJE')+2;
		$x6 = $pdf->GetStringWidth('TOTAL INSCRITOS')+2;
		
		$pdf->Ln(1);

		mysqli_close($mysqli);
		define("DATABASE", "becas");
		$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

		$s_sqlporcentaje = $mysqli->query("SELECT DISTINCT ROUND((porc_actual + porc_prestacion),0 ) AS porcentaje FROM becas WHERE (porc_actual + porc_prestacion) != 0 GROUP BY matricula ORDER BY porcentaje");

		$pdf->Cell($x1,15,'NIVEL',0,0,'L',false);
		$consulta_porc = "SELECT DISTINCT '   NIVEL   ' as nivel";
		while($porc = mysqli_fetch_array($s_sqlporcentaje)){

			$x2 = $pdf->GetStringWidth($porc['porcentaje']) + 2;
			$pdf->Cell($x2,15,$porc['porcentaje'],0,0,'R',false);

		}


		

		$pdf->Cell($x4,15,'',0,0,'L',false);
		// $pdf->Cell($x4,0,'ALUMNOS BECADOS',0,0,'L',false);
		$pdf->text($pdf->GetX()+1,$pdf->GetY()+5, 'ALUMNOS BECADOS');
		$pdf->Cell($x4,15,'CANTIDAD',0,0,'L',false);
		$pdf->Cell($x5,15,'PORCENTAJE',0,0,'L',false);
		$pdf->Cell($x4,15,'',0,0,'L',false);
		$pdf->text($pdf->GetX()+1,$pdf->GetY()+5, 'ALUMNOS NO BECADOS');
		$pdf->Cell($x4,15,'CANTIDAD',0,0,'L',false);
		$pdf->Cell($x5,15,'PORCENTAJE',0,0,'L',false);
		$pdf->Cell($x4,15,'',0,0,'L',false);
		$pdf->Cell($x6,15,'TOTAL INSCRITOS',0,0,'L',false);

		$pdf->Ln(5);
		$pdf->Line(10, 40,$pageWidth-10, 40);

		$pdf->SetFont('Arial','',7);

		mysqli_close($mysqli);
		define("DATABASE", 'becas'); //El nombre de la base de datos.
		$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
		$sql = $mysqli->query("CALL becasnivel_select()");
		if($sql->num_rows > 0){
			while($r_sql = mysqli_fetch_array($sql)){

				$pdf->SetFont('Arial','B',7);
				$pdf->Cell($x1,15,rtrim($r_sql['descripcion'], " "),0,0,'L',false);
				$pdf->SetFont('Arial','',7);

				mysqli_close($mysqli);
				define("DATABASE", "becas");
				$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

				$s_sqlporcentaje = $mysqli->query("SELECT DISTINCT ROUND((porc_actual + porc_prestacion),0 ) AS porcentaje FROM becas WHERE (porc_actual + porc_prestacion) != 0 GROUP BY matricula ORDER BY porcentaje");

				while($sql_porc = mysqli_fetch_array($s_sqlporcentaje)){
					$x2 = $pdf->GetStringWidth($sql_porc['porcentaje']) + 2;
					$pdf->Cell($x2,15,$r_sql['porc_'.$sql_porc['porcentaje']],0,0,'R',false);
				}


				$pdf->Cell($x4,15,'',0,0,'L',false);
				$pdf->Cell($x4,15,$r_sql['cant_becados'],0,0,'R',false);
				$pdf->Cell($x5,15,$r_sql['porc_becados'],0,0,'R',false);

				$pdf->Cell($x4,15,'',0,0,'L',false);
				$pdf->Cell($x4,15,$r_sql['cant_nobecados'],0,0,'R',false);
				$pdf->Cell($x5,15,$r_sql['porc_nobecados'],0,0,'R',false);

				$pdf->Cell($x4,15,'',0,0,'L',false);
				$pdf->Cell($x6,15,$r_sql['total_noinsc'],0,0,'R',false);



				$pdf->Ln(3);
			}
		}


		$pdf->Output();

		?>