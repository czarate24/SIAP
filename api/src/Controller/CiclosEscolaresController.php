<?php

namespace App\Controller;

use Exception;

class CiclosEscolaresController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

         $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');

        //obtiene los ciclos
        $sQuery = "SELECT c.id_ciclo_escolar,sistema_andes.c.ciclo,c.fechai,c.fechaf,
               c.id_institucion,c.id_status_ciclo,UPPER(s.estatus) as estatus_des
        FROM sistema_andes.ciclos_escolares AS c  INNER JOIN sistema_andes.status_ciclo AS s
        ON c.id_status_ciclo = s.id_status_ciclo
        WHERE c.id_institucion=".$intIdIns." ORDER BY c.fechai";
       // print_r($sQuery);
        
        $aDatosCiclos = $oConexion->query($sQuery);
        if (count($aDatosCiclos)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Catalogo de CiclosEscolares",
                "records" => $aDatosCiclos,
                "metadata" => array(
                    "total_registros" => count($aDatosCiclos)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => false,
                "message" => "No hay ciclos escolares",
                "records" => $aDatosCiclos,
                "metadata" => array(
                    "total_registros" => count($aDatosCiclos)
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
    public function create() 
    {

             
    } //Fin de la función agredarDatos



    /**
     * Actualiza plazas
     *
     * @return Cake\Network\Response
     */
    public function update() {
        //Configure::write('debug',2);
        $oConexion = $this->getConexion();
        $aDatos = $this->request->data;

         //pr($aDatos);
         //exit;

        $aRecords = json_decode($aDatos["records"], true);
        //$aRecords = $aDatos["records"];

       // echo "CARLOS"; $aRecord["razon_social"]

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE instituciones_datos SET estatus =".$aRecord["estatus"]." WHERE id_institucion_dato =".$aRecord["id_institucion_dato"];
           
           // pr($sQuery);
            //pr($aQueryParams);
            $oConexion->query($sQuery);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Registro Actualizado"
        )); 
    }

    /**
     * Elimina plazas
     *
     * @return Cake\Network\Response
     */
    public function delete() {
        $oConexion = $this->getConexion();

        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "DELETE FROM plaza " .
            "WHERE id = ?";
            $aQueryParams = array(
                $aRecord["id"]
            );
            $oConexion->query($sQuery, $aQueryParams);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Plazas eliminadas"
        ));
    }
}
