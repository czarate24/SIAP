<?php

namespace App\Controller;

use Exception;

class CobrosColegiaturaController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
   

        $oConexion = $this->getConexion();
        $IdAlumno = $this->request->query('id_alumno');
        $IdCiclo = $this->request->query('id_ciclo_escolar');
        $IdInstitucion = $this->request->query('id_institucion');
        $fecha=date('Y-m-d');


        $sQuery = "SELECT referencia, 
                    CASE 
                    WHEN ci.id_mes>=9
                    THEN 
                    CONCAT('COLEGIATURA ', mes.mes, ' ', SUBSTRING(ciclo.fechai, 1, 4)) 
                    ELSE
                    CONCAT('COLEGIATURA ', mes.mes, ' ', SUBSTRING(ciclo.fechaf, 1, 4))
                    END
                    AS concepto, 
                    ci.id_carnet_colegiatura, 
                    ci.id_cuota_colegiatura, 
                    cuota.cuota,
                    ci.fecha_vencimiento,
                    ct.cuota AS transporte, ROUND(ROUND(((becas.porc_actual/100))*cuota.cuota,0),2) AS beca,
                    CASE WHEN  ci.fecha_vencimiento < '".$fecha."' THEN ROUND(((cuota.cuota-ROUND(cuota.cuota*(becas.porc_actual/100),0)+IFNULL(ct.cuota,0))*((SELECT porcentaje_recargo FROM configuracion WHERE estatus=1 AND id_institucion=".$IdInstitucion.")/100)),2)
                    ELSE 0
                    END AS recargos
                    FROM cxcandes.carnets_colegiatura AS ci
                    LEFT JOIN cxcandes.transportes_alumno AS tra
                    ON ci.id_cuota_transporte=tra.id_transporte_alumno
                    LEFT JOIN cxcandes.cuotas_transportes AS ct
                    ON tra.id_cuota_transporte=ct.id_cuota_transporte
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ci.id_ciclo_escolar=ciclo.id_ciclo_escolar
                    LEFT JOIN cuotas_colegiaturas AS cuota
                    ON ci.id_cuota_colegiatura=cuota.id_cuota_colegiatura
                    LEFT JOIN becas.becas AS becas 
                    ON becas.id_beca=ci.id_beca
                    LEFT JOIN meses_ciclo AS mes
                    ON mes.id_mes=ci.id_mes
                    WHERE  ci.id_estatus_carnet=1 and ci.id_alumno=".$IdAlumno." AND ci.id_institucion=".$IdInstitucion." AND ci.id_ciclo_escolar=".$IdCiclo." ORDER BY id_carnet_colegiatura ASC LIMIT 1";
                   
        
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

    //AGREGAR COBRO DE COLEGIATURA
    public function create() 
    {

        $oConexion = $this->getConexion();
        $sQuery = "UPDATE carnets_colegiatura SET id_estatus_carnet =".$_POST["id_carnet"];
        $oConexion->query($sQuery);
     
        print_r($sQuery);

            $oConexion = $this->getConexion();
            $sQuery = "INSERT INTO cobros(" .
                      "id_alumno, " .
                      "id_carnet_colegiatura," .
                      "id_forma_pago, " .
                      "id_dato_facturacion, " .
                      "fecha_cobro, " .
                      "fecha_captura, " .
                      "importe, " .
                      "recargo, " .
                      "descuento, " .
                      "id_facturacion_transporte " .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_carnet"]."',
                     '".$_POST["id_forma_pago"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$_POST["fecha_cobro"]."',
                     '".$_POST["fecha_captura"]."',
                     '".$_POST["importe"]."',
                     '".$_POST["recargo"]."',
                     '".$_POST["descuento"]."',
                     '".$_POST["id_facturacion_transporte"]."'
                     )";
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
          
             
    } 

    // AGREGA COBRO DE INSCRIPCIÓN
     public function createInscripcion() 
    {
            $oConexion = $this->getConexion();
   
            $sQuery = "INSERT INTO cobros(" .
                      "id_alumno, " .
                      "id_carnet_inscripcion," .
                      "id_forma_pago, " .
                      "id_dato_facturacion, " .
                      "fecha_cobro, " .
                      "fecha_captura " .
                      "importe " .
                      "recargo " .
                      "descuento " .
                      "id_facturacion_transporte " .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_carnet_inscripcion"]."',
                     '".$_POST["id_forma_pago"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$_POST["fecha_cobro"]."',
                     '".$_POST["fecha_captura"]."'
                     '".$_POST["importe"]."'
                     '".$_POST["recargo"]."'
                     '".$_POST["descuento"]."'
                     '".$_POST["id_facturacion_transporte"]."'
                     )";
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Cobro Inscripción",
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
   
}
