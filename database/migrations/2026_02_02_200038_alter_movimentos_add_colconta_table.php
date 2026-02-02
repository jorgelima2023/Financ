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
        Schema::table('movimentos', function(Blueprint $table){
            $table->foreignId('contascorrente_id')->constrained('contascorrentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentos', function(Blueprint $table){
            $table->dropColumn('contascorrente_id');
        });
    }
};
