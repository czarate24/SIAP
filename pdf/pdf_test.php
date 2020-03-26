
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
		$this->Cell($pageWidth-100,10,utf8_decode('REPORTE GENERAL DE SOLICITUD DE BECAS '),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('CICLO ESCOLAR 2017 - 2018 '),0,0,'L',false);
    // Salto de línea
		$this->Ln(14);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('FOLIO')+6;
		$x2 = $this->GetStringWidth('MAT')+6;
		$x3 = $this->GetStringWidth('NOMBRE DEL ALUMNO')+35;
		$x4 = $this->GetStringWidth('NIVEL')+10;
		$x5 = $this->GetStringWidth('GRADO')+2;
		$x6 = $this->GetStringWidth('ORIGEN DE BECA')+2;
		$x7 = $this->GetStringWidth('% ANT')+2;
		$x8 = $this->GetStringWidth('SOL')+2;
		$x9 = $this->GetStringWidth('TIPO DE BECA')+14;
		$x10 = $this->GetStringWidth('ACA')+2;
		$x11 = $this->GetStringWidth('CON')+4;
		$x12 = $this->GetStringWidth('ESTATUS')+10;
		$x13 = $this->GetStringWidth('% AUT')+2;
		$x14 = $this->GetStringWidth('% PREST')+2;
		$x15 = $this->GetStringWidth('MOTIVO')+2;
		$x_aux = $x1+6+$x2+6+$x3+35+$x4+10+$x5+2+$x6+2+$x7+2+$x8+14+$x9+$x10+$x11+4;
		$this->Cell($x_aux-15,5,'CICLO ANTERIOR',0,0,'C',false);
		$this->Cell(-150,5,'PROMEDIOS',0,0,'C',false);
		$this->Ln(1);


		$this->Cell($x1,15,'FOLIO',0,0,'L',false);
		$this->Cell($x2,15,utf8_decode('MAT'),0,0,'L',false);
		$this->Cell($x3,15,'NOMBRE DEL ALUMNO',0,0,'L',false);
		$this->Cell($x4,15,'NIVEL',0,0,'L',false);
		$this->Cell($x5,15,'GRADO',0,0,'L',false);

		// $this->Rect($this->GetX(),$this->GetY(),$x6+$x7,10);
		$this->Cell($x6,15,'ORIGEN DE BECA',0,0,'L',false);
		$this->Line(127, $this->GetY()+5,160, $this->GetY()+5);
		$this->Cell($x7,15,'% ANT',0,0,'L',false);
		$this->Cell($x8,15,'SOL',0,0,'L',false);
		$this->Cell($x9,15,'TIPO DE BECA',0,0,'L',false);
		// $this->Rect($this->GetX(),$this->GetY(),$x13+$x14-7,10);
		$this->Line($this->GetX(), $this->GetY()+5,213, $this->GetY()+5);
		$this->Cell($x10,15,'ACA',0,0,'L',false);
		$this->Cell($x11,15,'CON',0,0,'L',false);
		$this->Cell($x12,15,'ESTATUS',0,0,'L',false);
		$this->Cell($x13,15,'% AUT',0,0,'L',false);
		$this->Cell($x14,15,'% PREST',0,0,'L',false);
		$this->Cell($x15,15,'MOTIVO',0,0,'C',false);
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
$pdf->AddPage('L','Legal');

$x1 = $pdf->GetStringWidth('FOLIO')+6;
$x2 = $pdf->GetStringWidth('MAT')+6;
$x3 = $pdf->GetStringWidth('NOMBRE DEL ALUMNO')+35;
$x4 = $pdf->GetStringWidth('NIVEL')+10;
$x5 = $pdf->GetStringWidth('GRADO')+2;
$x6 = $pdf->GetStringWidth('ORIGEN DE BECA')+2;
$x7 = $pdf->GetStringWidth('% ANT')+2;
$x8 = $pdf->GetStringWidth('SOL')+2;
$x9 = $pdf->GetStringWidth('TIPO DE BECA')+14;
$x10 = $pdf->GetStringWidth('ACA')+2;
$x11 = $pdf->GetStringWidth('CON')+4;
$x12 = $pdf->GetStringWidth('ESTATUS')+10;
$x13 = $pdf->GetStringWidth('% AUT')+2;
$x14 = $pdf->GetStringWidth('% PREST')+2;
$x15 = $pdf->GetStringWidth('MOTIVO')+2;

$pdf->SetFont('Arial','',7);
$direccion = $_REQUEST['direccion'];
$idcolumna = $_REQUEST['idcolumna'];

$origenbeca = $_REQUEST['origenbeca'];
$tiposolicitud = $_REQUEST['tiposolicitud'];
$tipobeca = $_REQUEST['tipobeca'];
$estatus = $_REQUEST['estatus'];
$nivel = $_REQUEST['nivel'];
$grado = $_REQUEST['grado'];
$where = '';

if(($origenbeca != '' && $origenbeca != null) || ($tiposolicitud != '' && $tiposolicitud != null) || ($tipobeca != '' && $tipobeca != null) || ($estatus != '' && $estatus != null) || ($nivel != '' && $nivel != null)  || ($grado != '' && $grado != null)){
	$where .= 'WHERE ';
}
if($origenbeca != '' && $origenbeca != null){
	$where .= " origen_beca = '".$origenbeca."'";
}
if($tiposolicitud != '' && $tiposolicitud != null){
	if(($origenbeca != '' && $origenbeca != null)){
		$where .= " AND tipo_sol = '".$tiposolicitud."'";
	}else{
		$where .= " tipo_sol = '".$tiposolicitud."'";
	}
	
}
if($tipobeca != '' && $tipobeca != null){
	if(($origenbeca != '' && $origenbeca != null) || ($tiposolicitud != '' && $tiposolicitud != null)){
		$where .= " AND tipo_beca = '".$tipobeca."'";
	}else{
		$where .= " tipo_beca = '".$tipobeca."'";
	}
	
}
if($estatus != '' && $estatus != null){
	if(($origenbeca != '' && $origenbeca != null) || ($tiposolicitud != '' && $tiposolicitud != null) || ($tipobeca != '' && $tipobeca != null) ){
		$where .= " AND estatus = '".$estatus."'";
	}else{
		$where .= " becas.estatus = '".$estatus."'";
	}

}
if($nivel != '' && $nivel != null){
	if(($origenbeca != '' && $origenbeca != null) || ($tiposolicitud != '' && $tiposolicitud != null) || ($tipobeca != '' && $tipobeca != null) || ($estatus != '' && $estatus != null)){
		$where .= " AND nivel = '".$nivel."'";
	}else{
		$where .= "nivel = '".$nivel."'";
	}
	
}
if($grado != '' && $grado != null){
	if(($origenbeca != '' && $origenbeca != null) || ($tiposolicitud != '' && $tiposolicitud != null) || ($tipobeca != '' && $tipobeca != null) || ($estatus != '' && $estatus != null) || ($nivel != '' && $nivel != null)){
		$where .= " AND becas.grado = ".$grado;
	}else{
		$where .= "becas.grado = ".$grado;
	}
	
}



$sql = $mysqli->query("SELECT id_beca,folio,matricula,nombre,grado,porc_beca,origen_beca,tipo_sol,porc_actual,estatus_beca.descripcion AS estatus, becas.estatus AS estatus_2,beca AS tipo_beca,becas.tipo_beca AS tipo_beca2,porc_prestacion,origen_beca2 AS ab_origen_beca2,
	empresas_convenio.empresa AS origen_beca2,motivo, ctrlesc.nivel.descripcion AS nivel
	FROM becas.becas
	LEFT JOIN becas.tipo_becas ON tipo_becas.abreviatura = becas.tipo_beca
	LEFT JOIN becas.estatus_beca ON estatus_beca.abreviatura = becas.estatus
	LEFT JOIN ctrlesc.nivel ON ctrlesc.nivel.cve = becas.nivel
	LEFT JOIN becas.empresas_convenio ON empresas_convenio.abreviatura = becas.origen_beca2
	".$where."
	ORDER BY ".$idcolumna." ".$direccion);
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		
		$pdf->Cell($x1,15,rtrim($r_sql['folio'], " "),0,0,'L',false);
		$pdf->Cell($x2,15,rtrim($r_sql['matricula']," "),0,0,'L',false);
		$pdf->Cell($x3,15,rtrim($r_sql['nombre']," "),0,0,'L',false);
		$pdf->Cell($x4,15,rtrim($r_sql['nivel']," "),0,0,'L',false);
		if($r_sql['grado'] != 0){$pdf->Cell($x5,15,rtrim($r_sql['grado']," "),0,0,'C',false);}else{$pdf->Cell($x5,15,'',0,0,'C',false);}
		$pdf->Cell($x6,15,rtrim($r_sql['origen_beca']," "),0,0,'L',false);
		if($r_sql['porc_beca'] != 0){$pdf->Cell($x7,15,$r_sql['porc_beca'],0,0,'R',false);}else{$pdf->Cell($x7,15,'',0,0,'R',false);}
		// $pdf->Cell($x7,15,rtrim($r_sql['porc_beca']," ").'%',0,0,'R',false);
		$pdf->Cell($x8,15,rtrim($r_sql['tipo_sol']," "),0,0,'L',false);
		$pdf->Cell($x9,15,rtrim($r_sql['tipo_beca']," "),0,0,'L',false);
		$pdf->Cell($x10,15,rtrim($r_sql['prom_aca']," "),0,0,'L',false);
		$pdf->Cell($x11,15,rtrim($r_sql['prom_con']," "),0,0,'L',false);
		$pdf->Cell($x12,15,rtrim($r_sql['estatus']," "),0,0,'L',false);
		if($r_sql['porc_actual'] != 0){$pdf->Cell($x13,15,$r_sql['porc_actual'],0,0,'R',false);}else{$pdf->Cell($x13,15,'',0,0,'R',false);}
		// $pdf->Cell($x13,15,rtrim($r_sql['porc_actual']," "),0,0,'R',false);
		$pdf->Cell($x14,15,rtrim($r_sql['porc_prestacion']," "),0,0,'R',false);
		$pdf->Cell($x15,15,rtrim($r_sql['motivo']," "),0,'L',false);


		
		// $pdf->Cell($x_nombre,15,$r_sql['nombre'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);
		// $pdf->Cell($x_matricula,15,$r_sql['matricula'],1,0,'L',false);

		$pdf->Ln(3);
	}
}


$pdf->Output();

?>