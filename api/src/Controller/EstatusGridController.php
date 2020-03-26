<?php

namespace App\Controller;

use Exception;

class EstatusGridController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

        $oConexion = $this->getConexion();
        //$intIdIns = $this->request->query('id_ins');

        //obtiene estatus ingresos
        $sQuery = "SELECT id_estatus_ingreso,descripcion,estatus FROM estatus_ingresos";
       // print_r($sQuery);
        
        $aDatosEstatus = $oConexion->query($sQuery);
        if (count($aDatosEstatus)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Estatus Ingresos",
                "records" => $aDatosEstatus,
                "metadata" => array(
                    "total_registros" => count($aDatosEstatus)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => false,
                "message" => "No hay Estatus Ingresos",
                "records" => $aDatosEstatus,
                "metadata" => array(
                    "total_registros" => count($aDatosEstatus)
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
          //Configure::write('debug', 0);
            $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO estatus_ingresos(descripcion) VALUES ('".$_POST["descripcion"]."')";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Estatus",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));

             
    } //Fin de la función agredarDatos


      public function actualiza() 
    {

                //Configure::write('debug', 0);
            $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "UPDATE estatus_ingresos SET descripcion ='".$_POST['descripcion']."' WHERE id_estatus_ingreso=".$_POST['id_estatus_ingreso'];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Actualizar estatus",
                       
                       
                       ));
                
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
            $sQuery = "UPDATE estatus_ingresos SET estatus =".$aRecord["estatus"]." WHERE id_estatus_ingreso =".$aRecord["id_estatus_ingreso"];
           
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
