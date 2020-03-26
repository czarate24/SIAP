<?php

namespace App\Controller;

use Exception;

class CobrosAlumnoController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
   

        $oConexion = $this->getConexion();
        $matricula = $this->request->query('matricula');

        //obtiene los bancos
        $sQuery = "SELECT alum.id_alumno, matricula,  CONCAT(alum.nombre,' ', ap_paterno, ' ', ap_materno) AS nombre, UPPER(niveles.nombre) AS nivel, grado, grupo,
                rfc, razon_social, usoCFDI, id_dato_facturacion, referencia 
                FROM sistema_andes.alumnos  alum
                LEFT JOIN sistema_andes.niveles_educativos AS niveles
                ON alum.id_nivel_educativo=niveles.id_nivel_educativo
                LEFT JOIN datos_facturacion AS df
                ON df.id_familia=alum.id_familia AND  df.predeterminado=1
                LEFT JOIN referencias
                ON referencias.id_alumno=alum.id_alumno and referencias.id_tipo_movimiento=2
                WHERE matricula=".$matricula;

       // print_r($sQuery);
        
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
