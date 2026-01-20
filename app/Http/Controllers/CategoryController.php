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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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

            $category->slug = $request->slug ? $request->slug : $category->slug;
            $category->name = $request->name ? $request->name : $category->name;
            $category->description = $request->description ? $request->description : $category->description;
            $category->status = $request->status ? $request->status : $category->status;

            $category->save();

            return response()->json([
                'message' => 'Categoría actualizada correctamente.',
                'data' => $category,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al actualizar la categoría.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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

            $category->delete(); // Si tiene el SoftDeletes en el modelo, hará un soft delete

            return response()->json([
                'message' => 'Categoría eliminada correctamente.',
                'data' => null,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al eliminar la categoría.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    public function trash()
    {
        try {
            // Obtenemos solo aquellos con fecha de deleted_at
            // SELECT * FROM categories WHERE deleted_at IS NOT NULL;
            $categories = Category::onlyTrashed()->get();

            if (count($categories) < 1) {
                return response()->json([
                    'message' => 'No hay categorías eliminadas.',
                    'data' => [],
                    'status' => 'success',
                ], 200);
            }

            return response()->json([
                'message' => 'Categorías eliminadas obtenidas correctamente.',
                'data' => $categories,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener las categorías eliminadas.',
                'data' => [],
                'status' => 'error',
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $category = Category::onlyTrashed()
                                ->where('id', $id)
                                ->first();

            if (!$category) {
                return response()->json([
                    'message' => 'Categoría eliminada no encontrada.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }
            
            // Restaurar la categoría
            $category->restore();

            return response()->json([
                'message' => 'Categoría restaurada correctamente.',
                'data' => $category,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al restaurar la categoría.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }
}
