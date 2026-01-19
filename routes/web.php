<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Usando el controlador para manejar la ruta
use App\Http\Controllers\HomeController;

use App\Http\Controllers\CategoryController; // Controlador de categorías

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

// http://localhost:8000/categories/index
Route::get('categories/index', [
    CategoryController::class, 
    'index'
]);

// http://localhost:8000/categories/register
Route::post('categories/register', [
    CategoryController::class, 
    'store'
]);

