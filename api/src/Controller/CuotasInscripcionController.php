<?php

namespace App\Controller;

use Exception;

class CuotasInscripcionController extends AppController {

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

                $sQuery="SELECT ci.id_cuota_inscripcion,
                ci.id_institucion,
                ci.id_ciclo_escolar,
                ci.id_nivel_educativo,
                ci.cuota,
                CASE  ci.id_nivel_educativo
                WHEN 1 THEN ci.grado
                ELSE ''
                END AS grado,  
                ci.descripcion,
                UPPER(ne.nombre) AS descrip_nivel, 
                ci.estatus
                FROM cxcandes.cuotas_inscripciones AS ci 
                INNER JOIN sistema_andes.niveles_educativos AS ne
                ON ci.id_nivel_educativo=ne.id_nivel_educativo
                WHERE ci.id_institucion=".$intIdIns." AND ci.id_ciclo_escolar=".$intIdCiclo." AND ci.estatus=1";

        //$sQuery = "SELECT id_nivel_educativo,UPPER(nombre) AS nombre,clave,id_institucion FROM sistema_andes.niveles_educativos WHERE STATUS=1 and id_institucion=".$intIdIns;
       // print_r($sQuery);
        
        $aDatosCuotasIns = $oConexion->query($sQuery);
        if (count($aDatosCuotasIns)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Grados",
                "records" => $aDatosCuotasIns,
                "metadata" => array(
                    "total_registros" => count($aDatosCuotasIns)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Inscriopcones",
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

        if (!isset($_POST["grado"])) {
             $_POST["grado"]="";
        }

         $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO cuotas_inscripciones(" .
                      "id_institucion, " .
                      "id_ciclo_escolar, " .
                      "id_nivel_educativo,".
                      "grado,".
                      "descripcion,".
                      "cuota,".
                      "fecha_alta, " .
                      "usuario_alta " .
                      ") VALUES (
                      ".$_POST["id_institucion"].",
                      ".$_POST["id_ciclo_escolar"].",
                      ".$_POST["id_nivel_educativo"].",
                      '".$_POST["grado"]."',
                      '".$_POST["descripcion"]."',
                      ".$_POST["cuota"].",
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
         if (!isset($_POST["grado"])) {
             $_POST["grado"]="";
        }
            $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "UPDATE cuotas_inscripciones SET descripcion ='".$_POST['descripcion']."',
                       id_nivel_educativo=".$_POST['id_nivel_educativo'].",
                       grado = '".$_POST['grado']."',
                       cuota = ".$_POST['cuota']." 
                         WHERE id_cuota_inscripcion=".$_POST['id_cuota_inscripcion'];
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
            $sQuery = "UPDATE cuotas_inscripciones SET estatus =".$aRecord["estatus"]." WHERE id_cuota_inscripcion =".$aRecord["id_cuota_inscripcion"];
           
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
