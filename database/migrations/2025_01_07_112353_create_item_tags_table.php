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
        Schema::create('item_tags', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Clave foránea a orders (order_id)
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade'); // Clave foránea a items (item_id)
            $table->timestamps(); // Campos created_at y updated_at
            
            // Definir clave primaria compuesta
            $table->primary(['item_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_tags');
    }
};
