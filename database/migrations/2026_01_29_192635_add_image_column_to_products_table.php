<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // php artisan migrate
    {
        Schema::table('products', function (Blueprint $table) {
            // Agregando una nueva columna para la imagen del producto
            $table->string('product_image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // php artisan migrate:rollback
    {
        Schema::table('products', function (Blueprint $table) {
            // Eliminando la columna de imagen del producto
            $table->dropColumn('product_image');
        });
    }
};
