<?php

namespace App\Controller;

use Exception;

class TransporteAlumnoController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        //$Ccontrol = $this->request->query('ncontrol');
        $IdAlum = $this->request->query('id_alumno');
       // $intIdNivel = $this->request->query('id_nivel_educativo');
       
        //obtiene las familias

        $sQuery = "SELECT  ta.id_transporte_alumno, 
                        ta.id_mes, 
                        ta.id_cuota_transporte, 
                        ta.id_alumno, 
                        ta.estatus, 
                        mc.mes, 
                        ct.cuota,
                        ct.descripcion    
                        FROM cuotas_transportes AS ct
                        INNER JOIN transportes_alumno AS ta
                        INNER JOIN meses_ciclo AS mc
                        ON ta.id_cuota_transporte=ct.id_cuota_transporte AND ta.id_mes=mc.id_mes
                        WHERE mc.status=1 AND ct.id_institucion=1 AND id_alumno=".$IdAlum;

        
        $aDatosAlum = $oConexion->query($sQuery);
        if (count($aDatosAlum)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Alumnos",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Alumnos",
                "records" => $aDatosAlum,
                "metadata" => array(
                    "total_registros" => count($aDatosAlum)
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
        $sQuerySelect = "SELECT id_mes FROM meses_ciclo
                WHERE status=1 AND 
                id_institucion=1";

        $aDatosMes = $oConexion->query($sQuerySelect);
            
        foreach ($aDatosMes as $variable) {
         $sQuery="";
         $aResultado="";
         $sQuery = "INSERT INTO transportes_alumno(" .
                      "id_mes," .
                      "id_cuota_transporte, " .
                      "id_alumno " .                     
                      ") VALUES (
                     '".$variable["id_mes"]."',
                     '".$_POST["id_cuota_transporte"]."',
                     '".$_POST["id_alumno"]."'
                     )";


                     $aResultado = $oConexion->query($sQuery);

            }

             return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Transporte alumno",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       )); 
     
  

    } //Fin de la función agredarDatos


      public function actualiza() 
    {
         $oConexion = $this->getConexion();
        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);
        
        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE transportes_alumno SET estatus =".$aRecord["estatus"]." WHERE id_transporte_alumno =".$aRecord["id_transporte_alumno"];
          
            $oConexion->query($sQuery);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Registro Actualizado"
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
            $sQuery = "UPDATE transportes_alumno SET " .
                "id_cuota_transporte = ? " .
                "WHERE id_transporte_alumno = ?";

            $aQueryParams = array(
                $aRecord["id_cuota_transporte"],
                $aRecord["id_transporte_alumno"]
            );
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
