<?php

namespace App\Controller;

use Exception;

class RolesController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
    try{

         $oConexion = $this->getConexion();
        $intIdIns = $this->request->query('id_ins');

        //obtiene los ciclos
        $sQuery = "SELECT id_rol,upper(rol) as rol,id_institucion,status FROM sistema_andes.roles
                    WHERE status=1 and id_institucion=".$intIdIns." ORDER BY rol";
       // print_r($sQuery);
        
        $aDatosRoles = $oConexion->query($sQuery);
        if (count($aDatosRoles)>0){
            return $this->asJson(array(
                "success" => true,
                "message" => "Catalogo de Roles",
                "records" => $aDatosRoles,
                "metadata" => array(
                    "total_registros" => count($aDatosRoles)
                )
            ));
        }else{
             return $this->asJson(array(
                "success" => true,
                "message" => "No hay Roles",
                "records" => $aDatosRoles,
                "metadata" => array(
                    "total_registros" => count($aDatosRoles)
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
    public function agregarDatos() 
    {

                //Configure::write('debug', 0);
            $oConexion = $this->getConexion();
                //Valida si trae imagen
            //print_r($_POST['imagen']);
            if ($_POST['imagen']==0){
                $target_file =''; 
                 if (empty($_POST['id_institucion_dato']))
                        {
                                
                                        // agrega el registro de la institución.
                            $sQuery = "INSERT INTO instituciones_datos(" .
                            "id_institucion, " .
                            "razon_social, " .
                            "rfc, " .
                            "calle, " .
                            "numero_exterior, " .
                            "numero_interior, " .
                            "colonia, " .
                            "codigo_postal, " .
                            "fecha_alta, " .
                            "usuario_alta " .
                            ") VALUES (
                            ".$_POST["id_institucion"].",
                            '".$_POST["razon_social"]."',
                            '".$_POST["rfc"]."',
                            '".$_POST["calle"]."',
                            '".$_POST["numero_exterior"]."',
                            '".$_POST["numero_interior"]."',
                            '".$_POST["colonia"]."',
                            ".$_POST["codigo_postal"].",
                            ".'2019-07-17'.",
                            ".'1'.
                            ")";
                                    // print_r($sQuery);
                            $aResultado = $oConexion->query($sQuery);
                            return $this->asJson(array(
                                "success" => true,
                                "message" => "Agregar Institución",
                                "records" => $aResultado,
                                "metadata" => array(
                                    "total_registros" => count($aResultado)
                                )
                            ));

                        }
                        else
                        {  
                                    //Actualiza Institución
                               $sQuery = "UPDATE instituciones_datos SET " .
                               "razon_social = ?, " .
                               "rfc = ?, " .
                               "calle = ?, " .
                               "numero_exterior = ?, " .
                               "numero_interior = ?, " .
                               "colonia = ?, " .
                               "codigo_postal = ?, " .
                               "fecha_modificacion = ?, " .
                               "usuario_modificacion = ? " .
                               "WHERE id_institucion_dato = ?";
                               $aQueryParams = array(
                                $_POST["razon_social"],
                                $_POST["rfc"],
                                $_POST["calle"],
                                $_POST["numero_exterior"],
                                $_POST["numero_interior"],
                                $_POST["colonia"],
                                $_POST["codigo_postal"],
                                '2019-07-17',
                                1,
                                $_POST["id_institucion_dato"]
                            );

                               $oConexion->query($sQuery, $aQueryParams);
                               return $this->asJson(array(
                                    "success" => true,
                                    "message" => "Actualiza de Instituciones"
                                    
                                ));

                        }  
            }
            else
            {
                
                $strImagen = $this->request->data['ruta_logo']['tmp_name'];
                $strOrig = $this->request->data['ruta_logo']['name'];
                $target_dir = "logos_instituciones/";
                $target_file = $target_dir . basename($strImagen);
                $imageFileType = pathinfo($strOrig,PATHINFO_EXTENSION);
                         //C:/xampp/htdocs/Ctrlacceso/
                $target_file = $target_dir . basename($_POST['rfc']) . "." . $imageFileType;
                $this->message =  $strImagen." - ".$target_file;
                $this->message = $_POST['id_institucion'];
                $this->message .= $target_file;

                $uploadOk = 1;

                        // Check if image file is a actual image or fake image
                if(isset($_POST["submit"])) 
                {
                    $check = getimagesize($target_file);
                    if($check !== false) {
                            // $this->message .= "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                            // $this->message .= "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                            // Check if file already exists
                            // if (file_exists($target_file)) {
                            // // $this->message .= "Sorry, file already exists.";
                            //  $uploadOk = 0;
                            // }
                            // Check file size
                            // if ($files["size"] > 500000) {
                            // // $this->message .= "Sorry, your file is too large.";
                            //  $uploadOk = 0;
                            // }
                            // Allow certain file formats
              //  print_r($imageFileType);
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG"
                    && $imageFileType != "GIF" ) {
                        // $this->message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;

                
                }
                        // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                         //$this->message .= "Sorry, your file was not uploaded.";
                        // if everything is ok, try to upload file
               



                 } 
                else 
                {
                   // print_r($target_file);
                    if (move_uploaded_file($strImagen, $target_file)) 
                    {

                        if (empty($_POST['id_institucion_dato']))
                        {
                                
                                        // agrega el registro de la institución.
                            $sQuery = "INSERT INTO instituciones_datos(" .
                            "id_institucion, " .
                            "razon_social, " .
                            "rfc, " .
                            "calle, " .
                            "numero_exterior, " .
                            "numero_interior, " .
                            "colonia, " .
                            "codigo_postal, " .
                            "ruta_logo, " .
                            "fecha_alta, " .
                            "usuario_alta " .
                            ") VALUES (
                            ".$_POST["id_institucion"].",
                            '".$_POST["razon_social"]."',
                            '".$_POST["rfc"]."',
                            '".$_POST["calle"]."',
                            '".$_POST["numero_exterior"]."',
                            '".$_POST["numero_interior"]."',
                            '".$_POST["colonia"]."',
                            ".$_POST["codigo_postal"].",
                            '".$target_file."',
                            ".'2019-07-17'.",
                            ".'1'.
                            ")";
                                    // print_r($sQuery);
                            $aResultado = $oConexion->query($sQuery);
                            return $this->asJson(array(
                                "success" => true,
                                "message" => "Agregar Institución",
                                "records" => $aResultado,
                                "metadata" => array(
                                    "total_registros" => count($aResultado)
                                )
                            ));

                        }
                        else
                        {  
                                    //Actualiza Institución
                               $sQuery = "UPDATE instituciones_datos SET " .
                               "razon_social = ?, " .
                               "rfc = ?, " .
                               "calle = ?, " .
                               "numero_exterior = ?, " .
                               "numero_interior = ?, " .
                               "colonia = ?, " .
                               "codigo_postal = ?, " .
                               "ruta_logo = ?, " .
                               "fecha_modificacion = ?, " .
                               "usuario_modificacion = ? " .
                               "WHERE id_institucion_dato = ?";
                               $aQueryParams = array(
                                $_POST["razon_social"],
                                $_POST["rfc"],
                                $_POST["calle"],
                                $_POST["numero_exterior"],
                                $_POST["numero_interior"],
                                $_POST["colonia"],
                                $_POST["codigo_postal"],
                                $target_file,
                                '2019-07-18',
                                1,
                                $_POST["id_institucion_dato"]
                            );

                               $oConexion->query($sQuery, $aQueryParams);
                               return $this->asJson(array(
                                    "success" => true,
                                    "message" => "Actualiza de Instituciones"
                                    
                                ));

                        } //Fin del if-else para verificar si es Alta o modificación
                                  // $this->message = $target_file;
                    } 
                    else 
                    {
                        return $this->asJson(array(
                        "success" => false,
                        "message" => "Sorry, there was an error uploading your file."
                        ));
                    } //fin del move_uploaded_file
                } // fin del if uploadOk
                   // }//fin del foreach
            } //fin del else
                //echo $this->returnjson();
               // exit();
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
            $sQuery = "UPDATE instituciones_datos SET estatus =".$aRecord["estatus"]." WHERE id_institucion_dato =".$aRecord["id_institucion_dato"];
           
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
