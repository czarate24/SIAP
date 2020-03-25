
<?php
require('fpdf/fpdf-1.8.php');
include "conexion.php";
define("DATABASE", 'cxcandes'); //El nombre de la base de datos.
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

class PDF extends FPDF
{
	function Header()
	{
		$this->SetFont('Arial','B',12);
		$pageWidth = $this->GetPageWidth();
		$pageHeight = $this->GetPageHeight()/2;
		$this->Cell(15,20,'',0,0,'L',false);
	}

}

		$pdf = new PDF();
		$pdf ->AddPage('P','Letter');
		$sql = $mysqli->query("SELECT insdatos.razon_social, 
					insdatos.calle, 
					insdatos.numero_exterior,
					insdatos.colonia, 
					ins.telefonos,  
					(SELECT ciclo 
					FROM sistema_andes.ciclos_escolares 
					WHERE id_ciclo_escolar=1) AS ciclo
					FROM cxcandes.instituciones_datos AS insdatos 
					LEFT JOIN sistema_andes.instituciones AS ins
					ON ins.id_institucion=insdatos.id_institucion
					AND insdatos.id_institucion=".$_REQUEST['id_institucion']." LIMIT 1");


			$sqlDescuentos = $mysqli->query("SELECT cc.descripcion, cc.cuota AS colegiatura, ci.cuota AS inscripcion
                FROM cxcandes.cuotas_colegiaturas AS cc 
                LEFT JOIN cxcandes.cuotas_inscripciones AS ci
                ON ci.id_nivel_educativo=cc.id_nivel_educativo AND ci.grado=cc.grado
                WHERE ci.id_institucion=".$_REQUEST['id_institucion']." AND ci.estatus=1 AND ci.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']);

			while($r_sql = mysqli_fetch_array($sql)){
				$pdf->Cell(180,20,rtrim($r_sql['razon_social'], " "),0,0,'C',false);	
				$pdf->Ln(3); 
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(60,23,utf8_decode(''),0,0,'C',false);
				$pdf->Cell(25,23,rtrim($r_sql['calle'],""),0,0,'C',false);
				$pdf->Cell(1,23,utf8_decode('# '),0,0,'C',false);
				$pdf->Cell(4,23,rtrim($r_sql['numero_exterior']," "),0,0,'C',false);
				$pdf->Cell(23,23,rtrim($r_sql['colonia']," "),0,0,'C',false);
				$pdf->Cell(8,23,utf8_decode('TELS.'),0,0,'C',false);
				$pdf->Cell(25,23,rtrim($r_sql['telefonos']," "),0,0,'C',false);
				$pdf->Ln(1);
				$pdf->Cell(210,28,utf8_decode('MAZATLÁN, SIN.'),0,0,'C',false);
				$pdf->Ln(15); 

				$pdf->Cell(10,30,'',0,0,'L',false);

				if($_REQUEST['id_institucion']==="1"){
					$pdf->Image('logoandes.png',$pdf->GetX(),$pdf->GetY(),30);
				}else{
					$pdf->Image('logocedi.png',$pdf->GetX(),$pdf->GetY(),25);
					
				}
				$pdf->SetFont('Arial','B',11);
				$pdf->Cell(20,10,'',0,0,'L',false);
				$pdf->Cell(63,10,utf8_decode('CUOTAS DE INSCRIPCIÓN Y COLEGIATURA CICLO'),0,0,'C',false);
				$pdf->Cell(1,10,rtrim($r_sql['ciclo']," "),0,0,'C',false); 
				
			} 


			$pdf->SetFont('Arial','',8);

			date_default_timezone_set("America/Mexico_City");
			setlocale(LC_TIME, 'spanish');

			if($sqlDescuentos->num_rows > 0){
			while($r_sql = mysqli_fetch_array($sqlDescuentos)){
				
		

	} 
			$pdf->Cell(40,10,utf8_decode(' '),0,0,'C',false);
			$pdf->Cell(15,5,utf8_decode( 'A PARTIR DEL'),"LTB");

		
} 
			


$pdf->Output();

?>