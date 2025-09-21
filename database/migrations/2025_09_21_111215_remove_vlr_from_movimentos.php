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
            $table->dropColumn('vlr');
            $table->dropColumn('dia');
            $table->dropColumn('mes');
            $table->dropColumn('ano');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->string('vlr')->nullable(); // ou o tipo original da coluna
            $table->string('dia')->nullable();
            $table->string('mes')->nullable();
            $table->string('ano')->nullable();
        });
    }
};
