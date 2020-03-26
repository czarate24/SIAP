<?php

namespace App\Controller;

use Exception;

class MostrarParcialidadController extends AppController {

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
        $sQuery = "SELECT parcialidades.id_contrato,
                        parcialidades.estatus, 
                        referencias.referencia,
                        parcialidades.numero_parcialidades,
                        CONCAT('INCRIPCIÓN CICLO ESCOLAR ', ciclo.ciclo) AS concepto, 
                        CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre, alumno.grado, UPPER(nivel.nombre) AS nivel,
                        CONCAT('$ ', parcialidades.importe) AS importe 
                        FROM cxcandes.contratos_pacialidades AS parcialidades
                        LEFT JOIN sistema_andes.alumnos AS alumno
                        ON alumno.id_alumno=parcialidades.id_alumno
                        LEFT JOIN sistema_andes.niveles_educativos AS nivel
                        ON alumno.id_nivel_educativo=nivel.id_nivel_educativo
                        LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                        ON parcialidades.id_ciclo_escolar=ciclo.id_ciclo_escolar
                        LEFT JOIN referencias 
                        ON alumno.id_alumno=referencias.id_alumno AND id_tipo_movimiento=1
                        WHERE (nivel.id_institucion=".$IdInstitucion." AND parcialidades.id_ciclo_escolar=".$IdCiclo.")";

        
        $aBancos = $oConexion->query($sQuery);
        if (count($aBancos)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Parcialidades",
                "records" => $aBancos,
                "metadata" => array(
                    "total_registros" => count($aBancos)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => false,
                "message" => "No hay Parcialidades",
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
          
             
    } //Fin de la función agredarDatos


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
