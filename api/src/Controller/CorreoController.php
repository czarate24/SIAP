<?php

namespace App\Controller;

use Exception;

class CorreoController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $intIdCiclo = $this->request->query('id_ciclo_escolar');
        $intIdAlumno = $this->request->query('id_alumno');
        
        $sQuery = "SELECT  inst.nombre, ciclo.id_status_ciclo, 
              ciclo.ciclo, 
              alum.id_nivel_educativo, 
              alum.grado, 
              tutores.correo, 
              inst.telefonos 
              FROM sistema_andes.instituciones AS inst
              LEFT JOIN sistema_andes.alumnos AS alum
              ON alum.id_institucion=inst.id_institucion
              LEFT JOIN sistema_andes.familias AS fam
              ON alum.id_familia=fam.id_familia
              LEFT JOIN sistema_andes.detalle_tutores AS d_tuto
              ON d_tuto.id_familia=fam.id_familia
              LEFT JOIN sistema_andes.tutores AS tutores
              ON tutores.id_tutor=d_tuto.id_tutor
              LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
              ON ciclo.id_ciclo_escolar=".$intIdCiclo."
              WHERE alum.id_alumno=".$intIdAlumno;
        
        $aDatosAlum = $oConexion->query($sQuery);
        if (count($aDatosAlum)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Correo",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Correo",
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
