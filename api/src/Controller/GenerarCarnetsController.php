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

        // obtiene las Instituciones
   
         $sQuery = "SELECT  * FROM tipos_movimientos WHERE estatus=1"; 
        
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
       $oConexion = $this->getConexion();
           // agrega el registro det tipo movimiento.
            $sQuery = "INSERT INTO carnets_inscripcion(" .
                      "id_alumno, " .
                      "id_ciclo_escolar," .
                      "id_institucion," .
                      "id_tipo_movimiento," .
                      "id_cuota_inscripcion," .
                      "referencia," .
                      "fecha" .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_ciclo_escolar"]."'
                     '".$_POST["id_institucion"]."'
                     '".$_POST["id_tipo_movimiento"]."'
                     '".$_POST["id_cuota_inscripcion"]."'
                     '".$_POST["referencia"]."'
                     '".$_POST["fecha"]."'
                     )";
                     // print_r($sQuery);
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