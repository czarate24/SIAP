<?php

namespace App\Controller;

use Exception;

class cmbTipoMovimientoController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {
      
        $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');
        // obtiene las Instituciones
   
         $sQuery = "SELECT  * FROM tipos_movimientos WHERE estatus=1 and id_institucion=".$intIdIns; 
        
        $aTipoMovimiento = $oConexion->query($sQuery);

        return $this->asJson(array(
            "success" => true,
            "message" => "Catalogo de Tipos de Movimientos",
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