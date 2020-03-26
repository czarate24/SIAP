<?php

namespace App\Controller;

use App\Controller\AppController;

class ConfigController extends AppController
{
	public function validaArch()
	{
		
		$oConexion = $this->getConexion();
	    $queryValida = "SELECT * FROM config_invfis WHERE MONTH(fecha_fisico)=MONTH(NOW()) AND YEAR(fecha_fisico)=YEAR(NOW())";
        $aResultado = $oConexion->query($queryValida);
        if (!empty($aResultado)){
        	 return $this->asJson(array(
            "success" => false,
            "message" => "Ya existe",
            "records" =>  $aResultado[0],
            "metadata" => array(
                "total_registros" => count($aResultado)
            )
        ));
        }else{
        	 return $this->asJson(array(
            "success" => true,
            "message" => "No existe",
            "records" =>  $aResultado,
            "metadata" => array(
                "total_registros" => count($aResultado)
            )
        ));
        }
        exit;
	}
}