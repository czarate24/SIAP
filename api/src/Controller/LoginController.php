<?php

namespace App\Controller;

use App\Controller\AppController;

class LoginController extends AppController
{
	public function read()
	{
		sleep(5);
		$aParams = $_REQUEST;
		$sUsuario= $aParams["usu"];  //"admin";
		$sContrasena= $aParams["pass"];  //"12345"

        //$usu = $_REQUEST['usu'];
        //$pass = $_REQUEST['pass'];


		$oConexion = $this->getConexion();
		$sQuery = "SELECT num as id_usuario, nombre as nombre_completo, usuario, tipo " .
		          "FROM usuarios " .
                  "WHERE usuario= ? ". " AND PASSWORD= ?";
        //pr($sQuery);

        $aQueryParams = [
        	$sUsuario,
        	$sContrasena
        ];
        $aResultado = $oConexion->query($sQuery, $aQueryParams);
        if (empty($aResultado)){
        	//no existe el usuario
        	return $this->asJson([
        		"success"=>false,
        		"message"=> "Usuario y/o Contraseña Invalidos"
        	]);
        }else{
        	// si existe el usuario
        	return $this->asJson([
        		"success"=>true,
        		"message"=> "Usuario Correcto",
        		"records"=> $aResultado[0]
        	]);
        }

        pr($aResultado);
        exit;
	}
}