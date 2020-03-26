<?php

namespace App\Controller;

use Exception;

class CuotasTransporteController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

         $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');
       // $intIdNivel = $this->request->query('id_nivel_educativo');
        $intIdCiclo = $this->request->query('id_ciclo_escolar');

        //obtiene los ciclos

            $sQuery="SELECT ci.id_cuota_transporte,ci.id_institucion,ci.id_ciclo_escolar,ci.cuota,ci.descripcion,UPPER(observacion) AS observacion, ci.estatus
                FROM cxcandes.cuotas_transportes AS ci 
                WHERE ci.id_institucion=".$intIdIns." AND ci.id_ciclo_escolar=".$intIdCiclo;

        //$sQuery = "SELECT id_nivel_educativo,UPPER(nombre) AS nombre,clave,id_institucion FROM sistema_andes.niveles_educativos WHERE STATUS=1 and id_institucion=".$intIdIns;
       // print_r($sQuery);
        
        $aDatosCuotasIns = $oConexion->query($sQuery);
        if (count($aDatosCuotasIns)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Transportes",
                "records" => $aDatosCuotasIns,
                "metadata" => array(
                    "total_registros" => count($aDatosCuotasIns)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Transportes",
                "records" => $aDatosCuotasIns,
                "metadata" => array(
                    "total_registros" => count($aDatosCuotasIns)
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
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO cuotas_transportes(" .
                      "id_institucion, " .
                      "id_ciclo_escolar, " .
                      "observacion,".
                      "descripcion,".
                      "cuota,".
                      "fecha_alta, " .
                      "usuario_alta " .
                      ") VALUES (
                      ".$_POST["id_institucion"].",
                      ".$_POST["id_ciclo_escolar"].",
                     '".$_POST["observacion"]."',
                      '".$_POST["descripcion"]."',
                      ".$_POST["cuota"].",
                      ".'2019-07-17'.",
                      ".'1'.
                      ")";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Transporte",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));

             
    } //Fin de la función agredarDatos


    public function actualiza() 
    {

                //Configure::write('debug', 0);
         if (!isset($_POST["grado"])) {
             $_POST["grado"]="";
        }
            $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "UPDATE cuotas_transportes SET descripcion ='".$_POST['descripcion']."',
                       observacion = '".$_POST['observacion']."',
                       cuota = ".$_POST['cuota']." 
                         WHERE id_cuota_transporte=".$_POST['id_cuota_transporte'];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Actualizar cuota",
                       
                       
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
            $sQuery = "UPDATE cuotas_transportes SET estatus =".$aRecord["estatus"]." WHERE id_cuota_transporte =".$aRecord["id_cuota_transporte"];
           
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
