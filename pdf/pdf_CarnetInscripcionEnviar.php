
<?php
require('fpdf/fpdf-1.8.php');
require('phpmailer/phpmailer/PHPMailerAutoload.php');
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
					LEFT JOIN cxcandes.carnets_inscripcion AS cins
					ON alum.id_alumno=cins.id_alumno
					WHERE alum.id_alumno=".$_REQUEST['id_alumno']."
					AND cins.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']." LIMIT 1");

			$sqlDescuentos = $mysqli->query("SELECT di.fecha_inicio, 
					di.fecha_fin, ce.fechai,alum.fecha_ingreso ,
					ROUND(ROUND((1-(di.descuento/100))*ci.cuota, 0),2) AS cuota
					FROM  cuotas_inscripciones AS ci
					LEFT JOIN carnets_inscripcion AS cari
					ON cari.id_cuota_inscripcion=ci.id_cuota_inscripcion
					LEFT JOIN  sistema_andes.alumnos AS alum
					ON alum.id_alumno=cari.id_alumno
					LEFT JOIN sistema_andes.ciclos_escolares AS ce
					ON ce.id_ciclo_escolar=1
					LEFT JOIN descuentos_inscripciones AS di
					ON di.id_cuota_inscripcion=ci.id_cuota_inscripcion
					WHERE (di.id_estatus_ingreso=3 OR (CASE WHEN 
					ce.fechai<alum.fecha_ingreso THEN di.id_estatus_ingreso=2 
					ELSE di.id_estatus_ingreso=1 END )) 
					AND ci.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."
					AND cari.id_alumno=".$_REQUEST['id_alumno']);

			$sqlCuota= $mysqli->query("SELECT 
					di.fecha_fin, 
					ROUND(ROUND(ci.cuota,0),2) AS cuota
					FROM  cuotas_inscripciones AS ci
					LEFT JOIN carnets_inscripcion AS cari
					ON cari.id_cuota_inscripcion=ci.id_cuota_inscripcion
					LEFT JOIN  sistema_andes.alumnos AS alum
					ON alum.id_alumno=cari.id_alumno
					LEFT JOIN sistema_andes.ciclos_escolares AS ce
					ON ce.id_ciclo_escolar=ci.id_ciclo_escolar
					LEFT JOIN descuentos_inscripciones AS di
					ON di.id_cuota_inscripcion=ci.id_cuota_inscripcion
					WHERE cari.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."
					AND cari.id_alumno=".$_REQUEST['id_alumno']." ORDER BY fecha_fin DESC LIMIT 1
					");

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

				$pdf->Cell(35,23,utf8_decode(''),0,0,'C',false);
				$pdf->Cell(22,23,rtrim($r_sql['calle'],""),0,0,'C',false);
				$pdf->Cell(1,23,utf8_decode('# '),0,0,'C',false);
				$pdf->Cell(4,23,rtrim($r_sql['numero_exterior']," "),0,0,'C',false);
				$pdf->Cell(21,23,rtrim($r_sql['colonia']," "),0,0,'C',false);
				$pdf->Cell(8,23,utf8_decode('TELS.'),0,0,'C',false);
				$pdf->Cell(22,23,rtrim($r_sql['telefonos']," "),0,0,'C',false);
				$pdf->Ln(1);
				$pdf->Cell(210,28,utf8_decode('MAZATLÁN, SIN.'),0,0,'C',false);
				$pdf->Ln(15); 
				
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(72,10,'',0,0,'L',false);
				$pdf->Cell(40,10,utf8_decode('INSCRIPCIÓN CICLO'),0,0,'R',false);
				$pdf->Cell(18,10,rtrim($r_sql['ciclo']," "),0,0,'C',false);
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
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(70,20,utf8_decode('Número de convenio: 1634968'),0,0,'R',false);
				$pdf->Ln(1); 
				$pdf->Cell(33,27,'',0,0,'L',false);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(12,27,utf8_decode('Alumno: '),0,0,'C',false);
				$pdf->SetFont('Arial','',8);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['ap_paterno']))+1,27,rtrim($r_sql['ap_paterno']," "),0,0,'L',false);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['ap_materno']))+1,27,rtrim($r_sql['ap_materno']," "),0,0,'L',false);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['nombre'])),27,rtrim($r_sql['nombre']," "),0,0,'L',false);
				$pdf->Ln(1); 
				$pdf->Cell(32,30,'',0,0,'L',false);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(17,34,utf8_decode('Referencia:'),0,0,'L',false);
				$pdf->Cell(30,34,rtrim($r_sql['referencia']," "),0,0,'L',false);
				$pdf->Ln(12);
				$pdf->Cell(122,10,'');
				$pdf->Image('bbva.png',$pdf->GetX(),$pdf->GetY(),50);
				$pdf->Ln(11);  
				
			} 

			$pdf->SetFont('Arial','',8);			
			date_default_timezone_set("America/Mexico_City");
			setlocale(LC_TIME, 'spanish');

			if($sqlDescuentos->num_rows > 0){
			while($r_sql = mysqli_fetch_array($sqlDescuentos)){
				
				$pdf->Cell(33,10,utf8_decode(' '),0,0,'C',false);
				$pdf->Cell($pdf->GetStringWidth(rtrim($r_sql['fecha_inicio']))+6,5,rtrim(strtoupper(strftime("%d de %B", strtotime($r_sql['fecha_inicio']))), " "),"LT");
				$pdf->Cell(5,5,utf8_decode( ' AL'),"T");
				$pdf->Cell(25,5,rtrim(strtoupper(strftime("%d de %B ", strtotime($r_sql['fecha_fin'])))," "),"RT");
				$pdf->Cell(5,5,utf8_decode( '$'),"T",0,'R',false);
				$pdf->Cell(13,5,rtrim($r_sql['cuota']," "),"TR",0,'R',false);
				$pdf->Ln(1.69);
				$pdf->Ln(3); 

	} 
			$pdf->Cell(33,10,utf8_decode(' '),0,0,'C',false);
			$pdf->Cell(15,5,utf8_decode( 'A PARTIR DEL'),"LTB");

					if($sqlCuota->num_rows > 0){
						while($r_sql = mysqli_fetch_array($sqlCuota)){
							$pdf->Cell(6,5,utf8_decode(' '),'TB');
							$pdf->Cell(29.5,5,rtrim(strtoupper(strftime("%d de %B", strtotime($r_sql['fecha_fin']))), " "),"TRB",0,'L',false);
							$pdf->Cell(5,5,utf8_decode( '$'),"TB",0,'R',false);

							$pdf->Cell(13,5,rtrim($r_sql['cuota']," "),"TRB",0,'R',false);
							$pdf->Ln(1.69);
							$pdf->Ln(3); 

						} 
					} 
} 

		$pdf->Output();
		$doc = $pdf->Output('S','Carnet_Inscripcion.pdf');

		// echo $doc;
		if($sqlCorreo->num_rows > 0){
			while($r_sql = mysqli_fetch_array($sqlCorreo)){
		 		$mail = new PHPMailer();
		        $mail->IsSMTP();
		        $mail->SMTPAuth   = true;
		        $mail->Host       = "smtp.gmail.com";
		        $mail->Port       = 587;
		        $mail->AddAddress($_REQUEST['correo']);
		        // $o_link = $this->conexion(); 
		        
		        $mail->Username   = "lilianagarciaguevara1496@gmail.com";
		        $mail->Password   = "Liliana1496!";
		        //$mail->From = "lilianagarciaguevara1496@gmail.com";
		        $mail->FromName = utf8_decode("CARNET DE INSCRIPCIÓN");

		        // $mail->AddEmbeddedImage('file:///C:/xampp/htdocs/ProveedoresExtranjeros/resources/cxp3.jpg', 'logo_2u');

		        $message=utf8_decode("<div><h4>Estimado Padre de Familia:</h4>
		        <p>De acuerdo a sus indicaciones, enviamos el carnet de inscripción para el ciclo escolar ".$r_sql['ciclo']."; en él encontrará las 
		        referencias y las distintas opciones para realizar el pago del mismo.</p>
		        <p>Para mayor información puede comunicarse a los teléfonos:</p>
		        <p>".$r_sql['telefonos'].".</p>
		        <p>Para ".$r_sql['nombre']." es un placer atenderle.</p>
		        <p>Cordialmente,</p>
		        <p>La administración.</p></div>");

		        $subject = utf8_decode("Envío de carnet de inscripción.");
		        $mail->Subject    = $subject;
		        $mail->MsgHTML($message);
		        $mail->AddStringAttachment($doc, 'Carnet_Inscripcion.pdf', 'base64', 'application/pdf');
		        $mail->Send();
		    }
		}




?>