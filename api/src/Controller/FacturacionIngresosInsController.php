<?php
namespace App\Controller;
use Cake\Utility\Xml;
use Exception;
class FacturacionIngresosInsController extends AppController {
  
    /**
     * Lee el catalogo de ciclos
     *
     * @return Cake\Network\Response
     */
    public function read() {
        $oConexion = $this->getConexion();
        $IdCobro = $this->request->query('id_cobro');
        $fecha= Date("Y-m-d\TH:i:s");

        //OBTIENE LOS DATOS DEL EMISOR Y RECEPTOR DE LA FACTURA.
        $sQuery = "SELECT 
                    IFNULL(df.rfc, 'XAXX010101000') AS rfc, 
                    IFNULL(df.razon_social, CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre)) AS razon_social, 
                    IFNULL(uso.uso_cfdi_id,21) AS uso_cfdi_id, 
                    institucion.id_sucursal, 
                    cobros.importe  AS base, 
                    id_tipo_moneda, 
                    id_impuesto, 
                    carnet.id_institucion,
                    carnet.id_ciclo_escolar,
                    id_cobro,
                    id_documento, 
                    id_metodo_pago, 
                    cobros.fecha_cobro,
                    condicion_pago, 
                    cuota.cuota AS total,
                    forma_pago.forma_pago_id,
                    IFNULL((descuentos.descuento/100)*cuota.cuota,0) + (cobros.descuento)AS descuento
                    FROM cobros
                    LEFT JOIN datos_facturacion AS df
                    ON df.id_dato_facturacion=cobros.id_dato_facturacion
                    LEFT JOIN usos_cfdi AS uso
                    ON df.usoCFDI=uso.clave
                    LEFT JOIN carnets_inscripcion AS carnet
                    ON carnet.id_carnet_inscripcion=cobros.id_carnet_inscripcion
                    LEFT JOIN instituciones_datos AS institucion
                    ON carnet.id_institucion=institucion.id_institucion
                    LEFT JOIN conceptos_facturacion AS concepto
                    ON concepto.id_concepto_facturacion=cobros.id_concepto_facturacion
                    LEFT JOIN formas_pago AS forma_pago
                    ON cobros.id_forma_pago=forma_pago.id_forma_pago
                    LEFT JOIN sistema_andes.alumnos AS alumno
                    ON alumno.id_alumno=carnet.id_alumno
                    LEFT JOIN cuotas_inscripciones AS cuota
                    ON cuota.id_cuota_inscripcion=carnet.id_cuota_inscripcion
                    LEFT JOIN descuentos_inscripciones AS descuentos
                    ON descuentos.id_descuento_inscripcion=cobros.id_descuento_inscripcion
                    WHERE id_cobro=".$IdCobro;
                $aRecords = $oConexion->query($sQuery);

            // OBTIENE LOS DATOS DE CADA UNO DE LOS CONCEPTOS A FACTURAR
            $sQueryConcepto="SELECT id_tipo_servicio, clave_producto, id_unidad_medida,
                CONCAT(concepto_movimiento, ' ', ciclo.ciclo,', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', UPPER(nivel.nombre), ' ', alumno.grado, ' ', alumno.grupo)
                AS concepto,
                id_impuesto,
                cuota.cuota,
                cobros.importe,
                IFNULL((descuentos.descuento/100)*cuota.cuota,0) + (cobros.descuento)AS descuento,
                id_tipo_factor
                FROM cobros
                LEFT JOIN carnets_inscripcion AS carnet
                ON carnet.id_carnet_inscripcion=cobros.id_carnet_inscripcion
                LEFT JOIN conceptos_facturacion AS concepto
                ON concepto.id_concepto_facturacion=cobros.id_concepto_facturacion
                LEFT JOIN sistema_andes.alumnos AS alumno
                ON alumno.id_alumno=carnet.id_alumno
                LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                ON ciclo.id_ciclo_escolar=carnet.id_ciclo_escolar
                LEFT JOIN sistema_andes.niveles_educativos AS nivel
                ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                LEFT JOIN cuotas_inscripciones AS cuota
                ON cuota.id_cuota_inscripcion=carnet.id_cuota_inscripcion
                 LEFT JOIN descuentos_inscripciones AS descuentos
                ON descuentos.id_descuento_inscripcion=cobros.id_descuento_inscripcion
                WHERE id_cobro=".$IdCobro;
                $aRecordsConcepto = $oConexion->query($sQueryConcepto);

            // GENERA CADA UNO DE LOS CONCEPTOS DE LA FACTURA
                foreach ($aRecordsConcepto as $aRecord) {
                    $emb[] = array(
                    "tipo" => $aRecord["id_tipo_servicio"],
                    "clave_sat" => $aRecord["clave_producto"],
                    "cantidad" => 1,
                    "unidad_medida_id" => $aRecord["id_unidad_medida"],
                    "descripcion" => $aRecord["concepto"],
                    "valor_unitario" => $aRecord["cuota"],
                    "descuento" => $aRecord["descuento"],
                    "importe" => $aRecord["cuota"],
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
                                            "subtotal" => $aRecord["total"],
                                            "descuento" => $aRecord["descuento"],
                                            "tipo_moneda_id" => $aRecord["id_tipo_moneda"],
                                            "total" => $aRecord["base"],
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
            $respuesta = json_decode(curl_exec($curl), true);
            //print_r($respuesta);
            /** Se cierra la sesión cURL */
            curl_close($curl);



           if($respuesta['success']==1){


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
                              "id_cobro, " .
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
                             '".$aRecord["id_cobro"]."',
                             '".$aRecord["fecha_cobro"]."',
                             '".$aRecord["total"]."',
                             '".$aRecord["descuento"]."',
                             '".$aRecord["base"]."',
                             '".$respuesta['data']['folio_asignado']."',
                             '".$fecha."',
                             '1',
                             '".$var_uuid."',
                             '".$xmlArr['@attributes']['Fecha']."',
                             '".$xmlArr['@attributes']['Serie']."',
                             '".$respuesta['data']['xml']."'
                             )";
                             // print_r($sQuery);
                             $aResultado = $oConexion->query($sQuery);
                             

                             $sQuery = "UPDATE cobros set id_estatus_cobro=2 where 
                             id_cobro=".$aRecord["id_cobro"];   
                             $aModificar = $oConexion->query($sQuery);

                               if (count($aResultado)>0){
                                    return $this->asJson(array(
                                    "success" => true,
                                    "message" => "Factura Agregada",
                                    "records" => $aResultado,
                                    "metadata" => array(
                                        "total_registros" => count($aResultado)
                                    )
                                    ));
                                }else{
                                     return $this->asJson(array(
                                        "success" => true,
                                        "message" => "No se agregó Factura",
                                        "records" => $aResultado,
                                        "metadata" => array(
                                            "total_registros" => count($aResultado)
                                        )
                                     ));

                                }            

                    }

           }

    

    }
}



