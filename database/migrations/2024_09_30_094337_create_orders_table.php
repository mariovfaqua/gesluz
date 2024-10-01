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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Clave primaria (id)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Clave foránea a users (user_id)
            $table->timestamp('fecha'); // Fecha del pedido
            $table->float('precio_total', 8, 2)->unsigned(); // Precio total del pedido
            $table->boolean('estatus')->default(false); // Estatus del pedido (0 = no entregado, 1 = entregado)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
