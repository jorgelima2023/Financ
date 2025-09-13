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
        Schema::table('forma_pgtos', function (Blueprint $table) {
            // alterar tamanho coluna name
            $table->string('nome', 30)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forma_pgtos', function (Blueprint $table) {
            // volta para tamanho anterior da coluna
            $table->string('nome', 255)->change();
        });
    }
};
