<?php

namespace App\Controller;

use Exception;

class ParcialidadesController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
   

        $oConexion = $this->getConexion();
        $matricula = $this->request->query('matricula');

        //obtiene los bancos
        $sQuery = "SELECT alum.id_alumno, matricula,  CONCAT(alum.nombre,' ', alum.ap_paterno, ' ', alum.ap_materno) AS nombre, UPPER(niveles.nombre) AS nivel, alum.grado, alum.grupo,
                rfc, razon_social, usoCFDI, id_dato_facturacion, referencias.referencia, f_andes,
                CONCAT('INSCRIPCIÓN CICLO ESCOLAR ', ciclo.ciclo ) as concepto,
                ci.id_cuota_inscripcion, ci.id_carnet_inscripcion, cuota.cuota, 
                IFNULL(id_descuento_inscripcion,0) AS id_descuento_inscripcion, 
                ROUND(ROUND(IFNULL(cuota.cuota*(descuento/100),0),0),0) AS descuento,
                cuota.cuota-(IFNULL(cuota.cuota*(descuento/100),0)) AS total
                FROM sistema_andes.alumnos  alum
                LEFT JOIN sistema_andes.niveles_educativos AS niveles
                ON alum.id_nivel_educativo=niveles.id_nivel_educativo
                LEFT JOIN sistema_andes.familias AS familias
                ON familias.id_familia=alum.id_familia
                LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                ON ciclo.id_ciclo_escolar=2
                LEFT JOIN datos_facturacion AS df
                ON df.id_familia=alum.id_familia AND  df.predeterminado=1
                LEFT JOIN referencias
                ON referencias.id_alumno=alum.id_alumno AND referencias.id_tipo_movimiento=1
                LEFT JOIN cxcandes.carnets_inscripcion AS ci
                ON ci.id_alumno=alum.id_alumno AND ci.id_ciclo_escolar=2
                LEFT JOIN cuotas_inscripciones AS cuota
                ON ci.id_cuota_inscripcion=cuota.id_cuota_inscripcion
                LEFT JOIN descuentos_inscripciones
                ON ci.id_cuota_inscripcion=descuentos_inscripciones.id_cuota_inscripcion AND fecha_inicio<'2019-02-22' AND fecha_fin>'2019-02-22' WHERE matricula=".$matricula."  AND ci.id_estatus_carnet=1";

        
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
            $oConexion = $this->getConexion();
            $sQuery = "UPDATE carnets_inscripcion SET id_estatus_carnet=2  WHERE id_alumno=".$_POST["id_alumno"]." AND 
                    id_estatus_carnet=1 AND id_ciclo_escolar=".$_POST["id_ciclo_escolar"];
            $oConexion->query($sQuery);

            $fecha_alta=Date('Y-m-d');
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO contratos_pacialidades(" .
                      "id_ciclo_escolar, " .
                      "id_institucion," .
                      "id_alumno, " .
                      "id_carnet_inscripcion, " .
                      "id_descuento_inscripcion, " .
                      "descuento, " .
                      "importe, " .
                      "id_dato_facturacion, " .
                      "fecha_alta, " .
                      "fecha_inicio, " .
                      "fecha_fin, " .
                      "parcialidad1, " .
                      "parcialidad2, " .
                      "numero_parcialidades, " .
                      "nomina " .
                      ") VALUES (
                     '".$_POST["id_ciclo_escolar"]."',
                     '".$_POST["id_institucion"]."',
                     '".$_POST["id_alumno"]."',
                     '".$_POST["id_carnet_inscripcion"]."',
                     '".$_POST["id_descuento_inscripcion"]."',
                     '".$_POST["descuento"]."',
                     '".$_POST["importe"]."',
                     '".$_POST["id_dato_facturacion"]."',
                     '".$fecha_alta."',
                     '".$fecha_alta."',
                     '".$fecha_alta."',
                     '".$_POST["parcialidad1"]."',
                     '".$_POST["parcialidad2"]."',
                     '".$_POST["numero_parcialidades"]."',
                     '".$_POST["nomina"]."'
                     )";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Parcialidades",
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

}
