<?php

namespace App\Controller;

use Exception;

class PlazasController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function read() {
        $oConexion = $this->getConexion();

        // obtiene todas las plazas
        $sQuery = "SELECT * " .
            "FROM plaza";
        $aPlazas = $oConexion->query($sQuery);

        return $this->asJson(array(
            "success" => true,
            "message" => "Catalogo de plazas",
            "records" => $aPlazas,
            "metadata" => array(
                "total_registros" => count($aPlazas)
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
        $oConexion = $this->getConexion();

        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE plaza SET " .
                    "empresa_id = ?, " .
                    "plaza = ?, " .
                    "plaza2 = ?, " .
                    "ciudad = ?, " .
                    "estado = ?, " .
                    "direccion_sucursal = ?, " .
                    "telefono_pedido = ?, " .
                    "telefono_queja = ?, " .
                    "telefono_queja2 = ?, " .
                    "permiso = ?, " .
                    "factor_control = ?, " .
                    "factor_space = ?, " .
                    "clientes_estacionario = ?, " .
                    "limite_descarga = ?, " .
                    "otorga_puntos = ? " .
                "WHERE id = ?";
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
                $aRecord["factor_control"],
                $aRecord["factor_space"],
                $aRecord["clientes_estacionario"],
                $aRecord["limite_descarga"],
                $aRecord["otorga_puntos"],
                $aRecord["id"]
            );
            $oConexion->query($sQuery, $aQueryParams);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Plazas actualizadas"
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
