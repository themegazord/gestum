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
        Schema::create('solicitacao_banco_novo', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nome', 100);
            $table->string('codigo', 3);
            $table->text('observacao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacao_banco_novo');
    }
};
