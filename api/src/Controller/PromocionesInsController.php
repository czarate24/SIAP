<?php

namespace App\Controller;

use Exception;

class PromocionesInsController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

         $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');
        $intIdCuota = $this->request->query('id_cuota_inscripcion');
        $intIdCiclo = $this->request->query('id_ciclo_escolar');

        //obtiene los ciclos

            $sQuery="SELECT ci.id_cuota_inscripcion,ci.cuota,di.id_descuento_inscripcion,di.id_estatus_ingreso,ei.descripcion,di.descuento,di.fecha_inicio,di.fecha_fin,di.estatus as estatus,
                    ROUND(ci.cuota*(100-di.descuento)/100) AS total 
                FROM cuotas_inscripciones AS ci
                INNER JOIN descuentos_inscripciones AS di
                    ON ci.id_cuota_inscripcion = di.id_cuota_inscripcion
                INNER JOIN estatus_ingresos AS ei
                    ON di.id_estatus_ingreso = ei.id_estatus_ingreso    
                WHERE ci.id_institucion=".$intIdIns." AND ci.id_ciclo_escolar=".$intIdCiclo." AND ci.estatus=1 AND ci.id_cuota_inscripcion=".$intIdCuota;

        //$sQuery = "SELECT id_nivel_educativo,UPPER(nombre) AS nombre,clave,id_institucion FROM sistema_andes.niveles_educativos WHERE STATUS=1 and id_institucion=".$intIdIns;
        //print_r($sQuery);
        
        $aDatosCuotasIns = $oConexion->query($sQuery);
        if (count($aDatosCuotasIns)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Promociones",
                "records" => $aDatosCuotasIns,
                "metadata" => array(
                    "total_registros" => count($aDatosCuotasIns)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Promociones",
                "records" => $aDatosCuotasIns,
                "metadata" => array(
                    "total_registros" => count($aDatosCuotasIns)
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

    
     //$Finicio = date("Y-m-d", strtotime($_POST["fecha_inicio"]));
        $Ffin = $_POST["fecha_fin"];
    
     
        $Finicio = $_POST["fecha_inicio"];
   
        //$Finicio->format('Y-m-d H:i:s');
//print_r($Ffin);
   
         $oConexion = $this->getConexion();
           // agrega el registro de la institución.
            $sQuery = "INSERT INTO descuentos_inscripciones(" .
                      "id_cuota_inscripcion, " .
                      "id_estatus_ingreso, " .
                      "descuento,".
                      "fecha_inicio,".
                      "fecha_fin,".
                      "fecha_alta, " .
                      "usuario_alta " .
                      ") VALUES (
                      ".$_POST["id_cuota_inscripcion"].",
                      ".$_POST["id_estatus_ingreso"].",
                     '".$_POST["descuento"]."',
                      '".$Finicio."',
                      '".$Ffin."',
                      ".'2019-07-17'.",
                      ".'1'.
                      ")";
                  //   print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Agregar Promocion",
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
            $sQuery = "UPDATE descuentos_inscripciones SET id_estatus_ingreso =".$_POST['id_estatus_ingreso'].",
                       descuento = ".$_POST['descuento'].",
                       fecha_inicio = '".$_POST['fecha_inicio']."', 
                       fecha_fin = '".$_POST['fecha_fin']."' 
                         WHERE id_descuento_inscripcion=".$_POST['id_descuento_inscripcion'];
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     return $this->asJson(array(
                        "success" => true,
                        "message" => "Actualizar Promocion",
                       
                       
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
            $sQuery = "UPDATE descuentos_inscripciones SET estatus =".$aRecord["estatus"]." WHERE id_descuento_inscripcion =".$aRecord["id_descuento_inscripcion"];
           
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
