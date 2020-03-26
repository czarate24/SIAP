<?php

namespace App\Controller;

use Exception;

class MesesAnualidadController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_institucion');
        $intIdCiclo = $this->request->query('id_ciclo_escolar');
        $intIdAlumno = $this->request->query('id_alumno');
    
        $sQuery = "SELECT id_carnet_colegiatura,
                mes.mes, 
                CONCAT('$', cuota.cuota) AS cuota
                FROM carnets_colegiatura AS carnet
                LEFT JOIN meses_ciclo AS mes
                ON mes.id_mes=carnet.id_mes
                LEFT JOIN cuotas_colegiaturas AS cuota
                ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                WHERE carnet.id_alumno=".$intIdAlumno." AND carnet.id_estatus_carnet=1 AND carnet.id_ciclo_escolar=".$intIdCiclo." AND 
                carnet.id_institucion=".$intIdIns;
        
        $aDatosAlum = $oConexion->query($sQuery);

        if (count($aDatosAlum)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Karnets",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Karnets",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));
            

        }  


    
    }catch (Exception $o_ex) {
    $s_error = str_replace("'", "", $o_ex->getMessage());
    $s_error = str_replace('"', "", $s_error);
    return $this->asJson(array(
                "success" => false,
                "message" => $s_error         
            ));
    }

       

    }

    /**
     * Crea Instituciones
     *
     * @return Cake\Network\Response
     */

    // Agrega datos de Carnet de inscripción al alumno seleccionado
    public function create() 
    {

      $oConexion = $this->getConexion();
      $fecha=date("Y-m-d");  // selecciona la fecha del sistema
      $referencia="XXXXXX";
      $id_tipo_movimiento=1;
		  if($_POST['id_status_ciclo']==="3"){
						
				if($_POST['id_nivel_educativo']==="1"){
						$aum_nivel=1;
					}else if($_POST['id_nivel_educativo']==="2"  && $_POST['grado']==6){
								$aum_nivel=1;
							}else if($_POST['id_nivel_educativo']==="3"  && $_POST['grado']>=3){
										$aum_nivel=1;
									}else{
                    $aum_nivel=0;
                  }
			}else{
					$aum_nivel=0;
				
				}						

           
      
            if($aum_nivel==1){

            		$sQuery = "INSERT INTO carnets_inscripcion(" .
                      "id_alumno, " .
                      "id_ciclo_escolar," .
                      "id_institucion, " .
                      "id_tipo_movimiento, " .
                      "id_cuota_inscripcion, " .
                      "referencia, " .
                      "fecha " .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '".$_POST["ciclo_escolar"]."',
                     '".$_POST["id_institucion"]."',
                     '".$id_tipo_movimiento."',
                     '".($_POST["id_cuota_inscripcion"]+1)."',
                     '".$referencia."',
                     '".$fecha."'
                     )";
                
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Carnets",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));

            }else{

                     $sQuery = "INSERT INTO carnets_inscripcion(" .
                      "id_alumno, " .
                      "id_ciclo_escolar," .
                      "id_institucion, " .
                      "id_tipo_movimiento, " .
                      "id_cuota_inscripcion, " .
                      "referencia, " .
                      "fecha " .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '".$_POST["ciclo_escolar"]."',
                     '".$_POST["id_institucion"]."',
                     '".$id_tipo_movimiento."',
                     '".$_POST["id_cuota_inscripcion"]."',
                     '".$referencia."',
                     '".$fecha."'
                     )";
                
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Carnets",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));
             
    }   
     }

 
    // Agrega los datos del Carnet de inscripción para cada uno de los alumnos con estatus 1
  
 public function createall() 
    {
                $oConexion = $this->getConexion();
                $fecha=date("Y-m-d");
                $referencia="XXXXXX";
                $id_tipo_movimiento=1;
                $id_status_ciclo=$_REQUEST['id_status_ciclo'];
          


                        $sQuery = "INSERT INTO carnets_inscripcion(" .
                              "id_alumno, " .
                              "id_cuota_inscripcion, " .
                              "id_ciclo_escolar," .
                              "id_institucion, " .
                              "id_tipo_movimiento, " .
                              "referencia, " .
                              "fecha " .
                              ")
                              SELECT  alum.id_alumno, 
                              CASE '".$id_status_ciclo."' WHEN 3 THEN 
                              CASE  cins.id_nivel_educativo
                              WHEN 1 THEN  cins.id_cuota_inscripcion+1
                              WHEN 2 THEN  CASE WHEN alum.grado=6 THEN cins.id_cuota_inscripcion+1  ELSE  cins.id_cuota_inscripcion END
                              WHEN 3 THEN  CASE WHEN alum.grado=3 THEN cins.id_cuota_inscripcion+1  ELSE  cins.id_cuota_inscripcion END 
                              ELSE  cins.id_cuota_inscripcion END 
                              ELSE cins.id_cuota_inscripcion END AS id_cuota_inscripcion,
                             '".$_POST["ciclo_escolar"]."',
                             '".$_POST["id_institucion"]."',
                             '".$id_tipo_movimiento."',
                             '".$referencia."',
                             '".$fecha."'
                              FROM sistema_andes.alumnos AS alum 
                              LEFT JOIN cxcandes.cuotas_inscripciones AS cins
                              ON cins.id_nivel_educativo=alum.id_nivel_educativo AND (CASE WHEN cins.grado<>0 OR cins.grado='MAT' THEN cins.grado=alum.grado ELSE cins.grado=0 END) 
                              LEFT JOIN cxcandes.carnets_inscripcion AS cains
                              ON cains.id_alumno=alum.id_alumno AND cains.id_ciclo_escolar=".$_POST['ciclo_escolar']."
                              WHERE alum.id_status_alumno=1 AND cins.id_ciclo_escolar=".$_POST['ciclo_escolar']."  AND  cains.id_alumno IS NULL AND alum.id_institucion=".$_REQUEST['id_institucion'];
                             
                             $aResultado = $oConexion->query($sQuery);


                
               
                
            
             
}

// AGREGA EL CARNET DE COLEGIATURA PARA EL ALUMNO SELECCIONADO POR LOS MESES DE TODO EL CICLO
        public function createCol() 
    {


      $oConexion = $this->getConexion();
		  
		  if($_POST['id_status_ciclo']==="3"){
						
				if($_POST['id_nivel_educativo']==="1"){
						$aum_nivel=1;
					}else if($_POST['id_nivel_educativo']==="2"  && $_POST['grado']==6){
								$aum_nivel=1;
							}else if($_POST['id_nivel_educativo']==="3"  && $_POST['grado']>=3){
										$aum_nivel=1;
									}
			}else{
					$aum_nivel=0;
				
				}						

           
            $fecha=date("Y-m-d");  // selecciona la fecha del sistema
            $referencia="XXXXXX";

            if($aum_nivel==1){

                $sQuery ="INSERT INTO carnets_colegiatura(" .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_alumno, " .
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "id_cuota_colegiatura, " .
                          "fecha, " .
                          "referencia".
                          ")SELECT mes.id_mes,
                          trans.id_transporte_alumno, 
                          id_beca,
                           ".$_POST["id_alumno"].",
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           ".($_POST["id_cuota_colegiatura"]+1).",
                           '".$fecha."',
                           '".$referencia."'
                          FROM meses_ciclo AS mes
                          LEFT JOIN transportes_alumno AS trans 
                          ON mes.id_mes=trans.id_mes AND trans.id_alumno=".$_POST["id_alumno"]."
                          LEFT JOIN sistema_andes.alumnos AS alum
                          ON alum.id_alumno=trans.id_alumno
                          LEFT JOIN becas.becas AS becas 
                          ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                          WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                          WHERE mes.status=1 AND mes.id_institucion=".$_REQUEST['id_institucion']." order by mes.orden";       
                         
                    
                         $aResultado = $oConexion->query($sQuery);

               }else{

               			  $sQuery ="INSERT INTO carnets_colegiatura(" .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_alumno, " .
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "id_cuota_colegiatura, " .
                          "fecha, " .
                          "referencia".
                          ")SELECT mes.id_mes,
                          trans.id_transporte_alumno, 
                          id_beca,
                           ".$_POST["id_alumno"].",
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           ".$_POST["id_cuota_colegiatura"].",
                           '".$fecha."',
                           '".$referencia."'
                          FROM meses_ciclo AS mes
                          LEFT JOIN transportes_alumno AS trans 
                          ON mes.id_mes=trans.id_mes AND trans.id_alumno=".$_POST["id_alumno"]."
                          LEFT JOIN sistema_andes.alumnos AS alum
                          ON alum.id_alumno=trans.id_alumno
                          LEFT JOIN becas.becas AS becas 
                          ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                          WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                          WHERE mes.status=1 AND mes.id_institucion=".$_REQUEST['id_institucion']." order by mes.orden";       
                         
                    
                         $aResultado = $oConexion->query($sQuery);

               }
    } 

        // AGREGA LOS CARNETS DE COLEGIATURA PARA EL ALUMNO SELECCIONADO EN LOS MESES SELECCIONADOS
         public function createColMes() 
    {

            $oConexion = $this->getConexion();
		  
    		  if($_POST['id_status_ciclo']==="3"){
    						
    				if($_POST['id_nivel_educativo']==="1"){
    						$aum_nivel=1;
    					}else if($_POST['id_nivel_educativo']==="2"  && $_POST['grado']==6){
    								$aum_nivel=1;
    							}else if($_POST['id_nivel_educativo']==="3"  && $_POST['grado']>=3){
    										$aum_nivel=1;
    									}else{
                        $aum_nivel=0;
                      }
    			}else{
    					$aum_nivel=0;
    				
    				}						

            $referencia="4324343";
            $fecha=date("Y-m-d"); 
            $anio=date("Y");
            
            if($_POST["mes1"]<10){
              $mes=("0".$_POST["mes1"]);
            }else{
              $mes=($_POST["mes1"]);
            }

            $fecha_venc=$anio."-".$mes."-10";
            $id_dia=(date('w', strtotime($fecha_venc)));
            
            if($id_dia==0){
               $fecha_vencimiento = date("Y-m-d", strtotime($anio."-".$mes."-11"));
            }else{
               $fecha_vencimiento = date("Y-m-d", strtotime($fecha_venc));
            }           

            if($aum_nivel==='1'){


                 $sQuery = "INSERT INTO carnets_colegiatura(" .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_alumno, " .
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "id_cuota_colegiatura, " .
                          "fecha, " .
                          "fecha_vencimiento, ".
                          "referencia ".
                          ")SELECT mes.id_mes,
                          trans.id_transporte_alumno, 
                          id_beca,
                           ".$_POST["id_alumno"].",
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           ".($_POST["id_cuota_colegiatura"]+1).",
                           '".$fecha."', 
                           '".$fecha_vencimiento."',
                           '".$referencia."'
                          FROM meses_ciclo AS mes
                          LEFT JOIN transportes_alumno AS trans 
                          ON mes.id_mes=trans.id_mes AND trans.id_alumno=".$_POST["id_alumno"]."
                          LEFT JOIN sistema_andes.alumnos AS alum
                          ON alum.id_alumno=trans.id_alumno
                          LEFT JOIN becas.becas AS becas 
                          ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                          WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                          WHERE mes.status=1 AND mes.orden>=".$_POST["orden1"]." and mes.orden <=".$_POST["orden2"]." AND mes.id_institucion=".$_REQUEST['id_institucion'];   

                         
             $aResultado = $oConexion->query($sQuery);


         }else{

         	 $sQuery = "INSERT INTO carnets_colegiatura(" .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_alumno, " .
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "id_cuota_colegiatura, " .
                          "fecha, " .
                          "fecha_vencimiento, " .
                          "referencia".
                          ")SELECT mes.id_mes,
                          trans.id_transporte_alumno, 
                          id_beca,
                           ".$_POST["id_alumno"].",
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           ".$_POST["id_cuota_colegiatura"].",
                           '".$fecha."',
                           '".$fecha_vencimiento."',
                           '".$referencia."'
                          FROM meses_ciclo AS mes
                          LEFT JOIN transportes_alumno AS trans 
                          ON mes.id_mes=trans.id_mes AND trans.id_alumno=".$_POST["id_alumno"]."
                          LEFT JOIN sistema_andes.alumnos AS alum
                          ON alum.id_alumno=trans.id_alumno
                          LEFT JOIN becas.becas AS becas 
                          ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                          WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                          WHERE mes.status=1 AND mes.orden>=".$_POST["orden1"]." and mes.orden <=".$_POST["orden2"]." AND mes.id_institucion=".$_REQUEST['id_institucion'];   
                         
             $aResultado = $oConexion->query($sQuery);
         }
         
    } 

      // AGREGA EL CARNET DE COLEGIATURA PARA TODOS LOS ALUMNOS EN LOS MESES SELECCIONADOS
       public function createColMesAll() 
    {

            $oConexion = $this->getConexion();
            $fecha=date("Y-m-d");
            $anio=date("Y");
            $referencia='3243533';
            if($_POST["mes1"]<10){
              $mes=("0".$_POST["mes1"]);
            }else{
              $mes=($_POST["mes1"]);
            }

            $fecha_venc=$anio."-".$mes."-10";
            $id_dia=(date('w', strtotime($fecha_venc)));
            
            if($id_dia==0){
               $fecha_vencimiento = date("Y-m-d", strtotime($anio."-".$mes."-11"));
            }else{
               $fecha_vencimiento = date("Y-m-d", strtotime($fecha_venc));
            }           

              $sQuery = "INSERT INTO carnets_colegiatura(" .
                          "id_alumno, " .
                          "id_cuota_colegiatura, " .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "fecha_vencimiento, " .
                          "fecha, " .
                          "referencia " .
                          ")SELECT alum.id_alumno, 
                             CASE ".$_POST["id_status_ciclo"]." WHEN 3 THEN  
                              CASE  cins.id_nivel_educativo
                              WHEN 1 THEN  cins.id_cuota_colegiatura+1
                              WHEN 2 THEN  CASE WHEN alum.grado=6 THEN cins.id_cuota_colegiatura+1  ELSE  cins.id_cuota_colegiatura END
                              WHEN 3 THEN  CASE WHEN alum.grado=3 THEN cins.id_cuota_colegiatura+1  ELSE  cins.id_cuota_colegiatura END 
                              ELSE  cins.id_cuota_colegiatura END 
                              ELSE  cins.id_cuota_colegiatura END AS id_cuota_colegiatura,
                            mes.id_mes,
                            trans.id_transporte_alumno,
                            becas.id_beca,
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           '".$fecha_vencimiento."',
                           '".$fecha."',
                           '".$referencia."'
                            FROM sistema_andes.alumnos AS alum 
                            LEFT JOIN cxcandes.cuotas_colegiaturas AS cins
                            ON cins.id_nivel_educativo=alum.id_nivel_educativo AND (CASE WHEN cins.grado<>0 OR cins.grado='MAT' THEN cins.grado=alum.grado ELSE cins.grado=0 END) 
                            LEFT JOIN cxcandes.carnets_colegiatura AS cains
                            ON cains.id_alumno=alum.id_alumno  AND cains.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."
                            LEFT JOIN cxcandes.meses_ciclo AS mes
                            ON alum.id_institucion=mes.id_institucion
                            LEFT JOIN cxcandes.transportes_alumno AS trans
                            ON trans.id_alumno=alum.id_alumno AND trans.id_mes=mes.id_mes
                            LEFT JOIN becas.becas AS becas 
                            ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                            WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                            WHERE  mes.status=1  AND orden>=".$_POST["orden1"]." AND orden <=".$_POST["orden2"]."  AND  alum.id_institucion=".$_REQUEST['id_institucion']." AND alum.id_status_alumno=1 AND cins.id_ciclo_escolar=".$_POST['ciclo_escolar']." AND cains.id_alumno IS NULL";

             $aResultado = $oConexion->query($sQuery);
         
    } 


      // AGREGA EL CARNET DE COLEGIATURA PARA TODOS LOS ALUMNOS EN TODOS LOS MESES
       public function createAllCol() 
    {

            $oConexion = $this->getConexion();
            $fecha=date("Y-m-d");

                 $sQuery = "INSERT INTO carnets_colegiatura(" .
                          "id_alumno, " .
                          "id_cuota_colegiatura, " .
                          "id_mes,".
                          "id_cuota_transporte,".
                          "id_beca,".
                          "id_ciclo_escolar," .
                          "id_institucion, " .
                          "id_tipo_movimiento, " .
                          "fecha " .
                          ")SELECT alum.id_alumno, 
                           CASE ".$_POST["id_status_ciclo"]." WHEN 3 THEN  
                              CASE  cins.id_nivel_educativo
                              WHEN 1 THEN  cins.id_cuota_colegiatura+1
                              WHEN 2 THEN  CASE WHEN alum.grado=6 THEN cins.id_cuota_colegiatura+1  ELSE  cins.id_cuota_colegiatura END
                              WHEN 3 THEN  CASE WHEN alum.grado=3 THEN cins.id_cuota_colegiatura+1  ELSE  cins.id_cuota_colegiatura END 
                              ELSE  cins.id_cuota_colegiatura END 
                              ELSE  cins.id_cuota_colegiatura END AS id_cuota_colegiatura,
                            mes.id_mes, 
                            trans.id_transporte_alumno,
                            becas.id_beca,
                           ".$_POST["ciclo_escolar"].",
                           ".$_POST["id_institucion"].",
                           ".$_POST["id_tipo_movimiento"].",
                           ".$fecha."
                            FROM sistema_andes.alumnos AS alum 
                            LEFT JOIN cxcandes.cuotas_colegiaturas AS cins
                            ON cins.id_nivel_educativo=alum.id_nivel_educativo AND (CASE WHEN cins.grado<>0 OR cins.grado='MAT' THEN cins.grado=alum.grado ELSE cins.grado=0 END) 
                            LEFT JOIN cxcandes.carnets_colegiatura AS cains 
                            ON cains.id_alumno=alum.id_alumno  AND cains.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."
                            LEFT JOIN cxcandes.meses_ciclo AS mes
                            ON alum.id_institucion=mes.id_institucion
                            LEFT JOIN cxcandes.transportes_alumno AS trans
                            ON trans.id_alumno=alum.id_alumno AND trans.id_mes=mes.id_mes
                            LEFT JOIN becas.becas AS becas 
                            ON becas.matricula=(SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) AS matricula_anterior FROM sistema_andes.alumnos 
                            WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno)
                            WHERE  mes.status=1 AND alum.id_institucion=".$_REQUEST['id_institucion']." AND cins.id_ciclo_escolar=".$_REQUEST['id_ciclo_escolar']."
                            AND alum.id_status_alumno=1 AND cains.id_alumno IS NULL  order by mes.orden";

             $aResultado = $oConexion->query($sQuery);
         
    } 



    /**
     * Actualiza plazas
     *
     * @return Cake\Network\Response
     */
    public function update() {
     
    }

    /**
     * Elimina plazas
     *
     * @return Cake\Network\Response
     */
    public function delete() {
       
    }
}
