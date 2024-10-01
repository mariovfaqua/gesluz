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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); // Clave primaria (id)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Clave foránea a users (user_id)
            $table->string('linea_1'); // Dirección línea 1
            $table->string('linea_2')->nullable(); // Dirección línea 2 (opcional)
            $table->string('provincia', 50); // Provincia
            $table->string('ciudad', 50); // Ciudad
            $table->string('pais', 100); // País
            $table->string('codigo_postal', 10); // Código postal
            $table->boolean('primaria')->default(false); // Si es la dirección principal o no
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
