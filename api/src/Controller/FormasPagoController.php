<?php

namespace App\Controller;

use Exception;

class FormasPagoController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {

        $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');

        //obtiene las Instituciones
        $sQuery = "SELECT  * FROM cxcandes.formas_pago 
        WHERE id_institucion=".$intIdIns." ORDER BY estatus desc";
        //print_r($sQuery);
        
        $aDatosFormasPago = $oConexion->query($sQuery);

        return $this->asJson(array(
            "success" => true,
            "message" => "Catalogo de Formas de pago",
            "records" => $aDatosFormasPago,
            "metadata" => array(
                "total_registros" => count($aDatosFormasPago)
            )
        ));
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
            $sQuery = "INSERT INTO formas_pago(" .
                      "id_institucion, " .
                      "forma_pago, " .
                      "clave," .
                      "forma_pago_id," .
                      "fecha_alta, " .
                      "usuario_alta " .
                      ") VALUES (
                      ".$_POST["id_institucion"].",
                     '".$_POST["forma_pago"]."',
                     '".$_POST["clave"]."',
                     '".$_POST["id"]."',
                      ".'2019-07-17'.",
                      ".'1'.
                      ")";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Forma de pago",
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
            $sQuery = "UPDATE formas_pago SET forma_pago ='".$_POST['forma_pago']."', clave ='".$_POST['clave']."' WHERE id_forma_pago=".$_POST['id_forma_pago'];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Actualizar Forma de pago",
                       
                       
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
        $aRecords = json_decode($aDatos["records"], true);
        //$aRecords = $aDatos["records"];

       // echo "CARLOS"; $aRecord["razon_social"]

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE formas_pago SET estatus =".$aRecord["estatus"]." WHERE id_forma_pago =".$aRecord["id_forma_pago"];
           
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
