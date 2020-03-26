
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
		$this->Cell($pageWidth-100,10,utf8_decode('COMPARATIVA DE INVENTARIO FISICO VX KARDEX '),0,0,'L',false);
		$this->Ln(5);	
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'',0,0,'L',false);
		$this->Cell($pageWidth-100,10,utf8_decode('INVENTARIO FISICO '.date('F j, Y')),0,0,'L',false);

				 // Salto de línea

		$global = $_REQUEST['global'];
		$this->Ln(14);
        if ($global=='true'){
      		 $this->Cell(100,1,'IMPRESION GLOBAL',0,0,'C',false);
		//$this->Ln(5);	
        }
        else {
           $this->Cell(100,1,'IMPRESION CON DIFERENCIAS',0,0,'C',false);
		
         } 
		$this->Ln(3);

		// $this->Rect($this->GetX(),$this->GetY(),$pageWidth-20,$pageHeight+45);
		

		$this->SetFont('Arial','B',7);
		$x1 = $this->GetStringWidth('CONSECUTIVO')+6;
		$x2 = $this->GetStringWidth('GRUPO')+30;
		$x3 = $this->GetStringWidth('CODIGO DE BARRAS')+8;
		$x4 = $this->GetStringWidth('ARTICULO')+40;
		$x5 = $this->GetStringWidth('EXISTENCIA')+10;
		$x6 = $this->GetStringWidth('COSTO')+10;
		$x7 = $this->GetStringWidth('EXISTENCIA')+10;
		$x8 = $this->GetStringWidth('COSTO')+10;
		$x9 = $this->GetStringWidth('EXISTENCIA')+10;
		$x10 = $this->GetStringWidth('COSTO')+10;
		$x11 = $this->GetStringWidth('EXISTENCIA')+10;
		$x12 = $this->GetStringWidth('COSTO')+10;
		$x_aux = $x1+6+$x2+6+$x3+35+$x4+10+$x5+2+$x6+2+$x7+2+$x8+14+$x9+$x10+$x11+4;
		$this->Cell(340,5,'KARDEX',0,0,'C',false);
		$this->Cell(-255,5,'FISICO',0,0,'C',false);
		$this->Cell(340,5,'FALTANTE',0,0,'C',false);
		$this->Cell(-255,5,'SOBRANTE',0,0,'C',false);
		$this->Ln(1);
		//$x_aux = $x1+6+$x2+6+$x3+35+$x4+10+$x5+2;
		//$this->Ln(1);


		$this->Cell($x1,15,'CONSECUTIVO',0,0,'L',false);
		$this->Cell($x2,15,utf8_decode('GRUPO'),0,0,'L',false);
		$this->Cell($x3,15,'CODIGO DE BARRAS',0,0,'L',false);
		$this->Cell($x4,15,'ARTICULO',0,0,'L',false);
		$this->Cell($x5,15,'EXISTENCIA',0,0,'L',false);
		$this->Cell($x6,15,'COSTO',0,0,'L',false);
		$this->Cell($x7,15,'EXISTENCIA',0,0,'L',false);
		$this->Cell($x8,15,'COSTO',0,0,'L',false);
		$this->Cell($x9,15,'EXISTENCIA',0,0,'L',false);
		$this->Cell($x10,15,'COSTO',0,0,'L',false);
		$this->Cell($x11,15,'EXISTENCIA',0,0,'L',false);
		$this->Cell($x12,15,'COSTO',0,0,'L',false);
		$this->Ln(5); 
		

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

$pdf ->AddPage('L','Legal');

$x1  = $pdf->GetStringWidth('CONSECUTIVO')+6;
$x2  = $pdf->GetStringWidth('GRUPO')+30;
$x3  = $pdf->GetStringWidth('CODIGO DE BARRAS')+8;
$x4  = $pdf->GetStringWidth('ARTICULO')+30;
$x5  = $pdf->GetStringWidth('EXISTENCIA')+10;
$x6  = $pdf->GetStringWidth('COSTO')+10;
$x7  = $pdf->GetStringWidth('EXISTENCIA')+10;
$x8  = $pdf->GetStringWidth('COSTO')+10;
$x9  = $pdf->GetStringWidth('EXISTENCIA')+10;
$x10 = $pdf->GetStringWidth('COSTO')+10;
$x11 = $pdf->GetStringWidth('EXISTENCIA')+10;
$x12 = $pdf->GetStringWidth('COSTO')+10;

 
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
		//$line = "___________________";


	
if($sql->num_rows > 0){
	while($r_sql = mysqli_fetch_array($sql)){
		
		$pdf->Cell($x1,15,rtrim($r_sql['consec'], " "),0,0,'C',false);
		$pdf->Cell($x2,15,rtrim($r_sql['gpo_descrip']," "),0,0,'L',false);
		$pdf->Cell($x3,15,rtrim($r_sql['codigob']," "),0,0,'L',false);
		$pdf->Cell($x4,15,rtrim($r_sql['descrip']," "),0,0,'L',false);
		$pdf->Cell($x5,15,rtrim($r_sql['existe']," "),0,0,'R',false);
		$pdf->Cell($x6,15,rtrim($r_sql['costo']," "),0,0,'R',false);
		$pdf->Cell($x7,15,rtrim($r_sql['exi_fis']," "),0,0,'R',false);
		$pdf->Cell($x8,15,rtrim($r_sql['cto_fis']," "),0,0,'R',false);
		$pdf->Cell($x9,15,rtrim($r_sql['faltante_exi']," "),0,0,'R',false);
		$pdf->Cell($x10,15,rtrim($r_sql['faltante_cto']," "),0,0,'R',false);
		$pdf->Cell($x11,15,rtrim($r_sql['sobrante_exi']," "),0,0,'R',false);
		$pdf->Cell($x12,15,rtrim($r_sql['sobrante_cto']," "),0,0,'R',false);
		//$pdf->Cell($x5,15,$line,0,0,'L',false);
		

/*		$pageHeight = $pdf->GetPageHeight()/2;
		for ($i=0; $i<=$sql->num_rows; $i=$i+3) { 
			//echo $sql->num_rows;
			$pdf->Line(160,$i+($pageHeight-150), 180, $i+($pageHeight-150));	
		}
		
		*/
		//$pdf->Line(43, 40,$pageWidth-10, 43); 
		/*if($r_sql['grado'] != 0){$pdf->Cell($x5,15,rtrim($r_sql['grado']," "),0,0,'C',false);}else{$pdf->Cell($x5,15,'',0,0,'C',false);}
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
		$pdf->Cell($x15,15,rtrim($r_sql['motivo']," "),0,'L',false); */


		
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