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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('nome',60); 
            // $table->string('nome', 100); // string com tamanho máximo 100 caracteres
            $table->string('classif',40);
            $table->string('soma',1);
            $table->string('lcto', 1);
            $table->string('ativo', 1);
            //$table->foreignId('movimento_id')->constrained('movimentos');
            $table->timestamps();  // created_at , updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
