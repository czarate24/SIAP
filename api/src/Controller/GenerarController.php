<?php

namespace App\Controller;

use Exception;

class GenerarController extends AppController {

    /**
     * Lee el catalogo de las plazas
     *
     * @return Cake\Network\Response
     */
    public function crearArch() {
        
       
        echo $_REQUEST['existe']."<br>";
        echo $_REQUEST['todos']."<br>";

       // echo $_REQUEST['nombre']."<br>";
        //echo $_REQUEST['id_usuario']."<br>";
        

        if ($_REQUEST['existe']=="true")
        {
            $cond="art.sta<>'B' and art.existe>0 ";
            
        }
        if ($_REQUEST['todos']=="true")   
        {
           
            $cond="art.sta<>'B' ";   
        }

        //print_r($cond);
        $oConexion = $this->getConexion();
        // obtiene todos los articulos de la cafeteria

                 $sQuery = "INSERT INTO inv_fis (grupo,gpo_descrip,cod_barr,descrip,marca,existe,costo,pvta,fec_alta,sta,exi_fis,cto_fis,uni_med,f_cant,f_costo,cont_fis,codigob,consec)
                     SELECT  art.grupo,
                    gpo.descrip AS gpo_descrip,
                    art.cod_barr,
                    art.descrip,
                    art.marca,
                    art.existe,
                    art.costo,
                    art.pvta,
                    art.fec_alta,
                    art.sta,
                    art.exi_fis,
                    art.cto_fis,
                    art.uni_med,
                    art.f_cant,
                    art.f_costo,
                    art.cont_fis,
                    art.codigob,
                    art.consec
                    FROM articulo AS art
                    INNER JOIN grupo AS gpo
                    ON art.grupo=gpo.grupo
                    WHERE ".$cond. "
                    ORDER BY gpo_descrip";

                  //  print_r($sQuery);

                   $aArticulos = $oConexion->query($sQuery);   
                   
                    $sQueryC = "INSERT INTO config_invfis (fecha_fisico,id_usuario,nom_usuario) values ('".date("Y-m-d H:i:s")."',".$_REQUEST['id_usuario'].",'".$_REQUEST['nombre']."')";

                    print_r($sQueryC);

                   $aConfiguracion = $oConexion->query($sQueryC);
                   
                    return $this->asJson(array(
                        "success" => true,
                        "message" => "Generado con éxito",
                        "records" => $aConfiguracion,
                        "metadata" => array(
                            "total_registros" => count($aConfiguracion)
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
