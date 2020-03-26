<?php

namespace App\Controller;

use Exception;

class UsoCFDIDfController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {
       
        $oConexion = $this->getConexion();

        // obtiene las Instituciones
        $intIdIns = $this->request->query('id_ins');
   
         //Obtiene los datos de dos bases de datos diferentes.
        $sQuery = "SELECT  cxcandes.usos_cfdi.id_uso_cfdi, 
                cxcandes.usos_cfdi.id_institucion, 
                cxcandes.usos_cfdi.clave, 
                cxcandes.usos_cfdi.uso,
                wscfdi.uso_cfdi.tipo_persona,
                wscfdi.uso_cfdi.id AS uso_cfdi_id
                FROM cxcandes.usos_cfdi
                INNER JOIN wscfdi.uso_cfdi ON 
                wscfdi.uso_cfdi.clave COLLATE utf8_unicode_ci=cxcandes.usos_cfdi.clave COLLATE utf8_unicode_ci
                WHERE cxcandes.usos_cfdi.status=1 AND cxcandes.usos_cfdi.id_institucion=".$intIdIns; 
        
        $aUsoCFDI = $oConexion->query($sQuery);

        return $this->asJson(array(
            "success" => true,
            "message" => "Catalogo de UsoCFDI",
            "records" => $aUsoCFDI,
            "metadata" => array(
                "total_registros" => count($aUsoCFDI)
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
            $sQuery = "INSERT INTO plaza (" .
                    "empresa_id, " .
                    "plaza, " .
                    "plaza2, " .
                    "ciudad, " .
                    "estado, " .
                    "direccion_sucursal, " .
                    "telefono_pedido, " .
                    "telefono_queja, " .
                    "telefono_queja2, " .
                    "permiso, " .
                    "oficio, " .
                    "precio_gas, " .
                    "precio_aditivo, " .
                    "precio_aditivoc, " .
                    "factor_control, " .
                    "factor_space, " .
                    "litros_vale, " .
                    "clientes_estacionario, " .
                    "clientes_portatil, " .
                    "limite_descarga, " .
                    "fecha_lista, " .
                    "otorga_puntos, " .
                    "estatus, " .
                    "fecha_operacion, " .
                    "fecha_planta, " .
                    "tarifa_id" .
                ") VALUES (" .
                    "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?" .
                ")";
            $aQueryParams = array(
                $aRecord["empresa_id"],
                $aRecord["plaza"],
                $aRecord["plaza2"],
                $aRecord["ciudad"],
                $aRecord["estado"],
                $aRecord["direccion_sucursal"],
                $aRecord["telefono_pedido"],
                $aRecord["telefono_queja"],
                $aRecord["telefono_queja2"],
                $aRecord["permiso"],
                "",
                0,
                0,
                0,
                $aRecord["factor_control"],
                $aRecord["factor_space"],
                10,
                $aRecord["clientes_estacionario"],
                0,
                $aRecord["limite_descarga"],
                0,
                $aRecord["otorga_puntos"],
                "",
                "0000-00-00",
                "0000-00-00",
                1
            );
            $aResultado = $oConexion->query($sQuery, $aQueryParams);
            $aRecord["id"] = $oConexion->driver()->lastInsertId();
        }
        unset($aRecord);

        // procesa los records para regresarlos y que los campos se actualicen en el store
        $aRecords = array_map(function($aRecord) {
            return array(
                "id" => $aRecord["id"],
                "clientId" => $aRecord["clientId"]
            );
        }, $aRecords);

        return $this->asJson(array(
            "success" => true,
            "message" => "Plazas agregadas",
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
