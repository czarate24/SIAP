
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

			$sqlDescuentos = $mysqli->query("SELECT ci.cuota, cc.cuota as colegiatura, ci.descripcion, des_ins.descuento as porcentaje, ROUND(ROUND(ci.cuota*(1-(des_ins.descuento/100)),0),2) AS descuento
				FROM cxcandes.cuotas_colegiaturas AS cc 
                INNER JOIN cxcandes.cuotas_inscripciones AS ci
                ON ci.id_nivel_educativo=cc.id_nivel_educativo AND ci.grado=cc.grado
                INNER JOIN cxcandes.descuentos_inscripciones AS des_ins
                ON ci.id_cuota_inscripcion=des_ins.id_cuota_inscripcion
                WHERE ci.id_institucion=".$_REQUEST['id_institucion']." AND ci.estatus=1 AND ci.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."  AND cc.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."  ORDER BY ci.id_cuota_inscripcion, porcentaje DESC");


			$sqlPorcentajes = $mysqli->query("SELECT  descuento, 
				CASE id_estatus_ingreso
				WHEN 1 THEN 'Reingreso'
				WHEN 2 THEN 'Nuevo Ingreso'
				WHEN 3 THEN ''
				END AS estatus, fecha_inicio, fecha_fin FROM descuentos_inscripciones AS di
				INNER JOIN cuotas_inscripciones AS ci
				ON ci.id_cuota_inscripcion=di.id_cuota_inscripcion
                WHERE ci.id_institucion=".$_REQUEST['id_institucion']." AND ci.estatus=1 AND ci.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']." GROUP BY descuento desc");


				$sqlEstatus = $mysqli->query("SELECT
				CASE id_estatus_ingreso
				WHEN 1 THEN 'Reingreso'
				WHEN 2 THEN 'Nuevo Ingreso'
				WHEN 3 THEN ''
				END AS estatus FROM descuentos_inscripciones AS di
				INNER JOIN cuotas_inscripciones AS ci
				ON ci.id_cuota_inscripcion=di.id_cuota_inscripcion
                WHERE ci.id_institucion=".$_REQUEST['id_institucion']." AND ci.estatus=1 AND ci.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']." GROUP BY descuento desc");


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
				$pdf->Ln(5); 

				$pdf->Cell(10,10,'',0,0,'L',false);

				if($_REQUEST['id_institucion']==="1"){
					$pdf->Image('logoandes.png',$pdf->GetX(),$pdf->GetY(),25);
				}else{
					$pdf->Image('logocedi.png',$pdf->GetX(),$pdf->GetY(),25);
					
				}
				$pdf->SetFont('Arial','B',11);
				$pdf->Ln(12);
				$pdf->Cell(70,50,'',0,0,'L',false);
				$pdf->Cell(70,10,utf8_decode('Cuotas de Inscripción y Colegiatura.'),0,0,'L',false);
				$pdf->SetFont('Arial','',9);
				$pdf->Ln(5);
				$pdf->Cell(35,50,'',0,0,'L',false);
				$pdf->Cell(80,10,utf8_decode('Enseguida se presentan las colegiaturas e inscripciones para el ciclo escolar'),0,0,'L',false);
				$pdf->Cell(49,10,rtrim($r_sql['ciclo']," "),0,0,'R',false); 
				$pdf->Cell(0,10,utf8_decode(':'),0,0,'L',false);
				$pdf->Ln(15);
			} 

						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(47,20,'',0,0,'L',false);
						
						$pdf->Cell(102,6,utf8_decode('INSCRIPCIONES'),1,0,'C',false);
						$pdf->Ln(6);
						$pdf->Cell(47,20,'',0,0,'L',false);
					
						$pdf->Cell(17,6,utf8_decode('ENE-FEB'),"LTB",0,'C',false);
						$pdf->Cell(17,6,utf8_decode('ENE-FEB'),"LTB",0,'C',false);
						$pdf->Cell(17,6,'MARZO',1,0,'C',false);
						$pdf->Cell(17,6,'ABR-MAY',1,0,'C',false);
						$pdf->Cell(17,6,'JUN-JUL',1,0,'C',false);
						$pdf->Cell(17,6,'AGOSTO',1,0,'C',false);
						$pdf->Ln(6);

						$pdf->Cell(47,80,'',0,0,'L',false);
						$pdf->SetFont('Arial','',7);
						if($sqlEstatus->num_rows > 0){
							while($r_sql = mysqli_fetch_array($sqlEstatus)){
								$pdf->Cell(17,6,rtrim($r_sql['estatus']," "),"LTR",0,'C',false);
								
							} 
						} 

						$pdf->Cell(17,6,utf8_decode(''),"RTB",0,'L',false);


						$pdf->Ln(6);
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(23,80,'',0,0,'L',false);
						$pdf->Cell(24,6,utf8_decode('NIVEL'),"LTB",0,'C',false);

						if($sqlPorcentajes->num_rows > 0){
							while($r_sql = mysqli_fetch_array($sqlPorcentajes)){
								$pdf->Cell(10,6,rtrim($r_sql['descuento']," "),"LTB",0,'R',false);
								$pdf->Cell(7,6,utf8_decode('%'),"RTB",0,'L',false);
								
							} 

							$pdf->Cell(9,6,utf8_decode('0'),"LTB",0,'R',false);
							$pdf->Cell(8,6,utf8_decode('%'),"RTB",0,'L',false);
							$pdf->Cell(25,6,'COLEGIATURAS',1,0,'C',false);
							
						} 


						


			if($sqlDescuentos->num_rows > 0){

			while($r_sql = mysqli_fetch_array($sqlDescuentos)){
					$pdf->SetFont('Arial','',8);
						
						if($r_sql['porcentaje']==='40'){
								$pdf->Ln(6);
								$pdf->Cell(23,80,'',0,0,'L',false);
								$pdf->Cell(24,6,rtrim($r_sql['descripcion']," "),1,0,'L',false);
							} 


						$pdf->Cell(9,6,utf8_decode('$'),"LTB",0,'L',false);
						$pdf->Cell(8,6,rtrim($r_sql['descuento']," "),"RTB",0,'R',false);	

						if($r_sql['porcentaje']==='5'){
								$pdf->Cell(9,6,utf8_decode('$'),"LTB",0,'L',false);
								$pdf->Cell(8,6,rtrim($r_sql['cuota']," "),"RTB",0,'R',false);

								$pdf->Cell(13,6,utf8_decode('$'),"LTB",0,'R',false);
								$pdf->Cell(12,6,rtrim($r_sql['colegiatura']," "),"RTB",0,'R',false);
							} 
	} 
				
} 

				

			


				
			
$pdf->Output();

?>