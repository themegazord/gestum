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
        Schema::create('recorrencias', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignUuid('conta_bancaria_id')->nullable()->constrained('conta_bancaria')->nullOnDelete();
            $table->enum('tipo', ['receita', 'despesa']);
            $table->string('descricao');
            $table->decimal('valor');
            $table->date('data_vencimento');
            $table->integer('frequencia');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();

            $table->index(['user_id', 'data_vencimento']);
            $table->index(['user_id', 'tipo']);
            $table->index('categoria_id');
            $table->index('conta_bancaria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recorrencias');
    }
};
