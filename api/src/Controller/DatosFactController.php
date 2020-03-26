<?php

namespace App\Controller;

use Exception;

class DatosFactController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        //obtiene las familias
        $intIdFamilia = $this->request->query('id_familia');
        $sQuery = "SELECT id_dato_facturacion, id_familia, rfc, razon_social, usoCFDI, estatus, calle, colonia, numero_interior, numero_exterior, codigo_postal, predeterminado,
                        estado, localidad, municipio, pais 
                        FROM datos_facturacion 
                        WHERE id_familia=".$intIdFamilia;
        
        //$sQuery = "SELECT id_dato_facturacion, id_alumno, rfc, razon_social, usoCFDI, estatus FROM datos_facturacion";
        
        $aDatosFact = $oConexion->query($sQuery);
        if (count($aDatosFact)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Facturacion",
                "records" => $aDatosFact,
                "metadata" => array(
                    "total_registros" => count($aDatosFact)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Facturacion",
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
        
        if($_POST["predeterminado"]==1){
             $sQuery = "UPDATE datos_facturacion set predeterminado=0 WHERE predeterminado=1 and id_familia=".$_POST["id_familia"];        
             $aDatosFact = $oConexion->query($sQuery);
        }
       

        $sQuery = "INSERT INTO datos_facturacion(" .
                      "id_familia, " .
                      "rfc," .
                      "razon_social, " .
                      "calle, " .
                      "colonia, " .
                      "numero_interior, " .
                      "numero_exterior, " .
                      "codigo_postal, " .
                      "estado, " .
                      "localidad, " .
                      "municipio, " .
                      "pais, " .
                      "predeterminado, " .
                      "usoCFDI " .
                      ") VALUES (
                     '".$_POST["id_familia"]."',
                     '".$_POST["rfc"]."',
                     '".$_POST["razon_social"]."',
                     '".$_POST["calle"]."',
                     '".$_POST["colonia"]."',
                     '".$_POST["numero_exterior"]."',
                     '".$_POST["numero_interior"]."',
                     '".$_POST["codigo_postal"]."',
                     '".$_POST["estado"]."',
                     '".$_POST["localidad"]."',
                     '".$_POST["municipio"]."',
                     '".$_POST["pais"]."',
                     '".$_POST["predeterminado"]."',
                     '".$_POST["usoCFDI"]."'
                     )";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Bancos",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));
             
    } //Fin de la función agredarDatos


      public function actualiza() 
    {

            $oConexion = $this->getConexion();

        
            if($_POST["predeterminado"]==1){
                 $sQuery = "UPDATE datos_facturacion set predeterminado=0 WHERE predeterminado=1 and id_familia=".$_POST["id_familia"];        
                 $aDatosFact = $oConexion->query($sQuery);
            }
            $sQuery = "UPDATE datos_facturacion SET 
            id_familia ='".$_POST['id_familia']."', 
            rfc ='".$_POST['rfc']."', 
            razon_social ='".$_POST['razon_social']."', 
            calle ='".$_POST['calle']."',
            colonia ='".$_POST['colonia']."',
            numero_exterior ='".$_POST['numero_exterior']."',
            numero_interior ='".$_POST['numero_interior']."',
            codigo_postal ='".$_POST['codigo_postal']."',
            estado ='".$_POST['estado']."',
            localidad ='".$_POST['localidad']."',
            municipio ='".$_POST['municipio']."',
            pais ='".$_POST['pais']."',
            predeterminado ='".$_POST['predeterminado']."',
            usoCFDI ='".$_POST['usoCFDI']."'
            WHERE id_dato_facturacion=".$_POST['id_dato_facturacion'];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Actualizar Datos Facturación",
                       
                       
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
            $sQuery = "UPDATE datos_facturacion SET estatus =".$aRecord["estatus"]." 
                       WHERE id_dato_facturacion =".$aRecord["id_dato_facturacion"];
           
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
