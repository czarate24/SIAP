<?php

namespace App\Controller;

use Exception;

class AjustesController extends AppController {

 

public function validaAjustes() {

        $oConexion = $this->getConexion();

        $sQueryValidacion = "SELECT  art.id AS id_articulo,
        art.grupo,
        gpo.descrip AS gpo_descrip,
        art.cod_barr,
        art.descrip,
        art.marca,
        art.existe,
        art.costo,
        IF(art.exi_fis>art.existe,art.exi_fis-art.existe,0) AS faltante_exi,
        IF(art.cto_fis>art.costo,art.cto_fis-art.costo,0) AS faltante_cto,
        IF(art.exi_fis<art.existe,art.existe-art.exi_fis,0) AS sobrante_exi,
        IF(art.cto_fis<art.costo,art.costo-art.cto_fis,0) AS sobrante_cto,
        IF(art.exi_fis = art.existe and art.costo=art.cto_fis,0,1) as diferencia, 
        art.pvta,
        art.fec_alta,
        art.sta,
        IF(art.sta='B','INACTIVO','ACTIVO') AS sta_descrip,
        art.exi_fis,
        art.cto_fis,
        art.uni_med,
        art.f_cant,
        art.f_costo,
        art.cont_fis,
        art.codigob,
        art.consec
        FROM inv_fis AS art
        INNER JOIN grupo AS gpo
        ON art.grupo=gpo.grupo
        WHERE (art.exi_fis-art.existe>0) and art.sta<>'B' 
        ORDER BY gpo_descrip,descrip";


        $aValidacion = $oConexion->query($sQueryValidacion);

        foreach ($aValidacion as $Valida)
       {
         
            if (($Valida['existe']<>0 and $Valida['costo']=0) or ($Valida['existe']=0 and $Valida['costo']<>0))
            {
                print_r("Existe algún dato por capturar ".$Valida['consec']." ".$Valida['descrip']);
                exit;
            }
            if($Valida['existe']<$Valida['sobrante_exi'])
            {
                print_r("No hay existencia Suficiente ".$Valida['descrip']);
                exit;
            }

            if($Valida['costo']<$Valida['sobrante_cto'])
            {
                print_r("No hay costo Suficiente ".$Valida['descrip']);
                exit;

            }
            if(($Valida['existe']<>0 and $Valida['costo']=0) or ($Valida['existe']=0 and $Valida['costo']<>0))
            {
                print_r("Existe algún dato por capturar ".$Valida['descrip']);
                exit;
            }
            generarajustes();

        }

       
    }

    
    public function generarajustes() {
        
      $oConexion = $this->getConexion();

        $sQueryFolio = "SELECT * FROM config where inventario='C'";
        $aFolio = $oConexion->query($sQueryFolio);
        foreach ($aFolio as $Folio)
       {
            $varFolio=$Folio['fol_ajuste'];      
       }

 



        $sQueryFaltantes = "SELECT  art.id AS id_articulo,
        art.grupo,
        gpo.descrip AS gpo_descrip,
        art.cod_barr,
        art.descrip,
        art.marca,
        art.existe,
        art.costo,
        IF(art.exi_fis>art.existe,art.exi_fis-art.existe,0) AS faltante_exi,
        IF(art.cto_fis>art.costo,art.cto_fis-art.costo,0) AS faltante_cto,
        IF(art.exi_fis = art.existe and art.costo=art.cto_fis,0,1) as diferencia, 
        art.pvta,
        art.fec_alta,
        art.sta,
        IF(art.sta='B','INACTIVO','ACTIVO') AS sta_descrip,
        art.exi_fis,
        art.cto_fis,
        art.uni_med,
        art.f_cant,
        art.f_costo,
        art.cont_fis,
        art.codigob,
        art.consec
        FROM inv_fis AS art
        INNER JOIN grupo AS gpo
        ON art.grupo=gpo.grupo
        WHERE (art.exi_fis-art.existe>0) and art.sta<>'B' 
        ORDER BY gpo_descrip,descrip";


      //  print_r($sQueryFaltantes); 

       $aFaltantes = $oConexion->query($sQueryFaltantes);

     //  print_r($aFaltantes); // $aFaltantes;

       foreach ($aFaltantes as $Faltante)
       {
 

            $sqlAjFal = "UPDATE articulo SET existe=existe+".$Faltante['faltante_exi'].", costo=costo+".$Faltante['faltante_cto']." WHERE consec=".$Faltante['consec']."";

                 print_r($sqlAjFal."<br>");
                 $oConexion->query($sqlAjFal);
                 //ejecutar consulta 

             $sqlInsFal = "INSERT INTO movtos
                        (cve_mov,
                         num_doc,
                         consec,
                         descrip,
                         grupo,
                         cod_barr,
                         cantidad,
                         costo,
                         fec_mov,
                         autorizo,
                         codigob,
                         alm) VALUES 
                         ('190',
                         ".$varFolio.",
                         ".$Faltante['consec'].",
                         '".$Faltante['descrip']."',
                         ".$Faltante['grupo'].",
                         ".$Faltante['cod_barr'].",
                         ".$Faltante['faltante_exi'].",
                         ".$Faltante['faltante_cto'].",
                         '".date("Y-m-d H:i:s")."',
                         '".$_REQUEST['nombre']."',
                         ".$Faltante['codigob'].",
                          'C')";

                           $oConexion->query($sqlInsFal);
                    print_r($sqlInsFal."<br>");

       }

      
       $sQuerySobrantes = "SELECT  art.id AS id_articulo,
        art.grupo,
        gpo.descrip AS gpo_descrip,
        art.cod_barr,
        art.descrip,
        art.marca,
        art.existe,
        art.costo,
        IF(art.exi_fis<art.existe,art.existe-art.exi_fis,0) AS sobrante_exi,
        IF(art.cto_fis<art.costo,art.costo-art.cto_fis,0) AS sobrante_cto,
        IF(art.exi_fis = art.existe and art.costo=art.cto_fis,0,1) as diferencia, 
        art.pvta,
        art.fec_alta,
        art.sta,
        IF(art.sta='B','INACTIVO','ACTIVO') AS sta_descrip,
        art.exi_fis,
        art.cto_fis,
        art.uni_med,
        art.f_cant,
        art.f_costo,
        art.cont_fis,
        art.codigob,
        art.consec
        FROM inv_fis AS art
        INNER JOIN grupo AS gpo
        ON art.grupo=gpo.grupo
        WHERE (art.existe-art.exi_fis>0) and art.sta<>'B' 
        ORDER BY gpo_descrip,descrip";


       // print_r($sQuerySobrantes); 
    //   $aActKardexS = $oConexion->query($sqlInsFal);
       $aSobrantes = $oConexion->query($sQuerySobrantes);

     //  print_r($aFaltantes); // $aFaltantes;

       foreach ($aSobrantes as $Sobrante)
       {
            print_r($Sobrante['consec']."<br>");
            print_r($Sobrante['descrip']."<br>");
            print_r($Sobrante['existe']."<br>");
            print_r($Sobrante['costo']."<br>");
            print_r($Sobrante['exi_fis']."<br>");
            print_r($Sobrante['cto_fis']."<br>");

 


            $sqlAjSob = "UPDATE articulo SET existe=existe-".$Sobrante['sobrante_exi'].", costo=costo-".$Sobrante['sobrante_cto']." WHERE consec=".$Sobrante['consec']."";

                 print_r($sqlAjSob."<br>");
                 $oConexion->query($sqlAjSob);
                 //ejecutar consulta 

             $sqlInsSob = "INSERT INTO movtos
                        (cve_mov,
                         num_doc,
                         consec,
                         descrip,
                         grupo,
                         cod_barr,
                         cantidad,
                         costo,
                         fec_mov,
                         autorizo,
                         codigob,
                         alm) VALUES 
                         ('290',
                         ".$varFolio.",
                         ".$Sobrante['consec'].",
                         '".$Sobrante['descrip']."',
                         ".$Sobrante['grupo'].",
                         ".$Sobrante['cod_barr'].",
                         ".$Sobrante['sobrante_exi'].",
                         ".$Sobrante['sobrante_cto'].",
                         '".date("Y-m-d H:i:s")."',
                         '".$_REQUEST['nombre']."',
                         ".$Sobrante['codigob'].",
                          'C')";

                     $oConexion->query($sqlInsSob);
                    print_r($sqlInsSob."<br>");

       }

            $varAcFolio= $varFolio+1;
            $sQueryAct = "UPDATE config set fol_ajuste=".$varAcFolio." where inventario='C'";
            $aAct = $oConexion->query($sQueryAct);

            $sQueryF = "UPDATE config_invfis SET fecha_ajuste='".date("Y-m-d H:i:s")."', folio_ajuste=".$varFolio.",observaciones='CERRADO CON AJUSTES', estatus=2 WHERE MONTH(fecha_fisico)=MONTH(NOW()) AND YEAR(fecha_fisico)=YEAR(NOW())";

                    print_r($sQueryF);

                   $aFinal = $oConexion->query($sQueryF);
       
      
        return $this->asJson(array(
            "success" => true,
            "message" => "ajustes aplicados",
            "records" =>  $aFinal,
            "metadata" => array(
                "total_registros" => count( $aFinal)
            )
        )); 

    }

    /**
     * Crea plazas
     *
     * @return Cake\Network\Response
     */

    public function update() {
        $oConexion = $this->getConexion();

        $aDatos = $this->request->data;
        $aRecords = json_decode($aDatos["records"], true);

        foreach ($aRecords as $aRecord) {
            // actualiza el registro de la plaza
            $sQuery = "UPDATE plaza SET " .
            "empresa_id = ?, " .
            "plaza = ?, " .
            "plaza2 = ?, " .
            "ciudad = ?, " .
            "estado = ?, " .
            "direccion_sucursal = ?, " .
            "telefono_pedido = ?, " .
            "telefono_queja = ?, " .
            "telefono_queja2 = ?, " .
            "permiso = ?, " .
            "factor_control = ?, " .
            "factor_space = ?, " .
            "clientes_estacionario = ?, " .
            "limite_descarga = ?, " .
            "otorga_puntos = ? " .
            "WHERE id = ?";
            $aQueryParams = array(
                $aRecord["empresa_id"],
                $aRecord["plaza"],
                $aRecord["plaza2"],
                $aRecord["ciudad"],
                $aRecord["estado"],
                $aRecord["direccion_sucursal"],
                $aRecord["telefono_pedido"],
                $aRecord["telefono_queja"],
                $aRecord["telefono_queja2"],
                $aRecord["permiso"],
                $aRecord["factor_control"],
                $aRecord["factor_space"],
                $aRecord["clientes_estacionario"],
                $aRecord["limite_descarga"],
                $aRecord["otorga_puntos"],
                $aRecord["id"]
            );
            $oConexion->query($sQuery, $aQueryParams);
        }

        return $this->asJson(array(
            "success" => true,
            "message" => "Plazas actualizadas"
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
