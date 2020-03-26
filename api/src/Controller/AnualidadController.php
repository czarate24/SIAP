<?php

namespace App\Controller;

use Exception;

class AnualidadController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */

     public function read() {
   

        $oConexion = $this->getConexion();
        $IdCiclo = $this->request->query('id_ciclo_escolar');
        $IdInstitucion = $this->request->query('id_institucion');

        //obtiene los bancos
        $sQuery = "SELECT anualidad.id_anualidad,
                        anualidad.estatus_anualidad, 
                        referencias.referencia,
                        CONCAT('COLEGIATURA CICLO ESCOLAR SEPTIEMBRE ', SUBSTRING(ciclo.fechai, 1, 4), ' A JUNIO DE ', SUBSTRING(ciclo.fechaf, 1, 4)) AS concepto, 
                        CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre, alumno.grado, UPPER(nivel.nombre) AS nivel,
                        CONCAT('$',anualidad.importe+anualidad.cuota_transporte-anualidad.descuento-anualidad.cuota_beca) AS importe, 
                        fp.forma_pago
                        FROM cxcandes.anualidades AS anualidad
                        LEFT JOIN sistema_andes.alumnos AS alumno
                        ON alumno.id_alumno=anualidad.id_alumno
                        LEFT JOIN formas_pago AS fp
                        ON anualidad.id_forma_pago=fp.id_forma_pago
                        LEFT JOIN sistema_andes.niveles_educativos AS nivel
                        ON alumno.id_nivel_educativo=nivel.id_nivel_educativo
                        LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                        ON anualidad.id_ciclo_escolar=ciclo.id_ciclo_escolar
                        LEFT JOIN referencias 
                        ON alumno.id_alumno=referencias.id_alumno AND id_tipo_movimiento=2
                        WHERE (nivel.id_institucion=".$IdInstitucion." AND anualidad.id_ciclo_escolar=".$IdCiclo.")";

                   
         $aBancos = $oConexion->query($sQuery);
        if (count($aBancos)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos Anualidad",
                "records" => $aBancos,
                "metadata" => array(
                    "total_registros" => count($aBancos)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Anualidad",
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

        $oConexion = $this->getConexion();
         $sQuery = "UPDATE carnets_colegiatura SET id_estatus_carnet=2  WHERE id_alumno=".$_POST['id_alumno']." AND 
                    id_estatus_carnet=1 AND id_ciclo_escolar=".$_POST['id_ciclo_escolar'];
       
      
        $oConexion->query($sQuery); 

        $fecha_actual=date('Y-m-d');
   
        $sQuery = "INSERT INTO anualidades(" .
                      "fecha_cobro, ".
                      "id_concepto_facturacion, ".
                      "id_alumno, " .
                      "id_forma_pago, " .
                      "id_dato_facturacion, " .
                      "fecha_captura, " .
                      "importe, " .
                      "descuento, " .
                      "id_facturacion_transporte, " .
                      "id_ciclo_escolar, " .
                      "cuota_beca, ".
                      "cuota_transporte ".
                      ") SELECT fecha_sistema,
                      id_concepto_facturacion,
                     '".$_POST['id_alumno']."',
                     '".$_POST["id_forma_pago"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$fecha_actual."',
                     '".$_POST["cuota"]."',
                     '".$_POST["descuento"]."',
                     '".$_POST["id_facturacion_transporte"]."',
                     '".$_POST['id_ciclo_escolar']."',
                     '".$_POST["beca"]."',
                     '".$_POST["transporte"]."'
                     FROM configuracion 
                     LEFT JOIN conceptos_facturacion
                     ON configuracion.id_institucion=conceptos_facturacion.id_institucion
                     LEFT JOIN sistema_andes.alumnos AS alumnos
                     ON alumnos.id_nivel_educativo=conceptos_facturacion.id_nivel_educativo
                     WHERE configuracion.estatus=1 AND configuracion.id_institucion=". $_POST['id_institucion_cobro']." AND id_tipo_movimiento=2 AND id_tipo_concepto=4 AND alumnos.id_alumno=".$_POST['id_alumno'];

                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Anualidad",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
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
            $sQuery = "UPDATE cobros SET id_estatus_cobro =".$aRecord["id_estatus_cobro"]." WHERE id_cobro =".$aRecord["id_cobro"];
           
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
  
}
