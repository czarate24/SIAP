<?php

namespace App\Controller;

use Exception;

class ImportesAnualidadController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
   

        $oConexion = $this->getConexion();
        $IdAlumno = $this->request->query('id_alumno');
        $IdCiclo = $this->request->query('id_ciclo_escolar');

        //OBTIENE LOS IMPORTES PARA LA ANUALIDADES
        $sQuery =  "SELECT SUM(cuota.cuota) AS cuota, 
                        IFNULL(SUM(ROUND(cuota.cuota*(becas.porc_actual/100),2)),0) AS beca,
                        ROUND(SUM(IFNULL(cuota_trans.cuota,0)),2) AS transporte,
                        ROUND(SUM(cuota.cuota-(cuota.cuota*(IFNULL(becas.porc_actual,0)/100))+IFNULL(cuota_trans.cuota,0)),2) AS subtotal,
                        ROUND(SUM((porcentaje_anualidad/100)*(cuota.cuota-(cuota.cuota*(IFNULL(becas.porc_actual,0)/100))+IFNULL(cuota_trans.cuota,0))),2) AS descuento,
                        ROUND(SUM((cuota.cuota-(cuota.cuota*(IFNULL(becas.porc_actual,0)/100))+IFNULL(cuota_trans.cuota,0))-(porcentaje_anualidad/100)*(cuota.cuota-(cuota.cuota*(IFNULL(becas.porc_actual,0)/100))+IFNULL(cuota_trans.cuota,0))),2)AS importe
                        FROM carnets_colegiatura AS carnet
                        LEFT JOIN cuotas_colegiaturas AS cuota
                        ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                        LEFT JOIN becas.becas AS becas
                        ON becas.id_beca=carnet.id_beca
                        LEFT JOIN transportes_alumno AS transporte
                        ON transporte.id_transporte_alumno=carnet.id_cuota_transporte
                        LEFT JOIN cuotas_transportes AS cuota_trans
                        ON transporte.id_cuota_transporte=cuota_trans.id_cuota_transporte
                        LEFT JOIN configuracion AS conf
                        ON conf.id_institucion=carnet.id_institucion AND conf.estatus=1
                        WHERE carnet.id_alumno=".$IdAlumno." AND carnet.id_estatus_carnet=1 AND carnet.id_ciclo_escolar=".$IdCiclo;

                   
        
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
                "success" => true,
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
   


      public function actualiza() 
    {

                
    } //Fin de la función agredarDatos



    /**
     * Actualiza plazas
     *
     * @return Cake\Network\Response
     */
    public function update() {
      
    }

    /**
     * Elimina plazas
     *
     * @return Cake\Network\Response
     */
    
}
