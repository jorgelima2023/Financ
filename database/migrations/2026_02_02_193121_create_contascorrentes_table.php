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
        Schema::create('contascorrentes', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('nome', 80);
            $table->string('agencia', 7)->nullable();;
            $table->string('conta', 10)->nullable();;
            $table->string('cod_compe', 10)->nullable();;
            $table->string('carteira', 10)->nullable();;
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contascorrentes');
    }
};
