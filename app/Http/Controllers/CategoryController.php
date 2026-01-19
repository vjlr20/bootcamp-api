<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Usando modelo de categorias
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Obtener todas las categorías
            $categories = Category::get();

            // Verificar si hay categorías
            if (count($categories) < 1) {
                return response()->json([
                    'message' => 'No hay categorías disponibles.',
                    'data' => [],
                    'status' => 'success',
                ], 200);
            }

            // Retornar las categorías en formato JSON
            return response()->json([
                'message' => 'Categorías obtenidas correctamente.',
                'data' => $categories,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener las categorías.',
                'data' => [],
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $newCategory = new Category();

            $newCategory->slug = $request->slug;
            $newCategory->name = $request->name;
            $newCategory->description = $request->description;
            $newCategory->status = $request->status ?? true;

            $newCategory->save();

            return response()->json([
                'message' => 'Categoría creada correctamente.',
                'data' => $newCategory,
                'status' => 'success',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al crear la categoría.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return response()->json([
                    'message' => 'Categoría no encontrada.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            return response()->json([
                'message' => 'Categoría obtenida correctamente.',
                'data' => $category,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener la categoría.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
