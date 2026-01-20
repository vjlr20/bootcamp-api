<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * CREATE TABLE categories (
         *  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         * slug VARCHAR(100) UNIQUE,
         * name VARCHAR(150),
         * description TEXT,
         * status TINYINT(1) DEFAULT 1,
         * created_at TIMESTAMP NULL,
         * updated_at TIMESTAMP NULL,
         * deleted_at TIMESTAMP NULL
         * );
         * 
         */
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            
            $table->string('slug', 100)->unique(); // Unique slug
            $table->string('name', 150); // Category name
            $table->text('description')->nullable(); // Category description

            $table->boolean('status')->default(true); // Active status
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
