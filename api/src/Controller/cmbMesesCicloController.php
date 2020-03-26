<?php

namespace App\Controller;

use Exception;

class cmbMesesCicloController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $IdIns = $this->request->query('id_ins');

        $sQuery = "SELECT mes, id_mes, orden FROM meses_ciclo WHERE status=1 and id_institucion=".$IdIns." order by orden";
        
        $aDatosMes = $oConexion->query($sQuery);
        if (count($aDatosMes)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Mes",
                "records" => $aDatosMes,
                "metadata" => array(
                    "total_registros" => count($aDatosMes)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Mes",
                "records" => $aDatosMes,
                "metadata" => array(
                    "total_registros" => count($aDatosMes)
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

    // Agrega datos de Carnet al alumno seleccionado
    public function create() {
    
             
    } //Fin de la función agredarDatos
 
 

      public function actualiza() 
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
