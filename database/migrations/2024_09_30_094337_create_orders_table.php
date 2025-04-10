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
            $table->foreignId('id_address')->constrained('addresses')->onDelete('cascade'); // Clave foránea a addresses (id_address)
            $table->timestamp('fecha'); // Fecha del pedido
            $table->float('precio_total', 8, 2)->unsigned(); // Precio total del pedido
            $table->boolean('estatus')->default(false); // Estatus del pedido (0 = no entregado, 1 = entregado)
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('cascade')->nullable(); // Clave foránea a users (id_user)
            $table->timestamps();
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
