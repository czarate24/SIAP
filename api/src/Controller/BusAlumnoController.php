<?php

namespace App\Controller;

use Exception;

class BusAlumnoController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {


        $oConexion = $this->getConexion();
        $idIns = $this->request->query('id_institucion');
        $intIdCiclo=$this->request->query('id_ciclo_escolar');
        $sQuery = "SELECT a.id_alumno,
                CONCAT(LPAD(a.ncontrol,4,0),LPAD(a.matri,2,0),' ') AS matricula, 
                a.matricula AS matricula_nueva,
                a.ap_paterno,
                a.ap_materno,
                a.nombre,
                UPPER(nivel.nombre) AS nivel_educativo,
                a.grado
                FROM sistema_andes.alumnos AS a
                LEFT JOIN sistema_andes.niveles_educativos AS nivel
                ON a.id_nivel_educativo=nivel.id_nivel_educativo 
                WHERE id_status_alumno=1 AND a.id_institucion=".$idIns;
        
        $aDatosAlum = $oConexion->query($sQuery);
        if (count($aDatosAlum)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Alumnos",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Alumnos",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));

        }  
    }

    /**
     * Crea Instituciones
     *
     * @return Cake\Network\Response
     */
    public function create() 
    {
         
             
    } //Fin de la función agredarDatos


  
 

      public function actualiza() 
    {

     
                
    } //Fin de la función agredarDatos



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
