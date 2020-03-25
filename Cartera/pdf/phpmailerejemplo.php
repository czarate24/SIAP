<?php

        //return  "Entro: ".$n_folio_ord;

    $o_link = $this->conexion(); 


    $n_resultado = $o_link->query($s_sql);
    if ($n_resultado > 0) {
        $date = new DateTime($d_fec_alta);

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 587;
        $mail->AddAddress("lilianagarciaguevara1496@gmail.com");
        $o_link = $this->conexion(); 
        
        $mail->Username   = "lilianagarciaguevara@gmail.com";
        $mail->Password   = "Liliana1496!!";
        $mail->From = "lilianagarciaguevara1496@gmail.com";
        $mail->FromName = "CUENTAS POR PAGAR EXTRANJERAS";

        $mail->AddEmbeddedImage('file:///C:/xampp/htdocs/ProveedoresExtranjeros/resources/cxp3.jpg', 'logo_2u');

        $message= "<h5>Hola:</h5>";

        $subject = utf8_decode("Notificación de la orden de compra Folio ");
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->Send();

        $this->success = 1;

        return "1";
    } else {
     return "Sin exito: ".$n_resultado;
 
}

?>