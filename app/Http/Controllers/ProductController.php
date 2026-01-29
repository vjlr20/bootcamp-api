<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Product;

use App\Http\Requests\Product\RegisterRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // SELECT * FROM products;
            $products = Product::get();

            if (count($products) < 1) {
                return response()->json([
                    'message' => 'No hay productos disponibles.',
                    'data' => [],
                    'status' => 'success',
                ], 200);
            }

            return response()->json([
                'message' => 'Productos obtenidos correctamente.',
                'data' => $products,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener los productos.',
                'data' => [],
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        // Valida los datos
        $validatedData = $request->validated();

        try {
            $productImageName = NULL;

            // Validamos si es diferente de nulo y si viene un archivo
            if (
                $request->hasFile('product_image') 
                && 
                $request->product_image != NULL
            ) {
                // Recuperar la imagen que se ha enviado
                $image = $request->file('product_image');

                // Formato o extensión de la imagen original (png, jpg, jpeg, etc.)
                $extension = $image->getClientOriginalExtension();

                /**
                 * Generar un nombre único para la imagen
                 * 2026-01-29_203015_product-image.png
                 */
                $filename = Carbon::now()->format('Y-m-d_His') . "_product-image." . $extension;

                // Almacenar la imagen en el almacenamiento (storage/app/public/products)
                $image->storeAs('public/products', $filename);

                $productImageName = $filename;
            }

            $newProduct = new Product();

            // Datos requeridos
            $newProduct->slug = $request->slug;
            $newProduct->name = $request->name;
            $newProduct->description = $request->description == NULL ? NULL : $request->description;
            $newProduct->price = $request->price;
            $newProduct->stock = $request->stock;
            $newProduct->status = $request->status ?? true;
            
            $newProduct->product_image = $productImageName; // Asignamos el nombre de la imagen

            $newProduct->save();

            return response()->json([
                'message' => 'Producto creado correctamente.',
                'data' => $newProduct,
                'status' => 'success',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al crear el producto.',
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
            $product = Product::where('id', $id)->first();

            if ($product == null) {
                return response()->json([
                    'message' => 'Producto no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            return response()->json([
                'message' => 'Producto obtenido correctamente.',
                'data' => $product,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener el producto.',
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
            $product = Product::where('id', $id)->first();

            if ($product == null) {
                return response()->json([
                    'message' => 'Producto no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            // Actualizar datos
            $product->slug = $request->slug ? $request->slug : $product->slug;
            $product->name = $request->name ? $request->name : $product->name;
            $product->description = $request->description ? $request->description : $product->description;
            $product->price = $request->price ? $request->price : $product->price;
            $product->stock = $request->stock ? $request->stock : $product->stock;
            $product->status = $request->status ? $request->status : $product->status;

            $product->save();

            return response()->json([
                'message' => 'Producto actualizado correctamente.',
                'data' => $product,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al actualizar el producto.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::where('id', $id)->first();

            if ($product == null) {
                return response()->json([
                    'message' => 'Producto no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            $product->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente.',
                'data' => null,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al eliminar el producto.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    public function getImage($filename)
    {
        try {
            $path = storage_path('app/private/public/products/' . $filename);

            if (!file_exists($path)) {
                return response()->json([
                    'message' => 'Imagen no encontrada.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            // Retorna la imagen guardada (No formato JSON)
            return response()->file($path);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Error al obtener la imagen del producto.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }
}
