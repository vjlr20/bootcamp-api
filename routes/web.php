<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* 
    Ruta principal de la aplicación.

    http://localhost:8000
    http://localhost:8000?name=Andrés

    $request -> Controla los datos que vienen en la petición

    ?name=Name -> Parámetro opcional que se puede enviar en la URL
*/
Route::get('/', function (Request $request) {
    $nombre = $request->name;

    $message = "Bienvenido a Laravel!<br/>";

    if ($nombre != NULL) {
        $message .= "Hola, " . $nombre . "!";
    }

    // Retornamos la vista o información
    return $message;
});

/* 
    Ruta principal de la aplicación.

    http://localhost:8000/info/{limit}

    {limit} -> Parámetro obligatorio que se debe enviar en la URL
*/
Route::get('/info/{limit}', function(Request $request, $limit) {
    // Generamos un número aleatorio
    $num = rand(1, $limit);

    if ($limit < 1) {
        return "El límite debe ser un número mayor o igual a 1.";
    }

    $response = "Número aleatorio generado: " . $num . ". ";

    return $response;
});

/* 
    Ruta principal de la aplicación.

    http://localhost:8000/about
*/
Route::get('/about', function() {
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
});

Route::get('/services', function() {
    return "Servicios :D";
});
