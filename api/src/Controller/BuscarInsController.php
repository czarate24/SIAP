<?php

namespace App\Controller;

use Exception;

class BuscarInsController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {

        $oConexion = $this->getConexion();

        //obtiene las Instituciones
        $sQuery = "SELECT  * FROM cxcandes.instituciones_datos
                           WHERE id_institucion=".$_REQUEST['id_institucion']."";  
         //print_f($sQuery);
        $aDatosInstituciones = $oConexion->query($sQuery);
        return $this->asJson(array(
            "success" => true,
            "message" => "Buscar Instituciones",
            "records" => $aDatosInstituciones,
            "metadata" => array(
                "total_registros" => count($aDatosInstituciones)
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
        $aDatos = $_POST;
        $aRecords = json_decode($aDatos["records"], true);
        foreach ($aRecords as &$aRecord) {
            $aRecord["clientId"] = $aRecord["id"];

            // agrega el registro de la plaza
            $sQuery = "INSERT INTO instituciones_datos (" .
                    "id_institucion, " .
                    "razon_social, " .
                    "rfc, " .
                    "calle, " .
                    "numero_exterior, " .
                    "numero_interior, " .
                    "colonia, " .
                    "codigo_postal, " .
                    "ruta_logo, " .
                    "fecha_alta, " .
                    "usuario_alta, " .
                ") VALUES (" .
                    "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?" .
                ")";
            $aQueryParams = array(
                $aRecord["id_institucion"],
                $aRecord["razon_social"],
                $aRecord["rfc"],
                $aRecord["calle"],
                $aRecord["numero_exterior"],
                $aRecord["numero_interior"],
                $aRecord["colonia"],
                $aRecord["codigo_postal"],
                $aRecord["ruta_logo"],
                "2019-07-17",
                1   
            );
            $aResultado = $oConexion->query($sQuery, $aQueryParams);
            $aRecord["id_institucion_dato"] = $oConexion->driver()->lastInsertId();
        }
        unset($aRecord);

        // procesa los records para regresarlos y que los campos se actualicen en el store
        $aRecords = array_map(function($aRecord) {
            return array(
                "id_institucion_dato" => $aRecord["id_institucion_dato"],
                "razon_social" => $aRecord["razon_social"]
            );
        }, $aRecords);

        return $this->asJson(array(
            "success" => true,
            "message" => "Institución Agregada",
            "records" => $aRecords
        ));
    }

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

       // echo "CARLOS";

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE inv_fis SET " .
                    "grupo = ?, " .
                    "gpo_descrip = ?, " .
                    "cod_barr = ?, " .
                    "descrip = ?, " .
                    "marca = ?, " .
                    "existe = ?, " .
                    "costo = ?, " .
                    "pvta = ?, " .
                    "fec_alta = ?, " .
                    "sta = ?, " .
                    "exi_fis = ?, " .
                    "cto_fis = ?, " .
                    "uni_med = ?, " .
                    "cont_fis = ?," .
                    "codigob = ?, " .
                    "consec = ? " .
                "WHERE id = ?";
            $aQueryParams = array(
                $aRecord["grupo"],
                $aRecord["gpo_descrip"],
                $aRecord["cod_barr"],
                $aRecord["descrip"],
                $aRecord["marca"],
                $aRecord["existe"],
                $aRecord["costo"],
                $aRecord["pvta"],
                $aRecord["fec_alta"],
                $aRecord["sta"],
                $aRecord["exi_fis"],
                $aRecord["cto_fis"],
                $aRecord["uni_med"],
                $aRecord["cont_fis"],
                $aRecord["codigob"],
                $aRecord["consec"],
                $aRecord["id_articulo"]
            );
           // pr($sQuery);
            //pr($aQueryParams);
            $oConexion->query($sQuery, $aQueryParams);
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
