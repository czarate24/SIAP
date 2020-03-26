<?php
namespace App\Controller;
use Cake\Utility\Xml;
use Exception;
class FacturacionAnualidadController extends AppController {

    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {


        $oConexion = $this->getConexion();
        $fecha= Date("Y-m-d\TH:i:s");
        $i=0;
        
         $sQuery=" (SELECT MAX(id_anualidad) as id_anualidad FROM anualidades)";
        $aUltimoRecord = $oConexion->query($sQuery);


        //OBTIENE LOS DATOS DEL EMISOR Y RECEPTOR DE LA FACTURA.
        foreach ($aUltimoRecord as $aRecord) 
        { 
        //OBTINE LOS DATOS PARA CUANDO LA FACTURA ES SOLO DE COLEGIATURA, FACTURANDO POR SEPARADO EL TRANSPORTE.
        $sQuery = "SELECT 
                IFNULL(df.rfc, 'XAXX010101000') AS rfc, 
                IFNULL(df.razon_social, CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre)) AS razon_social, 
                IFNULL(uso.uso_cfdi_id,21) AS uso_cfdi_id, 
                institucion.id_sucursal, 
                id_tipo_moneda, 
                id_impuesto, 
                id_documento, 
                id_metodo_pago, 
                condicion_pago, 
                anualidades.id_anualidad,
                anualidades.id_facturacion_transporte,
                CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre,
                anualidades.importe+anualidades.cuota_transporte AS subtotal,
                anualidades.descuento + anualidades.cuota_beca AS descuento,
                anualidades.importe+anualidades.cuota_transporte-anualidades.descuento-anualidades.cuota_beca AS importe,
                forma_pago.forma_pago_id,
                concepto.concepto_nivel AS nivel,
                alumno.curp,
                alumno.id_institucion, 
                anualidades.id_ciclo_escolar,
                anualidades.fecha_cobro,
                autRVOE
                FROM anualidades
                LEFT JOIN datos_facturacion AS df
                ON df.id_dato_facturacion=anualidades.id_dato_facturacion
                LEFT JOIN usos_cfdi AS uso
                ON df.usoCFDI=uso.clave
                LEFT JOIN conceptos_facturacion AS concepto
                ON concepto.id_concepto_facturacion=anualidades.id_concepto_facturacion
                LEFT JOIN formas_pago AS forma_pago
                ON anualidades.id_forma_pago=forma_pago.id_forma_pago
                LEFT JOIN sistema_andes.alumnos AS alumno
                ON alumno.id_alumno=anualidades.id_alumno
                LEFT JOIN instituciones_datos AS institucion
                ON alumno.id_institucion=institucion.id_institucion
                LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                ON ciclo.id_ciclo_escolar=anualidades.id_ciclo_escolar
                LEFT JOIN sistema_andes.niveles_educativos AS nivel
                ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                WHERE id_anualidad=".$aRecord["id_anualidad"];
                $aRecords = $oConexion->query($sQuery);

            

                // OBTIENE LOS DATOS DEL CONCEPTO DE COLEGIATURA Y RECARGO DE ESTA
                $sQueryConceptoCol="SELECT id_tipo_servicio, clave_producto, id_unidad_medida,
                CONCAT(concepto_movimiento, ' SEPTIEMBRE ', SUBSTRING(ciclo.fechai, 1, 4), ' A JUNIO DE ', SUBSTRING(ciclo.fechaf, 1, 4), ', ' , alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', 
                UPPER(concepto_nivel), ' ', alumno.grado, ' ', alumno.grupo)
                AS concepto,
                id_impuesto,
                id_tipo_factor,
                CASE WHEN concepto.id_tipo_concepto=2 
                    THEN anualidades.cuota_transporte
                ELSE
                    anualidades.importe
                END AS valor_unitario,
                CASE WHEN concepto.id_tipo_concepto=4
                THEN
                    ((anualidades.importe-anualidades.cuota_beca)*(configuracion.porcentaje_anualidad/100))+anualidades.cuota_beca
                ELSE
                anualidades.cuota_transporte*(configuracion.porcentaje_anualidad/100)
                END AS descuento,
                CASE WHEN concepto.id_tipo_concepto=4
                THEN 
                anualidades.importe+(anualidades.importe*(configuracion.porcentaje_anualidad/100))-anualidades.cuota_beca
                ELSE 
                anualidades.cuota_transporte-(anualidades.cuota_transporte*(configuracion.porcentaje_anualidad/100))
                END AS importe
                FROM anualidades
                LEFT JOIN conceptos_facturacion AS concepto
                ON concepto.id_concepto_facturacion=anualidades.id_concepto_facturacion OR
                CASE WHEN anualidades.cuota_transporte>=1
                    THEN concepto.id_tipo_concepto=2
                END
                LEFT JOIN sistema_andes.alumnos AS alumno
                ON alumno.id_alumno=anualidades.id_alumno
                LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                ON ciclo.id_ciclo_escolar=anualidades.id_ciclo_escolar
                LEFT JOIN sistema_andes.niveles_educativos AS nivel
                ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                LEFT JOIN configuracion
                ON configuracion.estatus=1 AND configuracion.id_institucion=alumno.id_institucion
                WHERE id_anualidad=".$aRecord["id_anualidad"]." AND concepto.id_nivel_educativo=alumno.id_nivel_educativo ORDER BY concepto.id_concepto_facturacion DESC";
                $aRecordsConcepto = $oConexion->query($sQueryConceptoCol);

             }
                // GENERA EL COMPLEMENTO EDUCATIVO PARA LAS FACTURAS SOLO DE COLEGIATURAS
                foreach ($aRecords as $aRecord) 
                {    
                    $dom = new \DOMDocument('1.0', 'utf-8');

                    $element   = $dom->createElement('datos', '');
                    $attribute1 = $dom->createAttribute('xmlns:cfdi');
                    $attribute2 = $dom->createAttribute('xmlns:iedu');
                    $attribute3 = $dom->createAttribute('xmlns:xsi');

                    $attribute1->value = "http://www.sat.gob.mx/cfd/3";
                    $attribute2->value = "http://www.w3.org/2001/XMLSchema-instance";
                    $attribute3->value = "http://www.sat.gob.mx/iedu";   

                    $element->appendChild($attribute1);
                    $element->appendChild($attribute2);
                    $element->appendChild($attribute3);

                    $element2   = $dom->createElement('cfdi:ComplementoConcepto', '');
                    $element->appendChild($element2);

                    $element3   = $dom->createElement('iedu:instEducativas', '');
                    $element2->appendChild($element3);

                    $atri2 = $dom->createAttribute('version');
                    $atri3 = $dom->createAttribute('nombreAlumno');
                    $atri4 = $dom->createAttribute('CURP');
                    $atri5 = $dom->createAttribute('nivelEducativo');
                    $atri6 = $dom->createAttribute('autRVOE');
                    $atri7 = $dom->createAttribute('rfcPago');
                    
                    $atri2->value = "1.0";
                    $atri3->value = $aRecord["nombre"];
                    $atri4->value = $aRecord["curp"];
                    $atri5->value = $aRecord["nivel"];
                    $atri6->value = $aRecord["autRVOE"];
                    $atri7->value = $aRecord["rfc"];
                    
                    $element3->appendChild($atri2);
                    $element3->appendChild($atri3);
                    $element3->appendChild($atri4);
                    $element3->appendChild($atri5);
                    $element3->appendChild($atri6);
                    $element3->appendChild($atri7);

                    $dom->appendChild($element);
                    
                    $complemento = $dom->saveXML();

                    $complemento = str_replace("\n", "", $complemento);
                    $complemento = str_replace("\r", "", $complemento);
                    $complemento = str_replace('"', '\"', $complemento);               
                   
                }

              
            
                    foreach ($aRecordsConcepto as $aRecord) {
                    $i+=1;       
                    if($i==1) {
                        $emb[] = array(
                        "tipo" => $aRecord["id_tipo_servicio"],
                        "clave_sat" => $aRecord["clave_producto"],
                        "cantidad" => 1,
                        "unidad_medida_id" => $aRecord["id_unidad_medida"],
                        "descripcion" => $aRecord["concepto"],
                        "valor_unitario" => $aRecord["valor_unitario"],
                        "descuento" => $aRecord["descuento"],
                        "importe" => $aRecord["valor_unitario"],
                        "impuestos" =>  array(
                             "traslados" =>  [array(
                                                "base" => $aRecord["importe"],
                                                "impuesto_id" => $aRecord["id_impuesto"],
                                                "tipo_factor_id" => $aRecord["id_tipo_factor"],
                                                "importe" => 0
                                                    )],   
                            ),
                        "complemento_concepto" => $complemento
                        );
                    }else
                    {
                        $emb[] = array(
                        "tipo" => $aRecord["id_tipo_servicio"],
                        "clave_sat" => $aRecord["clave_producto"],
                        "cantidad" => 1,
                        "unidad_medida_id" => $aRecord["id_unidad_medida"],
                        "descripcion" => $aRecord["concepto"],
                        "valor_unitario" => $aRecord["valor_unitario"],
                        "descuento" => $aRecord["descuento"],
                        "importe" => $aRecord["valor_unitario"],
                        "impuestos" =>  array(
                             "traslados" =>  [array(
                                                "base" => $aRecord["importe"],
                                                "impuesto_id" => $aRecord["id_impuesto"],
                                                "tipo_factor_id" => $aRecord["id_tipo_factor"],
                                                "importe" => 0
                                                    )],   
                            )
                        );
                    }
                }
                
              
                try{
                        $url="http://10.1.1.18/web/javila/wscfdi/api/metodos/generarLlave"; // pruebas
                        //$url ="https://cfdi.alerta.com.mx/api/metodos/generarLlave"; //producción.
                        $curl = curl_init($url);
                        /** Se establace la devolución del resultado como string si 
                        * se realizó con éxito, o FALSE si falló. */
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        // Sigue la cadena de petición
                        //curl_setopt($obj_curl, CURLINFO_HEADER_OUT, true); TODO: Obsoleto.
                        /** Se establece el HTTP POST. */
                        //  curl_setopt($curl, CURLOPT_POST, true);
                        /** Se establece el número de segundos a esperar 
                        * cuando se está intentando conectar. */
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                        /** Se establece el número maximo de segundos permitidos 
                        * para ejecutar las funciones cURL. */
                        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                        /** Se establecen los datos a envíar vía HTTP POST. */
                        // curl_setopt($curl, CURLOPT_POSTFIELDS,  $peticion);
                        /** Se establece la sesión cURL */
                        $respuesta = curl_exec($curl); 
                        curl_close($curl);

                    // SE GENERA EL JSON CON LOS DATOS A FACTURAR
       
                        foreach ($aRecords as $aRecord) 
                        {
                            $arr_json = array(
                                "datos" => json_encode(
                                    array(
                                       "usuario" => "prueba",
                                        "contrasenia" => "123456",
                                        "llave_sistema" => $respuesta,
                                        "json" => 
                                            array(
                                                    "documento_id" => 11,
                                                    "tipo_comprobante_id" => 1,
                                                    "serie" => "F", 
                                                    "folio" => "0",
                                                    "fecha" => $fecha,
                                                    "forma_pago_id" => $aRecord["forma_pago_id"],
                                                    "condicion_pago" => $aRecord["condicion_pago"],
                                                    "subtotal" => $aRecord["subtotal"],
                                                    "descuento" => $aRecord["descuento"],
                                                    "tipo_moneda_id" => $aRecord["id_tipo_moneda"],
                                                    "total" => $aRecord["importe"],
                                                    "metodo_pago_id" =>$aRecord["id_metodo_pago"],
                                                    "emisor" => array(
                                                        "sucursal_id" => $aRecord["id_sucursal"]
                                                    ),
                                                    "receptor" =>  array(
                                                        "rfc" => $aRecord["rfc"],
                                                        "razon_social" => $aRecord["razon_social"],
                                                        "uso_cfdi_id" => $aRecord["uso_cfdi_id"]
                                                    ),
                                                    "conceptos" => $emb

                                                )        
                            ))
                        );
                    }
                 
                    
                $arr_json =  str_replace("\/","/",$arr_json);
                $arr_json =  str_replace("\\\\","",$arr_json);
                //print_r($arr_json);

                // URL para timbrar comprobante:  http://10.1.1.18/web/javila/wscfdi/api/metodos/timbrado
                //$url="http://10.1.1.18/web/javila/wscfdi/api/metodos/conciliacionInterna"; // pruebas
                $url ="http://10.1.1.18/web/javila/wscfdi/api/metodos/timbrado"; //pruebas.
                $curl = curl_init($url);
                /** Se establace la devolución del resultado como string si 
                * se realizó con éxito, o FALSE si falló. */
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // Sigue la cadena de petición
                //curl_setopt($obj_curl, CURLINFO_HEADER_OUT, true); TODO: Obsoleto.
                /** Se establece el HTTP POST. */
                curl_setopt($curl, CURLOPT_POST, true);
                /** Se establece el número de segundos a esperar 
                * cuando se está intentando conectar. */
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                /** Se establece el número maximo de segundos permitidos 
                * para ejecutar las funciones cURL. */
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                /** Se establecen los datos a envíar vía HTTP POST. */
                curl_setopt($curl, CURLOPT_POSTFIELDS,$arr_json);
                /** Se establece la sesión cURL */
                ///$respuesta = curl_exec($curl);
                $respuesta = json_decode(curl_exec($curl), true);
                
                

             //   echo $respuesta;
                /** Se cierra la sesión cURL */
                curl_close($curl);


            if($respuesta['success']==1){

              //  print_r($respuesta['data']['xml']);
                $xml = simplexml_load_string($respuesta['data']['xml']);
                $ns = $xml->getNamespaces(true);
                $xml->registerXPathNamespace('t', $ns['tfd']);
           
                foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
                 $var_uuid=$tfd['UUID'];
                     
                } 

                $xml = simplexml_load_string($respuesta['data']['xml']);
                $json  = json_encode($xml);
                $xmlArr = json_decode($json, true);
           
                $fecha=Date('Y-m-d');
                foreach ($aRecords as $aRecord) 
                {
                    $sQuery = "INSERT INTO facturas(" .
                              "id_institucion, " .
                              "id_ciclo_escolar," .
                              "id_anualidad, " .
                              "fecha_pago, " .
                              "subtotal, " .
                              "descuento, " .
                              "total, " .
                              "folio_factura," .
                              "fecha_factura," .
                              "id_tipo_factura," .
                              "folio_timbre," .
                              "fecha_timbrado," .
                              "serie_factura," .
                              "xml" .
                              ") VALUES (
                             '".$aRecord["id_institucion"]."',
                             '".$aRecord["id_ciclo_escolar"]."',
                             '".$aRecord["id_anualidad"]."',
                             '".$aRecord["fecha_cobro"]."',
                             '".$aRecord["subtotal"]."',
                             '".$aRecord["descuento"]."',
                             '".$aRecord["importe"]."',
                             '".$respuesta['data']['folio_asignado']."',
                             '".$fecha."',
                             '3',
                             '".$var_uuid."',
                             '".$xmlArr['@attributes']['Fecha']."',
                             '".$xmlArr['@attributes']['Serie']."',
                             '".$respuesta['data']['xml']."'
                             )";
                             
                             $aResultado = $oConexion->query($sQuery);                      

                             $sQuery = "UPDATE anualidades set estatus_anualidad=2 where 
                             id_anualidad=".$aRecord["id_anualidad"];   
                             $aModificar = $oConexion->query($sQuery);

                               
                                    return $this->asJson(array(
                                    "success" => true,
                                    "message" => "Anualidad Agregada",
                                    "records" => $respuesta,
                                    "metadata" => array(
                                        "total_registros" => count($aResultado)
                                    )
                                    ));
                                           

                    }

           }else{
                 return $this->asJson(array(
                                        "success" => true,
                                        "message" => "No se timbro factura de anualidades",
                                        "records" => array(),
                                       
                                     ));

           }
        
            }catch (Exception $o_ex) 
            {
                $s_error = str_replace("'", "", $o_ex->getMessage());
                $s_error = str_replace('"', "", $s_error);
                return $this->asJson(array(
                            "success" => false,
                            "message" => $s_error         
                        ));
            }
        
    }    
}



