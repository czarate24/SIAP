<?php

namespace App\Controller;

use Exception;

class TutoresController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $Ccontrol = $this->request->query('ncontrol');
       // $intIdNivel = $this->request->query('id_nivel_educativo');
       
        //obtiene las familias
        $sQuery = "SELECT f.id_familia,f.ncontrol,dt.id_tutor,t.ap_paterno,t.ap_materno,t.nombre,t.parentesco,t.correo
                    FROM sistema_andes.familias AS f 
                    INNER JOIN sistema_andes.detalle_tutores AS dt
                        ON f.id_familia=dt.id_familia
                    INNER JOIN sistema_andes.tutores AS t
                        ON dt.id_tutor=t.id_tutor
                    WHERE f.ncontrol=".$Ccontrol;
       // print_r($sQuery);
        
        $aDatosFact = $oConexion->query($sQuery);
        if (count($aDatosFact)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Tutores",
                "records" => $aDatosFact,
                "metadata" => array(
                    "total_registros" => count($aDatosFact)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Tutores",
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
                  //Configure::write('debug', 0);
            $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO bancos(" .
                      "nombre, " .
                      "afiliacion," .
                      "num_cta, " .
                      "cta_CLABE, " .
                      "contacto, " .
                      "correo " .
                      ") VALUES (
                     '".$_POST["nombre"]."',
                     '".$_POST["afiliacion"]."',
                     '".$_POST["num_cta"]."',
                     '".$_POST["cta_CLABE"]."',
                     '".$_POST["contacto"]."',
                     '".$_POST["correo"]."'
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
            $sQuery = "UPDATE bancos SET estatus =".$aRecord["estatus"]." WHERE id_banco =".$aRecord["id_banco"];
           
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
