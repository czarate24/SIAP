<?php

namespace App\Controller;

use Exception;

class CarnetsInsController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');
        $intIdCiclo = $this->request->query('id_ciclo_escolar');

        $sQuery = "SELECT alum.id_alumno,
                    (SELECT CONCAT(LPAD(ncontrol,4,'0'),LPAD(matri,2,'0')) FROM sistema_andes.alumnos 
                          WHERE sistema_andes.alumnos.id_alumno=alum.id_alumno) AS matri, 
                    alum.matricula, 
                    alum.ap_paterno, 
                    alum.ap_materno, 
                    alum.nombre, 
                    alum.grado, 
                    alum.id_status_alumno, 
                    cins.id_cuota_inscripcion, 
                    alum.id_institucion,
                    cins.id_nivel_educativo,
                    UPPER(nivel.nombre) AS nivel_educativo,
                    carn.id_ciclo_escolar, 
                    carn.id_carnet_inscripcion, 
                    (SELECT id_status_ciclo 
                    FROM sistema_andes.ciclos_escolares 
                    WHERE id_ciclo_escolar=".$intIdCiclo.") AS id_status_ciclo
                    FROM sistema_andes.alumnos AS alum 
                    LEFT JOIN cxcandes.cuotas_inscripciones AS cins
                    ON (cins.grado=alum.grado OR cins.grado='')
                    INNER JOIN sistema_andes.niveles_educativos AS nivel
                    ON alum.id_nivel_educativo=nivel.id_nivel_educativo AND alum.id_nivel_educativo=cins.id_nivel_educativo
                    LEFT JOIN cxcandes.carnets_inscripcion AS carn
                    ON  carn.id_alumno=alum.id_alumno AND (carn.id_ciclo_escolar=".$intIdCiclo.")
                    WHERE alum.id_status_alumno=1 AND cins.id_ciclo_escolar=".$intIdCiclo." and alum.id_institucion=".$intIdIns;


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
                      "referencia, " .                    
                      "id_alumno, " .
                      "id_ciclo_escolar," .
                      "id_institucion, " .
                      "id_tipo_movimiento, " .
                      "id_cuota_inscripcion, " .
                      "fecha " .
                      ")
                       SELECT referencia, 
                     ".$_POST["id_alumno"].",
                     ".$_POST["ciclo_escolar"].",
                     ".$_POST["id_institucion"].",
                     ".$id_tipo_movimiento.",
                     ".($_POST["id_cuota_inscripcion"]+1).",
                     ".$fecha."
                     from referencias
                     where id_tipo_movimiento=1 AND id_alumno=".$_POST["id_alumno"];
                
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
                      "referencia, " .                    
                      "id_alumno, " .
                      "id_ciclo_escolar," .
                      "id_institucion, " .
                      "id_tipo_movimiento, " .
                      "id_cuota_inscripcion, " .
                      "fecha " .
                      ")
                       SELECT referencia, 
                     ".$_POST["id_alumno"].",
                     ".$_POST["ciclo_escolar"].",
                     ".$_POST["id_institucion"].",
                     ".$id_tipo_movimiento.",
                     ".$_POST["id_cuota_inscripcion"].",
                     ".$fecha."
                     from referencias
                     where id_alumno=".$_POST["id_alumno"]." and id_tipo_movimiento=1";
                    
                
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
                $id_tipo_movimiento=1;
                $id_status_ciclo=$_REQUEST['id_status_ciclo'];
          


                        $sQuery = "INSERT INTO carnets_inscripcion(" .
                              "referencia,".
                              "id_alumno, " .
                              "id_cuota_inscripcion, " .
                              "id_ciclo_escolar," .
                              "id_institucion, " .
                              "id_tipo_movimiento, " .
                              "fecha " .
                              ")
                              SELECT  ref.referencia,
                              alum.id_alumno, 
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
                             '".$fecha."'
                              FROM sistema_andes.alumnos AS alum 
                              LEFT JOIN cxcandes.cuotas_inscripciones AS cins
                              ON cins.id_nivel_educativo=alum.id_nivel_educativo AND (CASE WHEN cins.grado<>0 OR cins.grado='MAT' THEN cins.grado=alum.grado ELSE cins.grado=0 END) 
                              LEFT JOIN cxcandes.carnets_inscripcion AS cains
                              ON cains.id_alumno=alum.id_alumno AND cains.id_ciclo_escolar=".$_POST['ciclo_escolar']."
                              LEFT JOIN referencias as ref
                              ON ref.id_alumno=alum.id_alumno
                              WHERE ref.id_tipo_movimiento=1 AND alum.id_status_alumno=1 AND cins.id_ciclo_escolar=".$_POST['ciclo_escolar']."  AND  cains.id_alumno IS NULL AND alum.id_institucion=".$_REQUEST['id_institucion'];
                             
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
