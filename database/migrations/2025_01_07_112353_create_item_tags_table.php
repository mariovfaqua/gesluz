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
            $table->foreignId('id_item')->constrained('items')->onDelete('cascade'); // Clave foránea a orders (id_order)
            $table->foreignId('id_tag')->constrained('tags')->onDelete('cascade'); // Clave foránea a items (id_item)
            $table->timestamps(); // Campos created_at y updated_at
            
            // Definir clave primaria compuesta
            $table->primary(['id_item', 'id_tag']);
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
