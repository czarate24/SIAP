<?php

namespace App\Controller;

use Exception;

class ReferenciasController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {

    try{

        $oConexion = $this->getConexion();
        $idIns = $this->request->query('id_institucion');
        $idAlumno = $this->request->query('id_alumno');
        
        $sQuery = "SELECT id_referencia, referencia, id_alumno, ref.id_tipo_movimiento, tm.movimiento AS tipo_movimiento
                FROM referencias AS ref
                INNER JOIN tipos_movimientos AS tm
                ON tm.id_tipo_movimiento=ref.id_tipo_movimiento WHERE ref.estatus=1 AND id_alumno=".$idAlumno;
        
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

         // DIVIDE LOS NÚMEROS DE LA MATRÍCULA Y LE ASIGNA UNA VARIABLE A CADA UNO 
         $matricula=$_POST["matricula"];
         $num1 = substr($matricula, -7,1); // PRIMER NÚMERO
         $num2 = substr($matricula, -6,1); // SEGUNDO NÚMERO
         $num3 = substr($matricula, -5,1); // TERCER NÚMERO
         $num4 = substr($matricula, -4,1); // CUARTO NÚMERO 
         $num5 = substr($matricula, -3,1); // QUINTO NÚMERO
         $num6 = substr($matricula, -2,1); // SEXTO NÚMERO

         // MÚLTIPLICA EL NÚMERO DE LA PRIMER POSICIÓN POR EL NÚMERO QUE LE CORRESPONDE DE ACUERDO A SU POSICIÓN
         $num1=$num1*1;
         if($num1>=10){  // SI EL NÚMERO ES MAYOR A 10 SUMA EL NÚMERO DE LO PRIMERA POSICIÓN MÁS EL DE LA SEGUNDA
            $n1 = substr($num1, -2,1); 
            $n2 = substr($num1, -1,1);
            $num1=$n1+$n2;
         }
         $num2=$num2*2;
          if($num2>=10){
            $n1 = substr($num2, -2,1); 
            $n2 = substr($num2, -1,1);
            $num2=$n1+$n2;
         }
         $num3=$num3*1;
          if($num3>=10){
            $n1 = substr($num3, -2,1); 
            $n2 = substr($num3, -1,1);
            $num3=$n1+$n2;
         }
         $num4=$num4*2;
          if($num4>=10){
            $n1 = substr($num4, -2,1); 
            $n2 = substr($num4, -1,1);
            $num4=$n1+$n2;
         }
         $num5=$num5*1;
          if($num5>=10){
            $n1 = substr($num5, -2,1); 
            $n2 = substr($num5, -1,1);
            $num5=$n1+$n2;
         }
         $num6=$num6*2;
          if($num6>=10){
            $n1 = substr($num6, -2,1); 
            $n2 = substr($num6, -1,1);
            $num6=$n1+$n2;
         }

         $verCol=18+$num1+$num2+$num3+$num4+$num5+$num6; // SUMA 18 QUE ES EL RESULTADO DE CONVERTIR A NÚMERO COL, MÁS CADA UNO DE LOS NÚMEROS QUE CONFORMAN LA MATRÍCULA.

         $ver1=substr($verCol, -2,1);  // SACA EL PRIMER NÚMERO DEL RESULTADO DE LA SUMA
         $ver2=substr($verCol, -1,1);  // SEGUNDO NÚMERO DEL RESULTADO DE LA SUMA

         if($ver2==0){ // SI EL SEGUNDO NÚMERO DE LA SUMA ES 0 ASIGNA EL O AL NÚMERO VERIFICADOR DE LA COLEGIATURA.
            $verifColeg=0;
         }else{  // SI EL NÚMERO ES MAYOR A 0 LE SUMA 1 AL NÚMERO EN LA POSICIÓN DE LAS DECENAS Y EL SEGUNDO NÚMERO LO PASA A 0, LO QUE NOS DA LA DECENA SUPERIOR.
            $ver1=$ver1+1;
            $ver2=0;
            $total=$ver1.$ver2; 
            $verifColeg=$total-$verCol; // SE RESTA LA DECENA SUPERIOR AL RESULTADO DE LA SUMA PARA OBTENER EL NÚMERO VERIFICADOR.-
         }
         $refcolegiatura='COL'.substr($matricula, 0, -1).$verifColeg;


         // SE REPITE EL MISMO PROCESO PARA OBTENER LA REFERENCIA DE LA INSCRPCIÓN.
         $verIns=16+$num1+$num2+$num3+$num4+$num5+$num6;
         $ver1=substr($verIns, -2,1); 
         $ver2=substr($verIns, -1,1); 

         if($ver2==0){
            $verifIns=0;
         }else{
            $ver1=$ver1+1;
            $ver2=0;
            $total=$ver1.$ver2;
            $verifIns=$total-$verIns;
         }
         $refinscripcion='INS'.substr($matricula, 0, -1).$verifIns;

         // INSERT PARA LA INSCRIPCIÓN
         $sQuery = "INSERT INTO referencias(" .
                      "id_alumno, " .
                      "id_tipo_movimiento," .
                      "referencia" .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '1',
                     '".$refinscripcion."'
                     )";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                     
        // INSERT PARA LA COLEGIATURA
        $sQuery = "INSERT INTO referencias(" .
                      "id_alumno, " .
                      "id_tipo_movimiento," .
                      "referencia" .
                      ") VALUES (
                     '".$_POST["id_alumno"]."',
                     '2',
                     '".$refcolegiatura."'
                     )";
                     // print_r($sQuery);
                     $aResultado = $oConexion->query($sQuery);
                   
        
    } //Fin de la función AGREGAR REFERENCIAS


  
      public function createAll(){

            $oConexion = $this->getConexion();

            $sQuery = "SELECT alum.id_alumno, CONCAT(LPAD(ncontrol,4,0),LPAD(matri,2,0),' ') AS matricula
                FROM sistema_andes.alumnos AS alum
                LEFT JOIN referencias AS ref 
                ON ref.id_alumno=alum.id_alumno
                LEFT JOIN tipos_movimientos AS tm
                ON tm.id_tipo_movimiento=ref.id_tipo_movimiento 
                WHERE id_status_alumno=1 AND ref.id_alumno IS NULL";
                         
            $aRecords = $oConexion->query($sQuery);

            foreach ($aRecords as $aRecord) {
            
                 $matricula=$aRecord["matricula"];
                 $num1 = substr($matricula, -7,1); // PRIMER NÚMERO
                 $num2 = substr($matricula, -6,1); // SEGUNDO NÚMERO
                 $num3 = substr($matricula, -5,1); // TERCER NÚMERO
                 $num4 = substr($matricula, -4,1); // CUARTO NÚMERO 
                 $num5 = substr($matricula, -3,1); // QUINTO NÚMERO
                 $num6 = substr($matricula, -2,1); // SEXTO NÚMERO

                 // MÚLTIPLICA EL NÚMERO DE LA PRIMER POSICIÓN POR EL NÚMERO QUE LE CORRESPONDE DE ACUERDO A SU POSICIÓN
                 $num1=$num1*1;
                 if($num1>=10){  // SI EL NÚMERO ES MAYOR A 10 SUMA EL NÚMERO DE LO PRIMERA POSICIÓN MÁS EL DE LA SEGUNDA
                    $n1 = substr($num1, -2,1); 
                    $n2 = substr($num1, -1,1);
                    $num1=$n1+$n2;
                 }
                 $num2=$num2*2;
                  if($num2>=10){
                    $n1 = substr($num2, -2,1); 
                    $n2 = substr($num2, -1,1);
                    $num2=$n1+$n2;
                 }
                 $num3=$num3*1;
                  if($num3>=10){
                    $n1 = substr($num3, -2,1); 
                    $n2 = substr($num3, -1,1);
                    $num3=$n1+$n2;
                 }
                 $num4=$num4*2;
                  if($num4>=10){
                    $n1 = substr($num4, -2,1); 
                    $n2 = substr($num4, -1,1);
                    $num4=$n1+$n2;
                 }
                 $num5=$num5*1;
                  if($num5>=10){
                    $n1 = substr($num5, -2,1); 
                    $n2 = substr($num5, -1,1);
                    $num5=$n1+$n2;
                 }
                 $num6=$num6*2;
                  if($num6>=10){
                    $n1 = substr($num6, -2,1); 
                    $n2 = substr($num6, -1,1);
                    $num6=$n1+$n2;
                 }

                 $verCol=18+$num1+$num2+$num3+$num4+$num5+$num6; // SUMA 18 QUE ES EL RESULTADO DE CONVERTIR A NÚMERO COL, MÁS CADA UNO DE LOS NÚMEROS QUE CONFORMAN LA MATRÍCULA.

                 $ver1=substr($verCol, -2,1);  // SACA EL PRIMER NÚMERO DEL RESULTADO DE LA SUMA
                 $ver2=substr($verCol, -1,1);  // SEGUNDO NÚMERO DEL RESULTADO DE LA SUMA

                 if($ver2==0){ // SI EL SEGUNDO NÚMERO DE LA SUMA ES 0 ASIGNA EL O AL NÚMERO VERIFICADOR DE LA COLEGIATURA.
                    $verifColeg=0;
                 }else{  // SI EL NÚMERO ES MAYOR A 0 LE SUMA 1 AL NÚMERO EN LA POSICIÓN DE LAS DECENAS Y EL SEGUNDO NÚMERO LO PASA A 0, LO QUE NOS DA LA DECENA SUPERIOR.
                    $ver1=$ver1+1;
                    $ver2=0;
                    $total=$ver1.$ver2; 
                    $verifColeg=$total-$verCol; // SE RESTA LA DECENA SUPERIOR AL RESULTADO DE LA SUMA PARA OBTENER EL NÚMERO VERIFICADOR.-
                 }
                 $refcolegiatura='COL'.substr($matricula, 0, -1).$verifColeg;


                 // SE REPITE EL MISMO PROCESO PARA OBTENER LA REFERENCIA DE LA INSCRPCIÓN.
                 $verIns=16+$num1+$num2+$num3+$num4+$num5+$num6;
                 $ver1=substr($verIns, -2,1); 
                 $ver2=substr($verIns, -1,1); 

                 if($ver2==0){
                    $verifIns=0;
                 }else{
                    $ver1=$ver1+1;
                    $ver2=0;
                    $total=$ver1.$ver2;
                    $verifIns=$total-$verIns;
                 }
                 $refinscripcion='INS'.substr($matricula, 0, -1).$verifIns;

                 // INSERT PARA LA INSCRIPCIÓN
                 $sQuery = "INSERT INTO referencias(" .
                              "id_alumno, " .
                              "id_tipo_movimiento," .
                              "referencia" .
                              ") VALUES (
                             '".$aRecord["id_alumno"]."',
                             '1',
                             '".$refinscripcion."'
                             )";
                             // print_r($sQuery);
                             $aResultado = $oConexion->query($sQuery);
                             
                // INSERT PARA LA COLEGIATURA
                $sQuery = "INSERT INTO referencias(" .
                              "id_alumno, " .
                              "id_tipo_movimiento," .
                              "referencia" .
                              ") VALUES (
                             '".$aRecord["id_alumno"]."',
                             '2',
                             '".$refcolegiatura."'
                             )";
                             // print_r($sQuery);
                             $aResultado = $oConexion->query($sQuery);
                           
                          
                    }  


      }
 

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
    public function delete() {
     
    }
}
