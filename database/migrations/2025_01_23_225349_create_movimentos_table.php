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
        Schema::create('movimentos', function (Blueprint $table) {
            $table->id()->primary();
            $table->integer('tipo');  // 'O' obrigacao  'P' previsao 
            $table->integer('dia');
            $table->integer('mes');
            $table->integer('ano');
            // $table->integer('categorias');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('forma_pgto_id')->constrained('forma_pgtos');
            $table->string('descricao');
            $table->float('vlr')->default(0);
            $table->float('valor')->default(0);
            // $table->integer('forma_pgtos');
            $table->date('dt');     // data do lancamento
            $table->integer('dcto');// numero do documento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentos');
    }
};
