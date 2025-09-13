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
        Schema::table('movimentos', function (Blueprint $table) {
            // alterar tamanho coluna name
            $table->string('descricao', 150)->change();
            $table->string('dcto', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentos', function (Blueprint $table) {
            // volta para tamanho anterior da coluna
            $table->string('descricao', 255)->change();
        });
    }
};
