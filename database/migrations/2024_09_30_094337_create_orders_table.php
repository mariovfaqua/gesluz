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
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('cascade')->nullable(); // Clave foránea a users (id_user)
            $table->timestamp('fecha'); // Fecha del pedido
            $table->float('precio_envio', 8, 2)->nullable(); // Precio del envío
            $table->float('precio_total', 8, 2)->unsigned(); // Precio total del pedido
            // $table->boolean('envio')->nullable()->default(null); // Estado del envío (NULL = sin envío, 0 = no enviado, 1 = enviado)
            $table->enum('estatus', [
                'pendiente_email',        // El pedido ha sido creado pero falta enviar el email con el coste del envío
                'pendiente_confirmacion', // El email fue enviado y se espera la respuesta del cliente
                'pendiente_envio',        // El cliente ha confirmado el envío y está pendiente de ser enviado
                'pendiente_recogida',     // El cliente pasará a recogerlo en tienda
                'completado'              // Recogido o entregado, proceso completo
            ])->default('pendiente_recogida');
            // $table->boolean('estatus')->default(false); // Estatus del pedido (0 = no entregado, 1 = entregado)
            $table->foreignId('id_address')->nullable()->constrained('addresses')->onDelete('cascade');  // Clave foránea a addresses (id_address)
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
