<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        $rules = array(
            'slug' => [
                'required', 
                'string', 
                'max:100', 
                'unique:products,slug'
            ],
            'name' => [
                'required', 
                'string', 
                'max:150'
            ],
            'description' => [
                'nullable', 
                'string'
            ],
            'product_image' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,webp'
            ],
            'price' => [
                'required', 
                'numeric', 
                'min:0.01'
            ],
            'stock' => [
                'required', 
                'integer', 
                'min:0'
            ],
        );
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = array(
            'slug.required' => 'El campo slug es obligatorio.',
            'slug.string' => 'El campo slug debe ser una cadena de texto.',
            'slug.max' => 'El campo slug no debe exceder los 100 caracteres.',
            'slug.unique' => 'El slug proporcionado ya está en uso.',

            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe exceder los 150 caracteres.',

            'description.string' => 'El campo descripción debe ser una cadena de texto.',

            'product_image.image' => 'El archivo debe ser una imagen válida.',
            'product_image.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg.',

            'price.required' => 'El campo precio es obligatorio.',
            'price.numeric' => 'El campo precio debe ser un número.',
            'price.min' => 'El campo precio debe ser al menos 0.01.',

            'stock.required' => 'El campo stock es obligatorio.',
            'stock.integer' => 'El campo stock debe ser un número entero.',
            'stock.min' => 'El campo stock no puede ser negativo.',
        );

        return $messages;
    }
}
