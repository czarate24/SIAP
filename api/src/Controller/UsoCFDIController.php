<?php

namespace App\Controller;

use Exception;

class UsoCFDIController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

        $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');

        //obtiene los usos cfdi
        $sQuery = "SELECT id_uso_cfdi, id_institucion, clave, uso, status FROM usos_cfdi WHERE id_institucion=".$intIdIns;
        
        $aUsoCFDI = $oConexion->query($sQuery);
        if (count($aUsoCFDI)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Uso cfdi",
                "records" => $aUsoCFDI,
                "metadata" => array(
                    "total_registros" => count($aUsoCFDI)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No Uso CFDI",
                "records" => $aUsoCFDI,
                "metadata" => array(
                    "total_registros" => count($aUsoCFDI)
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
            $sQuery = "INSERT INTO usos_cfdi(" .
                      "id_institucion, " .
                      "uso_cfdi_id, " .
                      "clave," .
                      "uso" .
                      ") VALUES (
                     '".$_POST["id_institucion"]."',
                     '".$_POST["uso_cfdi_id"]."',
                     '".$_POST["clave"]."',
                     UPPER('".$_POST["uso"]."')
                     )";

                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Uso CFDI",
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
            $sQuery = "UPDATE bancos SET 
            nombre ='".$_POST['nombre']."', 
            afiliacion ='".$_POST['afiliacion']."', 
            num_cta ='".$_POST['num_cta']."', 
            cta_CLABE ='".$_POST['cta_CLABE']."', 
            contacto ='".$_POST['contacto']."', 
            correo ='".$_POST['correo']."' 
            WHERE id_banco=".$_POST['id_banco'];
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
            $sQuery = "UPDATE usos_cfdi SET status =".$aRecord["status"]." WHERE id_uso_cfdi =".$aRecord["id_uso_cfdi"];
           
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
