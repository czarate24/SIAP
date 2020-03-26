<?php

namespace App\Controller;

use Exception;

class cmbFormasPagoController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {
      
        $oConexion = $this->getConexion();
        $intIdIns = 1;
        // obtiene las Instituciones
   
         $sQuery = "SELECT  id_forma_pago, forma_pago FROM formas_pago WHERE estatus=1 and id_institucion=".$intIdIns; 
        
        $aTipoMovimiento = $oConexion->query($sQuery);

        return $this->asJson(array(
            "success" => true,
            "message" => "Catalogo de Formas de Pago",
            "records" => $aTipoMovimiento,
            "metadata" => array(
                "total_registros" => count($aTipoMovimiento)
            )
        ));
    }

    /**
     * Crea plazas
     *
     * @return Cake\Network\Response
     */
    public function create() {
      
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