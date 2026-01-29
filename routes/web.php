<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Usando el controlador para manejar la ruta
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;


/**
 * Metodo: GET, POST, PUT, DELETE
 * Subrutina: /test
 * Controlador: HomeController
 * Metodo del controlador: test
 * 
 */
Route::get('/test', [
    HomeController::class,
    'test'
]);

/* 
    Ruta principal de la aplicación.

    http://localhost:8000
    http://localhost:8000?name=Andrés

    $request -> Controla los datos que vienen en la petición

    ?name=Name -> Parámetro opcional que se puede enviar en la URL
*/
Route::get('/', [
    HomeController::class,
    'home'
]);

/* 
    Ruta principal de la aplicación.

    http://localhost:8000/info/{limit}

    {limit} -> Parámetro obligatorio que se debe enviar en la URL
*/
Route::get('/info/{limit}', );

/* 
    Ruta principal de la aplicación.

    http://localhost:8000/about
*/
Route::get('/about', [
    HomeController::class,
    'about'
]);

Route::get('/services', [
    HomeController::class,
    'services'
]);

// http://localhost:8000/product-image/nombre-de-la-imagen.jpg
Route::get('/product-image/{filename}', [
    ProductController::class,
    'getImage'
]);

