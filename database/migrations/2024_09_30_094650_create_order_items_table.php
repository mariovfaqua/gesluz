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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Clave foránea a orders (order_id)
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Clave foránea a items (item_id)
            $table->integer('cantidad')->unsigned(); // Cantidad de productos en el pedido
            $table->timestamps(); // Campos created_at y updated_at
            
            // Definir clave primaria compuesta
            $table->primary(['order_id', 'item_id']);
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
