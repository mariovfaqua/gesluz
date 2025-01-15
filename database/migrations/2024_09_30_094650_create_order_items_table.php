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
        Schema::create('order_items', function (Blueprint $table) {
            $table->foreignId('id_order')->constrained('orders')->onDelete('cascade'); // Clave foránea a orders (id_order)
            $table->foreignId('id_item')->constrained('items')->onDelete('cascade'); // Clave foránea a items (id_item)
            $table->integer('cantidad')->unsigned(); // Cantidad de productos en el pedido
            $table->timestamps();
            
            // Definir clave primaria compuesta
            $table->primary(['id_order', 'id_item']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
