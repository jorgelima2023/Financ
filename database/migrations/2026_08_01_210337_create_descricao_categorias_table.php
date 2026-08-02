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
        Schema::create('descricao_categorias', function (Blueprint $table) {
            // table "de" -> "para" 
            // indicar descricao de uma categoria e para qual codigo apontar
            // ao ler arq movimentos ofx
            $table->id()->primary();
            $table->string('descricao_categoria', 60);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descricao_categorias');
    }
};
