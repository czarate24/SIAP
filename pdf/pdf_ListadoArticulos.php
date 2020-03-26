
<?php
require('fpdf/fpdf-1.8.php');
include "conexion.php";

define("DATABASE", 'coop'); //El nombre de la base de datos.
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
		$this->Cell($pageWidth-100,10,utf8_decode('LISTADO DE ARTICULOS '),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('INVENTARIO FISICO '.date('F j, Y')),0,0,'L',false);
				 // Salto de línea
		$this->Ln(14);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('CONSECUTIVO')+6;
		$x2 = $this->GetStringWidth('GRUPO')+30;
		$x3 = $this->GetStringWidth('CODIGO DE BARRAS')+8;
		$x4 = $this->GetStringWidth('ARTICULO')+40;
		$x5 = $this->GetStringWidth('CONTEO')+2;
		$x_aux = $x1+6+$x2+6+$x3+35+$x4+10+$x5+2;
		$this->Ln(1);


		$this->Cell($x1,15,'CONSECUTIVO',0,0,'L',false);
		$this->Cell($x2,15,utf8_decode('GRUPO'),0,0,'L',false);
		$this->Cell($x3,15,'CODIGO DE BARRAS',0,0,'L',false);
		$this->Cell($x4,15,'ARTICULO',0,0,'L',false);
		$this->Cell($x5,15,'CONTEO',0,0,'L',false);
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

$pdf ->AddPage('P','Letter');

$x1 = $pdf->GetStringWidth('RAZON SOCIAL')+6;
$x2 = $pdf->GetStringWidth('CALLE')+30;
$x3 = $pdf->GetStringWidth('COLONIA')+8;
$x4 = $pdf->GetStringWidth('TELEFONOS')+40;
$x5 = $pdf->GetStringWidth('MATRICULA')+2;
 

$sql = $mysqli->query("SELECT insdatos.razon_social, 
			insdatos.calle, 
			insdatos.colonia, 
			ins.telefonos, 
			alum.matricula, 
			alum.nombre, 
			alum.ap_paterno, 
			alum.ap_materno, 
			alum.grado, 
			alum.grupo, 
			cins.referencia
			FROM cxcandes.instituciones_datos AS insdatos 
			LEFT JOIN sistema_andes.instituciones AS ins
			ON ins.id_institucion=insdatos.id_institucion
			LEFT JOIN sistema_andes.alumnos AS alum
			ON alum.id_institucion=ins.id_institucion
			LEFT JOIN cxcandes.carnets_inscripcion AS cins
			ON alum.id_alumno=cins.id_alumno
			");
		$line = "___________________";


	
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		
		$pdf->Cell($x1,15,rtrim($r_sql['razon_social'], " "),0,0,'C',false);
		$pdf->Cell($x2,15,rtrim($r_sql['calle']," "),0,0,'L',false);
		$pdf->Cell($x3,15,rtrim($r_sql['colonia']," "),0,0,'L',false);
		$pdf->Cell($x4,15,rtrim($r_sql['telefonos']," "),0,0,'L',false);
		$pdf->Cell($x5,15,$line,0,0,'L',false);
		



		$pdf->Ln(3); 
	} 
} 


$pdf->Output();

?>