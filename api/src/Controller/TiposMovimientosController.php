<?php

namespace App\Controller;

use Exception;

class TiposMovimientosController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        //obtiene las familias
        $intIdIns = $this->request->query('id_ins');
        $sQuery = "SELECT id_tipo_movimiento, movimiento, id_institucion, estatus 
                         FROM tipos_movimientos 
                         WHERE id_institucion=".$intIdIns; 

        
        $aDatosFact = $oConexion->query($sQuery);
        if (count($aDatosFact)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Tipos Movimientos",
                "records" => $aDatosFact,
                "metadata" => array(
                    "total_registros" => count($aDatosFact)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Tipos Movimientos",
                "records" => $aDatosFact,
                "metadata" => array(
                    "total_registros" => count($aDatosFact)
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
            $oConexion = $this->getConexion();
           // agrega el registro det tipo movimiento.
            $sQuery = "INSERT INTO tipos_movimientos(" .
                      "movimiento, " .
                      "id_institucion" .
                      ") VALUES (
                     '".$_POST["movimiento"]."',
                     '".$_POST["id_institucion"]."'
                     )";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Tipos Movimientos",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));
             
    } //Fin de la función agredar
            


      public function actualiza() 
    {
        $oConexion = $this->getConexion();
        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);
        
        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE tipos_movimientos SET estatus =".$aRecord["estatus"]." WHERE id_tipo_movimiento =".$aRecord["id_tipo_movimiento"];
          
            $oConexion->query($sQuery);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Registro Actualizado"
        ));              

                
    } //Fin de la función agredarDatos



    /**
     * Actualiza plazas
     *
     * @return Cake\Network\Response
     */
    public function update() {
          $oConexion = $this->getConexion();
        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE tipos_movimientos SET " .
                "movimiento = ? " .
                "WHERE id_tipo_movimiento = ?";

            $aQueryParams = array(
                $aRecord["movimiento"],
                $aRecord["id_tipo_movimiento"]
            );
            $oConexion->query($sQuery, $aQueryParams);
    }
}

    /**
     * Elimina plazas
     *
     * @return Cake\Network\Response
     */
    public function delete() {
       
    }
}
