<?php

namespace App\Controller;

use Exception;

class CobrosCarnetController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
   

        $oConexion = $this->getConexion();
        $IdAlumno = $this->request->query('id_alumno');
        $IdCiclo = $this->request->query('id_ciclo_escolar');
        $InInstitucion = $this->request->query('id_institucion');
        $fecha=Date('Y-m-d');

        //obtiene los bancos
        $sQuery =  "SELECT referencia, 
                    CONCAT('INSCRIPCIÓN ', ciclo) AS concepto, 
                    ci.id_cuota_inscripcion, 
                    ci.id_carnet_inscripcion, 
                    cuota.cuota, 
                    IFNULL(id_descuento_inscripcion,0) AS id_descuento_inscripcion, 
                    ROUND(ROUND(IFNULL(cuota.cuota*(descuento/100),0),0),0) AS descuento
                    FROM cxcandes.carnets_inscripcion AS ci
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ci.id_ciclo_escolar=ciclo.id_ciclo_escolar
                    LEFT JOIN cuotas_inscripciones AS cuota
                    ON ci.id_cuota_inscripcion=cuota.id_cuota_inscripcion
                    LEFT JOIN descuentos_inscripciones
                    ON ci.id_cuota_inscripcion=descuentos_inscripciones.id_cuota_inscripcion AND fecha_inicio<'".$fecha."' AND fecha_fin>'".$fecha."'
                    WHERE ci.id_estatus_carnet=1 and ci.id_alumno=".$IdAlumno." AND ci.id_institucion=".$InInstitucion." AND ci.id_ciclo_escolar=".$IdCiclo." ORDER BY id_carnet_inscripcion ASC LIMIT 1";

                   
        
        $aBancos = $oConexion->query($sQuery);
        if (count($aBancos)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Alumno",
                "records" => $aBancos,
                "metadata" => array(
                    "total_registros" => count($aBancos)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => false,
                "message" => "No hay Alumnos",
                "records" => $aBancos,
                "metadata" => array(
                    "total_registros" => count($aBancos)
                )
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
