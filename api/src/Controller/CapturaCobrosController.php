<?php

namespace App\Controller;

use Exception;

class CapturaCobrosController extends AppController {

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
        $sQuery = "SELECT cobro.id_cobro,
                        cobro.id_estatus_cobro, 
                        cobro.id_facturacion_transporte,
                        CASE WHEN cobro.id_carnet_inscripcion>0
                        THEN carneti.referencia
                        ELSE carnetc.referencia
                        END AS referencia,
                        CASE WHEN cobro.id_carnet_inscripcion>0
                        THEN 1
                        ELSE 2
                        END AS id_tipo_movimiento,
                          CASE WHEN cobro.id_carnet_inscripcion>0
                        THEN 0
                        ELSE carnetc.id_cuota_transporte
                        END AS id_cuota_transporte,
                        CASE WHEN cobro.id_carnet_inscripcion>0
                        THEN CONCAT('INSCRIPCIÓN ', ciclo.ciclo)
                        ELSE
                            CASE 
                                WHEN carnetc.id_mes>=9
                                THEN 
                                CONCAT('COLEGIATURA ', mes.mes, ' ', SUBSTRING(ciclo.fechai, 1, 4)) 
                                ELSE
                                CONCAT('COLEGIATURA ', mes.mes, ' ', SUBSTRING(ciclo.fechaf, 1, 4))
                            END
                        END AS concepto,
                        CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre, alumno.grado, UPPER(nivel.nombre) AS nivel,
                        CONCAT('$',cobro.importe) AS importe, 
                        fp.forma_pago
                        FROM cxcandes.cobros AS cobro
                        LEFT JOIN sistema_andes.alumnos AS alumno
                        ON alumno.id_alumno=cobro.id_alumno
                        LEFT JOIN formas_pago AS fp
                        ON cobro.id_forma_pago=fp.id_forma_pago
                        LEFT JOIN sistema_andes.niveles_educativos AS nivel
                        ON alumno.id_nivel_educativo=nivel.id_nivel_educativo
                        LEFT JOIN carnets_colegiatura AS carnetc
                        ON cobro.id_carnet_colegiatura=carnetc.id_carnet_colegiatura
                        LEFT JOIN carnets_inscripcion AS carneti
                        ON cobro.id_carnet_inscripcion=carneti.id_carnet_inscripcion
                        LEFT JOIN meses_ciclo AS mes
                        ON mes.id_mes=carnetc.id_mes
                        LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                        ON carnetc.id_ciclo_escolar=ciclo.id_ciclo_escolar OR carneti.id_ciclo_escolar=ciclo.id_ciclo_escolar 
                        WHERE id_estatus_cobro=1 and (carnetc.id_institucion=".$IdInstitucion." AND carnetc.id_ciclo_escolar=".$IdCiclo." AND id_estatus_cobro=1) or (carneti.id_institucion=".$IdInstitucion." AND carneti.id_ciclo_escolar=".$IdCiclo."
                            AND id_estatus_cobro=1)";

                   
         $aBancos = $oConexion->query($sQuery);
        if (count($aBancos)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Datos cobro",
                "records" => $aBancos,
                "metadata" => array(
                    "total_registros" => count($aBancos)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Datos Cobro",
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
        $sQuery = "UPDATE carnets_colegiatura SET id_estatus_carnet =2 where id_carnet_colegiatura=".$_POST["id_carnet"];
        $oConexion->query($sQuery);

        $fecha_actual=date('Y-m-d');
   
        $sQuery = "INSERT INTO cobros(" .
                      "fecha_cobro, ".
                      "id_concepto_facturacion, ".
                      "id_alumno, " .
                      "id_carnet_colegiatura," .
                      "id_forma_pago, " .
                      "id_dato_facturacion, " .
                      "fecha_captura, " .
                      "importe, " .
                      "recargo, " .
                      "descuento, " .
                      "id_facturacion_transporte " .
                      ") SELECT fecha_sistema,
                      id_concepto_facturacion,
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_carnet"]."',
                     '".$_POST["id_forma_pago"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$fecha_actual."',
                     '".$_POST["importe"]."',
                     '".$_POST["recargo"]."',
                     '".$_POST["descuento"]."',
                     '".$_POST["id_facturacion_transporte"]."'
                     FROM configuracion 
                     LEFT JOIN conceptos_facturacion
                     ON configuracion.id_institucion=conceptos_facturacion.id_institucion
                     LEFT JOIN sistema_andes.alumnos AS alumnos
                     ON alumnos.id_nivel_educativo=conceptos_facturacion.id_nivel_educativo
                     WHERE configuracion.estatus=1 AND configuracion.id_institucion=".$_POST["id_institucion_cobro"]." AND id_tipo_movimiento=2 AND id_tipo_concepto=1 AND alumnos.id_alumno=".$_POST["id_alumno"];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Cobro Colegiatura",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));
             
    } //Fin de la función agredarDatos


 public function createIns() 
    {
                  //Configure::write('debug', 0);
        $oConexion = $this->getConexion();
        $sQuery = "UPDATE carnets_inscripcion SET id_estatus_carnet =2 where id_carnet_inscripcion=".$_POST["id_carnet"];
        $oConexion->query($sQuery);

        $fecha_actual=date('Y-m-d');
   
        $sQuery = "INSERT INTO cobros(" .
                      "fecha_cobro, ".
                      "id_concepto_facturacion,".
                      "id_alumno, " .
                      "id_carnet_inscripcion," .
                      "id_forma_pago, " .
                      "id_dato_facturacion, " .
                      "fecha_captura, " .
                      "importe, " .
                      "recargo, " .
                      "descuento, " .
                      "id_descuento_inscripcion, " .
                      "id_facturacion_transporte " .
                      ") SELECT fecha_sistema,
                      id_concepto_facturacion,
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_carnet"]."',
                     '".$_POST["id_forma_pago"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$fecha_actual."',
                     '".$_POST["importe"]."',
                     '".$_POST["recargo"]."',
                     '".$_POST["descuento"]."',
                     '".$_POST["id_descuento_inscripcion"]."',
                     '".$_POST["id_facturacion_transporte"]."'
                     FROM configuracion 
                     LEFT JOIN conceptos_facturacion
                     ON configuracion.id_institucion=conceptos_facturacion.id_institucion
                     WHERE configuracion.estatus=1 AND configuracion.id_institucion=".$_POST["id_institucion_cobro"]." AND id_tipo_movimiento=1";
                
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Cobro Inscripcion",
                        "records" => $aResultado,
                        "metadata" => array(
                        "total_registros" => count($aResultado)
                        )
                       ));
    }

   


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
