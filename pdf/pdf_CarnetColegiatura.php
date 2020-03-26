
<?php
require('fpdf/fpdf-1.8.php');
include "conexion.php";
define("DATABASE", 'cxcandes'); //El nombre de la base de datos.
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

class PDF extends FPDF
{
	function Header()
	{
		$this->SetFont('Arial','B',11);
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
					insdatos.convenio_cie,
					insdatos.CLABE,
					insdatos.colonia,  
					ins.telefonos, 
					alum.matricula, 
					alum.nombre, 
					alum.ap_paterno, 
					alum.ap_materno, 
					alum.grado, 
					alum.grupo, 
					alum.id_nivel_educativo, 
					(SELECT ciclo 
					FROM sistema_andes.ciclos_escolares 
					WHERE id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar'].") AS ciclo,
					cins.referencia
					FROM cxcandes.instituciones_datos AS insdatos 
					LEFT JOIN sistema_andes.instituciones AS ins
					ON ins.id_institucion=insdatos.id_institucion
					LEFT JOIN sistema_andes.alumnos AS alum
					ON alum.id_institucion=ins.id_institucion
					LEFT JOIN cxcandes.carnets_colegiatura AS cins
					ON alum.id_alumno=cins.id_alumno
					WHERE alum.id_alumno=".$_REQUEST['id_alumno']."
					AND cins.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']." GROUP by cins.id_alumno");

			
			$sqlCuota= $mysqli->query("SELECT colegiatura.cuota AS colegiatura, 
				tipo_becas.beca, carnet.fecha_vencimiento,
				becas.porc_actuaL AS porc_actual, 
				ROUND(ROUND(((1-(becas.porc_actual/100))*colegiatura.cuota),0),2) AS total_beca, 
				transporte.descripcion, 
				IFNULL(transporte.cuota, 0) AS transporte,
				ROUND((ROUND(((1-(IFNULL(becas.porc_actual, 0)/100))*colegiatura.cuota),0) +  IFNULL(transporte.cuota, 0)),2) AS total,
				ROUND((((ROUND(((1-(IFNULL(becas.porc_actual, 0)/100))*colegiatura.cuota),0) +  IFNULL(transporte.cuota, 0))*(1+(SELECT porcentaje_recargo FROM configuracion WHERE estatus=1 AND id_institucion=".$_REQUEST['id_institucion'].")/100))),2) AS total_total
				FROM cxcandes.carnets_colegiatura AS carnet
				LEFT JOIN cxcandes.cuotas_colegiaturas AS colegiatura
				ON carnet.id_cuota_colegiatura=colegiatura.id_cuota_colegiatura
				LEFT JOIN cxcandes.transportes_alumno AS transporte_alumno
				ON transporte_alumno.id_transporte_alumno=carnet.id_cuota_transporte
				LEFT JOIN cxcandes.cuotas_transportes AS transporte
				ON transporte_alumno.id_cuota_transporte=transporte.id_cuota_transporte
				LEFT JOIN becas.becas AS becas
				ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
				WHERE sistema_andes.alumnos.id_alumno=".$_REQUEST['id_alumno'].")
				LEFT JOIN becas.tipo_becas AS tipo_becas
				ON tipo_becas.abreviatura=becas.tipo_beca
				WHERE carnet.id_alumno=".$_REQUEST['id_alumno']." AND carnet.id_carnet_colegiatura=".$_REQUEST['id_carnet_colegiatura']." GROUP BY carnet.id_alumno");


			while($r_sql = mysqli_fetch_array($sql)){
				$pdf->Cell(180,20,rtrim($r_sql['razon_social'], " "),0,0,'C',false);	
				$pdf->Ln(3); 
				$pdf->SetFont('Arial','',6);

				$pdf->Cell(30,0,'',0,0,'L',false);

				if($_REQUEST['id_institucion']==="1"){
					$pdf->Image('logoandes.png',$pdf->GetX(),$pdf->GetY(),22);
				}else{
					$pdf->Image('logocedi.png',$pdf->GetX(),$pdf->GetY(),15);
					
				}

				$pdf->Cell(30,23,utf8_decode(''),0,0,'C',false);
				$pdf->Cell(23,23,rtrim($r_sql['calle'],""),0,0,'C',false);
				$pdf->Cell(1,23,utf8_decode('# '),0,0,'C',false);
				$pdf->Cell(4,23,rtrim($r_sql['numero_exterior']," "),0,0,'C',false);
				$pdf->Cell(21,23,rtrim($r_sql['colonia']," "),0,0,'C',false);
				$pdf->Cell(6,23,utf8_decode('TELS.'),0,0,'C',false);
				$pdf->Cell(25,23,rtrim($r_sql['telefonos']," "),0,0,'C',false);
				$pdf->Ln(1);
				$pdf->Cell(205,28,utf8_decode('MAZATLÁN, SIN.'),0,0,'C',false);
				$pdf->Ln(15); 

				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(74,10,'',0,0,'L',false);
				$pdf->Cell(55,10,rtrim($_REQUEST['concepto']," "),0,0,'C',false);
				$pdf->SetFont('Arial','B',8);
				$pdf->Ln(3); 
				$pdf->Cell(35,20,'',0,0,'L',false);
				$pdf->Cell(10,20,utf8_decode('Matrícula: '),0,0,'C',false);
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(18,20,rtrim($r_sql['matricula']," "),0,0,'C',false);
				$pdf->Cell(10,20,'',0,0,'L',false);

				$pdf->Cell(2,20,utf8_decode('('),0,0,'L',false);
				if($_REQUEST['id_status_ciclo']==="3"){
					
					if(rtrim($r_sql['id_nivel_educativo']==="1" && $r_sql['grado']==="MAT")){
							$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
							$pdf->Cell(3,20,utf8_decode('1'),0,0,'C',false);
						}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="1")){
								$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
								$pdf->Cell(3,20,utf8_decode('2'),0,0,'C',false);
							}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="2")){
									$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
									$pdf->Cell(3,20,utf8_decode('3'),0,0,'C',false);
								}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="3")){
										$pdf->Cell(7,20,utf8_decode('PRI'),0,0,'C',false);
										$pdf->Cell(3,20,utf8_decode('1'),0,0,'C',false);
									}else if(rtrim($r_sql['id_nivel_educativo']==="2"  && $r_sql['grado']>=1 && $r_sql['grado']<=5)){
											$pdf->Cell(7,20,utf8_decode('PRI'),0,0,'C',false);
											$pdf->Cell(3,20,rtrim($r_sql['grado']+1," "),0,0,'L',false);
										}else if(rtrim($r_sql['id_nivel_educativo']==="2"  && $r_sql['grado']==6)){
												$pdf->Cell(7,20,utf8_decode('SEC'),0,0,'C',false);
												$pdf->Cell(3,20,utf8_decode('1'),0,0,'C',false);
											}else if(rtrim($r_sql['id_nivel_educativo']==="3"  && $r_sql['grado']>=1 && $r_sql['grado']<=2)){
												$pdf->Cell(7,20,utf8_decode('SEC'),0,0,'C',false);
												$pdf->Cell(3,20,rtrim($r_sql['grado']+1," "),0,0,'L',false);
												}else if(rtrim($r_sql['id_nivel_educativo']==="3"  && $r_sql['grado']>=3)){
													$pdf->Cell(7,20,utf8_decode('BACH'),0,0,'C',false);
													$pdf->Cell(3,20,utf8_decode('1'),0,0,'C',false);
													}else if(rtrim($r_sql['id_nivel_educativo']==="4"  && $r_sql['grado']>=1 && $r_sql['grado']<=5)){
														$pdf->Cell(7,20,utf8_decode('BACH'),0,0,'C',false);
														$pdf->Cell(3,20,rtrim($r_sql['grado']+1," "),0,0,'L',false);
													}
				}else{

					if(rtrim($r_sql['id_nivel_educativo']==="1" && $r_sql['grado']==="MAT")){
							$pdf->Cell(7,20,utf8_decode('MAT'),0,0,'C',false);
						}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="1")){
								$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
								$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
							}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="2")){
									$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
									$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
								}else if(rtrim($r_sql['id_nivel_educativo']==="1"  && $r_sql['grado']==="3")){
										$pdf->Cell(7,20,utf8_decode('PRE'),0,0,'C',false);
										$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
									}else if(rtrim($r_sql['id_nivel_educativo']==="2")){
											$pdf->Cell(7,20,utf8_decode('PRI'),0,0,'C',false);
											$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
										}else if(rtrim($r_sql['id_nivel_educativo']==="3")){
												$pdf->Cell(7,20,utf8_decode('SEC'),0,0,'C',false);
												$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
											}else if(rtrim($r_sql['id_nivel_educativo']==="4")){
													$pdf->Cell(8,20,utf8_decode('BACH'),0,0,'C',false);
													$pdf->Cell(3,20,rtrim($r_sql['grado']," "),0,0,'L',false);
											}
					}						

				
				$pdf->Cell(3,20,utf8_decode('" )'),0,0,'L',false);
				$pdf->Cell(-55,29,'',0,0,'L',false);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(12,27,utf8_decode('Alumno: '),0,0,'C',false);
				$pdf->SetFont('Arial','',8);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['ap_paterno']))+1,27,rtrim($r_sql['ap_paterno']," "),0,0,'L',false);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['ap_materno']))+1,27,rtrim($r_sql['ap_materno']," "),0,0,'L',false);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['nombre'])),27,rtrim($r_sql['nombre']," "),0,0,'L',false);
				
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(135,32);
				 $pdf->Cell(50,20,utf8_decode('Para Pagar en Banco/Transferencia'),0,0,'R',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(123,38);
				 $pdf->Cell(50,20,utf8_decode('Referencia:'),0,0,'L',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(144,38);
				 $pdf->Cell(50,20,rtrim($r_sql['referencia']," "),0,0,'L',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(150,45);
				 $pdf->Cell(50,20,utf8_decode('BBVA'),0,0,'L',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(135,50);
				 $pdf->Cell(50,20,rtrim("Convenio CIE: ".$r_sql['convenio_cie']," "),0,0,'L',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(143,57);
				 $pdf->Cell(50,20,utf8_decode('Otros Bancos'),0,0,'L',false);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->SetXY(118,62);
				 $pdf->Cell(50,20,rtrim("CLABE Interbancaria: ".$r_sql['CLABE']," "),0,0,'L',false);
				 
				 
			/*$pdf->Ln(1); 
				$pdf->Cell(32,30,'',0,0,'L',false);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(17,34,utf8_decode('Referencia:'),0,0,'L',false);
				$pdf->Cell(35,34,rtrim($r_sql['referencia']," "),0,0,'L',false);
				//LOGO BANCO BBVA
				$pdf->Ln(12);
				$pdf->Cell(122,10,'');
				$pdf->Image('bbva.png',$pdf->GetX(),$pdf->GetY(),50);
				$pdf->Ln(11);  */ 

				// ÁREA DE CONCEPTOS
				$pdf->SetXY(10,50);
				if($sqlCuota->num_rows > 0){
					while($r_sql = mysqli_fetch_array($sqlCuota)){

						$pdf->SetFont('Arial','',8);
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(65,5,utf8_decode('CONCEPTOS'),1,0,'L',false);
						$pdf->Ln(5); 
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(45,5,utf8_decode('CUOTA COLEGIATURA'),1,0,'L',false);
						$pdf->Cell(6,5,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,5,rtrim($r_sql['colegiatura']," "),"RTB",0,'R',false);
						
						if(rtrim($r_sql['total_beca'])>0){
						$pdf->Ln(4.9); 
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(9,5,utf8_decode('BECA'),"LTB",0,'L',false);
						$pdf->Cell(36,5,rtrim($r_sql['beca']," "),"RBT",0,'L',false);
						$pdf->Cell(6,5,utf8_decode('%'),"LTB",0,'R',false);
						$pdf->Cell(14,5,rtrim($r_sql['porc_actual']," "),"RTB",0,'R',false);
						$pdf->Ln(4.9);
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(45,5,'SUBTOTAL',1,0,'L',false);
						$pdf->Cell(6,5,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,5,rtrim($r_sql['total_beca']," "),"RTB",0,'R',false);
					}
						if(rtrim($r_sql['transporte'])>0){
						
						$pdf->Ln(4.9); 
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(45,5,rtrim($r_sql['descripcion']," "),1,0,'L',false);
						$pdf->Cell(6,5,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,5,rtrim($r_sql['transporte']," "),"RTB",0,'R',false);
						}
						
						$pdf->Ln(4.9); 
						$pdf->Cell(33,5,'',0,0,'L',false);
						$pdf->Cell(45,5,utf8_decode('MENSUALIDAD'),"LBT",0,'L',false);
						$pdf->Cell(6,5,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,5,rtrim($r_sql['total']," "),"RTB",0,'R',false);
						$pdf->Ln(8); 

						$pdf->SetFont('Arial','B',9);
						$pdf->Cell(33,6,'',0,0,'L',false);
						$pdf->Cell(23,6,utf8_decode('HASTA EL DÍA'),"LTB",0,'L',false);
						$pdf->Cell(22,6,rtrim((date('j', strtotime($r_sql['fecha_vencimiento'])))," "),"RBT",0,'L',false);
						$pdf->Cell(6,6,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,6,rtrim($r_sql['total']," "),"RBT",0,'R',false);
						
						$pdf->Ln(6);
						$pdf->Cell(33,6,'',0,0,'L',false);
						$pdf->Cell(31,6,utf8_decode('DESPUÉS DEL DÍA'),"LTB",0,'L',false);
						$pdf->Cell(14,6,rtrim((date('j', strtotime($r_sql['fecha_vencimiento'])))," "),"RBT",0,'L',false);
						$pdf->Cell(6,6,utf8_decode('$'),"LTB",0,'R',false);
						$pdf->Cell(14,6,rtrim($r_sql['total_total']," "),"RTB",0,'R',false);
				}
			} 
	}
			
$pdf->Output();

?>