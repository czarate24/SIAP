<?php
namespace App\Controller;

class EjemploController extends AppController
{
    public function index()
    {
        return $this->asJson([
            "success" => true,
            "message" => "Respuesta JSON"
        ]);
    }
}
