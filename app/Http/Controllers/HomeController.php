<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) 
    {
        $nombre = $request->name;
        $apellido = $request->lastname;

        $message = "Bienvenido a mi API con Laravel! :D<br/>";

        if ($nombre != NULL) {
            $message .= "Hola, " . $nombre . "!";
        }

        if ($apellido != NULL) {
            $message .= " " . $apellido . "!";
        }

        // Retornamos la vista o información
        return $message;
    }

    public function info(Request $request, $limit)
    {
        // Generamos un número aleatorio
        $num = rand(1, $limit);

        if ($limit < 1) {
            return "El límite debe ser un número mayor o igual a 1.";
        }

        $response = "Número aleatorio generado: " . $num . ". ";

        return $response;
    }

    public function about()
    {
        $services = array(
            'Desarrollo web',
            'Diseño gráfico',
            'Marketing digital',
            'Consultoría tecnológica',
            'Soporte técnico'
        );

        $message = "Servicios ofrecidos:<br/>";

        foreach ($services as $service) {
            $message .= "- " . $service . "<br/>";
        }

        return $message;
    }

    public function services()
    {
        return "Servicios :D";
    }

    public function test()
    {
        return "Acción de prueba en el controlador de Home";
    }
}
