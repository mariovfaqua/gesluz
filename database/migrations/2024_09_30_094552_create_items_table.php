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
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->float('precio', 7, 2)->unsigned();
            $table->enum('distribucion', ['salón', 'dormitorio', 'cocina', 'baño', 'jardín', 'otros']);
            $table->string('material', 50);
            $table->string('color', 30);
            $table->integer('stock')->unsigned();
            $table->foreignId('id_brand')->nullable()->constrained('brands')->onDelete('cascade');
            $table->timestamps();
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
