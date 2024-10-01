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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Clave primaria (id)
            $table->string('nombre', 100); // Nombre del producto
            $table->text('descripcion'); // Descripción del producto
            $table->float('precio', 7, 2)->unsigned(); // Precio del producto
            $table->string('material', 50); // Material del producto
            $table->string('color', 30); // Color del producto
            $table->string('marca', 50); // Marca del producto
            $table->integer('stock')->unsigned(); // Stock disponible
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
