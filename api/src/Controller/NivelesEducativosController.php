<?php

namespace App\Controller;

use Exception;

class NivelesEducativosController extends AppController {

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
        $sQuery = "SELECT id_nivel_educativo,UPPER(nombre) AS nombre,clave,id_institucion FROM sistema_andes.niveles_educativos WHERE STATUS=1 and id_institucion=".$intIdIns;
       // print_r($sQuery);
        
        $aDatosNivel = $oConexion->query($sQuery);
        if (count($aDatosNivel)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Niveles Educativos",
                "records" => $aDatosNivel,
                "metadata" => array(
                    "total_registros" => count($aDatosNivel)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => false,
                "message" => "No hay Niveles Educativos",
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
