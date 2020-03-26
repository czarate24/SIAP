<?php
namespace App\Controller;
use Cake\Utility\Xml;
use Exception;
class FacturacionIngresosTransporteController extends AppController {

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
        $i=0;
        $aum=0;
        while ($aum < 2) {
            # code...
            $aum++;
            $emb="";

            //OBTINE LOS DATOS PARA CUANDO LA FACTURA ES SOLO DE COLEGIATURA, FACTURANDO POR SEPARADO EL TRANSPORTE.
            $sQuery = "SELECT 
                    IFNULL(df.rfc, 'XAXX010101000') AS rfc, 
                    IFNULL(df.razon_social, CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre)) AS razon_social, 
                    IFNULL(uso.uso_cfdi_id,21) AS uso_cfdi_id, 
                    institucion.id_sucursal, 
                    id_tipo_moneda, 
                    id_impuesto, 
                    id_documento, 
                    carnet.id_institucion, 
                    carnet.id_ciclo_escolar,
                    cobros.id_cobro,
                    cobros.fecha_cobro,
                    id_metodo_pago, 
                    condicion_pago, 
                    cobros.id_facturacion_transporte,
                    CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre,
                    cuota.cuota+ ROUND((cuota.cuota-(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota))+cobros.descuento)*(configuracion.porcentaje_recargo/100),2)- (ROUND((((IFNULL(becas.porc_actual,0)/100))*cuota.cuota),0))+cobros.descuento  + IFNULL(cuota_trans.cuota,0) AS total,
                    forma_pago.forma_pago_id,
                    recargo, 
                    cuota.cuota  + IFNULL(ROUND((cuota.cuota-(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota))+cobros.descuento)*(configuracion.porcentaje_recargo/100),2),0) AS cuota,
                    ROUND(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota),0)+cobros.descuento AS descuento,
                    (cuota.cuota  + IFNULL(ROUND((cuota.cuota-(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota))+cobros.descuento)*(configuracion.porcentaje_recargo/100),2),0))-ROUND(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota),0)+cobros.descuento AS importe, 
                    concepto.concepto_nivel AS nivel,
                    alumno.curp, 
                    autRVOE
                    FROM cobros
                    LEFT JOIN datos_facturacion AS df
                    ON df.id_dato_facturacion=cobros.id_dato_facturacion
                    LEFT JOIN usos_cfdi AS uso
                    ON df.usoCFDI=uso.clave
                    LEFT JOIN carnets_colegiatura AS carnet
                    ON carnet.id_carnet_colegiatura=cobros.id_carnet_colegiatura
                    LEFT JOIN instituciones_datos AS institucion
                    ON carnet.id_institucion=institucion.id_institucion
                    LEFT JOIN conceptos_facturacion AS concepto
                    ON concepto.id_concepto_facturacion=cobros.id_concepto_facturacion
                    LEFT JOIN formas_pago AS forma_pago
                    ON cobros.id_forma_pago=forma_pago.id_forma_pago
                    LEFT JOIN sistema_andes.alumnos AS alumno
                    ON alumno.id_alumno=carnet.id_alumno
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ciclo.id_ciclo_escolar=carnet.id_ciclo_escolar
                    LEFT JOIN sistema_andes.niveles_educativos AS nivel
                    ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                    LEFT JOIN cuotas_colegiaturas AS cuota
                    ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                    LEFT JOIN becas.becas AS becas
                    ON becas.id_beca=carnet.id_beca
                    LEFT JOIN transportes_alumno AS transporte
                    ON transporte.id_transporte_alumno=carnet.id_cuota_transporte
                    LEFT JOIN cuotas_transportes AS cuota_trans
                    ON transporte.id_cuota_transporte=cuota_trans.id_cuota_transporte
                    LEFT JOIN configuracion
                    ON configuracion.id_institucion=cuota.id_institucion
                    WHERE id_cobro=".$IdCobro;
                    $aRecords = $oConexion->query($sQuery);

            // OBTINE LOS DATOS GENERALES PARA LA FACTURA, CUANDO SE FACTURARÁ SOLO EL TRANSPORTE Y RECARGO DE ESTE
            $sQuery = "SELECT 
                    IFNULL(df.rfc, 'XAXX010101000') AS rfc, 
                    IFNULL(df.razon_social, CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre)) AS razon_social, 
                    IFNULL(uso.uso_cfdi_id,21) AS uso_cfdi_id, 
                    institucion.id_sucursal, 
                     CASE WHEN cobros.recargo>1
            THEN
            cuota_trans.cuota + ROUND(cuota_trans.cuota *(configuracion.porcentaje_recargo/100),2)
            ELSE
                    cuota_trans.cuota
                    END AS importe,
                    id_tipo_moneda, 
                    id_impuesto, 
                    id_documento, 
                    carnet.id_institucion, 
                    carnet.id_ciclo_escolar,
                    cobros.id_cobro,
                    cobros.fecha_cobro,
                    id_metodo_pago, 
                    condicion_pago, 
                    cobros.id_facturacion_transporte,
                    CONCAT(alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre) AS nombre,
                    cuota_trans.cuota AS total,
                    forma_pago.forma_pago_id,
                    recargo, 
                    0 AS descuento,
            CASE WHEN cobros.recargo>1
            THEN
            cuota_trans.cuota + ROUND(cuota_trans.cuota *(configuracion.porcentaje_recargo/100),2)
            ELSE
                    cuota_trans.cuota
                    END AS cuota,
                    concepto.concepto_nivel AS nivel, 
                    alumno.curp, 
                    autRVOE
                    FROM cobros
                    LEFT JOIN datos_facturacion AS df
                    ON df.id_dato_facturacion=cobros.id_dato_facturacion
                    LEFT JOIN usos_cfdi AS uso
                    ON df.usoCFDI=uso.clave
                    LEFT JOIN carnets_colegiatura AS carnet
                    ON carnet.id_carnet_colegiatura=cobros.id_carnet_colegiatura
                    LEFT JOIN instituciones_datos AS institucion
                    ON carnet.id_institucion=institucion.id_institucion
                    LEFT JOIN conceptos_facturacion AS concepto
                    ON concepto.id_concepto_facturacion=cobros.id_concepto_facturacion
                    LEFT JOIN formas_pago AS forma_pago
                    ON cobros.id_forma_pago=forma_pago.id_forma_pago
                    LEFT JOIN sistema_andes.alumnos AS alumno
                    ON alumno.id_alumno=carnet.id_alumno
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ciclo.id_ciclo_escolar=carnet.id_ciclo_escolar
                    LEFT JOIN sistema_andes.niveles_educativos AS nivel
                    ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                    LEFT JOIN cuotas_colegiaturas AS cuota
                    ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                    LEFT JOIN becas.becas AS becas
                    ON becas.id_beca=carnet.id_beca
                    LEFT JOIN transportes_alumno AS transporte
                    ON transporte.id_transporte_alumno=carnet.id_cuota_transporte
                    LEFT JOIN cuotas_transportes AS cuota_trans
                    LEFT JOIN configuracion
                    ON configuracion.id_institucion=cuota_trans.id_institucion
                    ON transporte.id_cuota_transporte=cuota_trans.id_cuota_transporte
                    WHERE id_cobro=".$IdCobro;
                    $aRecordsTrans = $oConexion->query($sQuery);



                // OBTIENE LOS DATOS DEL CONCEPTO DE COLEGIATURA Y RECARGO DE ESTA
                $sQueryConceptoCol="SELECT id_tipo_servicio, clave_producto, id_unidad_medida,
                    CASE WHEN carnet.id_mes>=9
                    THEN 
                    CONCAT(concepto_movimiento, ' ', mes.mes, ' ', SUBSTRING(ciclo.fechai, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', UPPER(concepto_nivel), ' ', alumno.grado, ' ', alumno.grupo)
                    ELSE
                    CONCAT(concepto_movimiento, ' ', mes.mes, ' ', SUBSTRING(ciclo.fechaf, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', UPPER(concepto_nivel), ' ', alumno.grado, ' ', alumno.grupo)
                    END AS concepto,
                    id_impuesto,
                    id_tipo_factor,
                    CASE WHEN concepto.id_tipo_concepto=3
                        THEN ROUND((cuota.cuota-(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota))+cobros.descuento)*(configuracion.porcentaje_recargo/100),2)
                    ELSE 
                        cuota.cuota
                    END AS valor_unitario,
                    CASE WHEN concepto.id_tipo_concepto=1
                        THEN (ROUND(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota),0)+cobros.descuento)
                    ELSE
                    0
                    END AS descuento,
                    CASE WHEN concepto.id_tipo_concepto=1
                        THEN cuota.cuota-(ROUND(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota),0)+cobros.descuento)
                    ELSE 
                        ROUND((cuota.cuota-(((IFNULL(becas.porc_actual,0)/100)*cuota.cuota))+cobros.descuento)*(configuracion.porcentaje_recargo/100),2)
                    END AS importe
                    FROM cobros
                    LEFT JOIN carnets_colegiatura AS carnet
                    ON carnet.id_carnet_colegiatura=cobros.id_carnet_colegiatura
                    LEFT JOIN conceptos_facturacion AS concepto
                    ON concepto.id_concepto_facturacion=cobros.id_concepto_facturacion OR
                    CASE WHEN recargo>1
                        THEN concepto.id_tipo_concepto=3
                    END
                    LEFT JOIN sistema_andes.alumnos AS alumno
                    ON alumno.id_alumno=carnet.id_alumno
                    LEFT JOIN meses_ciclo AS mes
                    ON mes.id_mes=carnet.id_mes
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ciclo.id_ciclo_escolar=carnet.id_ciclo_escolar
                    LEFT JOIN sistema_andes.niveles_educativos AS nivel
                    ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                    LEFT JOIN cuotas_colegiaturas AS cuota
                    ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                    LEFT JOIN becas.becas AS becas
                    ON becas.id_beca=carnet.id_beca
                    LEFT JOIN configuracion
                    ON configuracion.id_institucion=cuota.id_institucion
                    WHERE id_cobro=".$IdCobro." AND concepto.id_nivel_educativo=alumno.id_nivel_educativo";
                    $aRecordsConcepto = $oConexion->query($sQueryConceptoCol);

                //OBTIENE LOS DATOS DEL CONCEPTO DE TRANSPORTE PARA LA FACTURACIÓN DE ESTE POR SEPARADO
                $sQueryConceptoTrans="SELECT id_tipo_servicio, clave_producto, id_unidad_medida,
                    CASE WHEN concepto.id_tipo_concepto=3
                    THEN
                    CASE WHEN carnet.id_mes>=9
                    THEN 
                    CONCAT(concepto_movimiento, ' CT ', mes.mes, ' ', SUBSTRING(ciclo.fechai, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', concepto_nivel, ' ', alumno.grado, ' ', alumno.grupo)
                    ELSE
                    CONCAT(concepto_movimiento, ' CT ', mes.mes, ' ', SUBSTRING(ciclo.fechaf, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', concepto_nivel, ' ', alumno.grado, ' ', alumno.grupo)
                    END
                    ELSE
                    CASE WHEN carnet.id_mes>=9
                    THEN 
                    CONCAT(concepto_movimiento, ' ', mes.mes, ' ', SUBSTRING(ciclo.fechai, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', concepto_nivel, ' ', alumno.grado, ' ', alumno.grupo)
                    ELSE
                    CONCAT(concepto_movimiento, ' ', mes.mes, ' ', SUBSTRING(ciclo.fechaf, 1, 4), ', ', alumno.ap_paterno, ' ', alumno.ap_materno, ' ', alumno.nombre, ', CURP: ', alumno.curp, ', ', concepto_nivel, ' ', alumno.grado, ' ', alumno.grupo)
                    END
                    END AS concepto,
                    id_impuesto,
                    id_tipo_factor,
                    CASE WHEN concepto.id_tipo_concepto=3
                        THEN ROUND(cuota_trans.cuota *(configuracion.porcentaje_recargo/100),2)
                    ELSE 
                         cuota_trans.cuota 
                    END AS valor_unitario,
                     CASE WHEN concepto.id_tipo_concepto=3
                        THEN ROUND(cuota_trans.cuota *(configuracion.porcentaje_recargo/100),2)
                    ELSE 
                         cuota_trans.cuota 
                    END AS importe
                    FROM cobros
                    LEFT JOIN carnets_colegiatura AS carnet
                    ON carnet.id_carnet_colegiatura=cobros.id_carnet_colegiatura
                    LEFT JOIN conceptos_facturacion AS concepto
                    ON concepto.id_tipo_concepto=2  OR
                    CASE WHEN recargo>1
                        THEN concepto.id_tipo_concepto=3
                    END
                    LEFT JOIN sistema_andes.alumnos AS alumno
                    ON alumno.id_alumno=carnet.id_alumno
                    LEFT JOIN meses_ciclo AS mes
                    ON mes.id_mes=carnet.id_mes
                    LEFT JOIN sistema_andes.ciclos_escolares AS ciclo
                    ON ciclo.id_ciclo_escolar=carnet.id_ciclo_escolar
                    LEFT JOIN sistema_andes.niveles_educativos AS nivel
                    ON nivel.id_nivel_educativo=alumno.id_nivel_educativo
                    LEFT JOIN cuotas_colegiaturas AS cuota
                    ON cuota.id_cuota_colegiatura=carnet.id_cuota_colegiatura
                    LEFT JOIN transportes_alumno AS transporte
                    ON transporte.id_transporte_alumno=carnet.id_cuota_transporte
                    LEFT JOIN cuotas_transportes AS cuota_trans
                    ON transporte.id_cuota_transporte=cuota_trans.id_cuota_transporte
                    LEFT JOIN configuracion
                    ON configuracion.id_institucion=cuota.id_institucion
                    WHERE id_cobro=".$IdCobro." AND concepto.id_nivel_educativo=alumno.id_nivel_educativo";
                    $aRecordsTransporte = $oConexion->query($sQueryConceptoTrans);

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

              
                // GENERA CADA UNO DE LOS CONCEPTOS DE LA FACTURA,SI ES UNO GENERA LA FACTURA DE COLEGIATURA, SI NO ES UNO 
                // GENERA LA FACTURA DE TRANSPORTE 
                if($aum==1){


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
                 }else{

                    foreach ($aRecordsTransporte as $aRecord) {
                   
                        $emb[] = array(
                        "tipo" => $aRecord["id_tipo_servicio"],
                        "clave_sat" => $aRecord["clave_producto"],
                        "cantidad" => 1,
                        "unidad_medida_id" => $aRecord["id_unidad_medida"],
                        "descripcion" => $aRecord["concepto"],
                        "valor_unitario" => $aRecord["valor_unitario"],
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
                    if($aum==1){
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
                                                    "subtotal" => $aRecord["cuota"],
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
                    } else{
                         foreach ($aRecordsTrans as $aRecord) 
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
                                                    "subtotal" => $aRecord["cuota"],
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

                    }
                    
                $arr_json =  str_replace("\/","/",$arr_json);
                $arr_json =  str_replace("\\\\","",$arr_json);
                print_r($arr_json);

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
                print_r($respuesta);
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

                if($aum==1){
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
                                 '".$aRecord["cuota"]."',
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
                    }else{
                          foreach ($aRecordsTrans as $aRecord) 
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
                                         '".$aRecord["cuota"]."',
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
    }    
}



