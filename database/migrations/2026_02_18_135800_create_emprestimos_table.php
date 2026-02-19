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
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('conta_bancaria_id')->constrained('conta_bancaria')->cascadeOnDelete();
            $table->foreignUuid('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->enum('tipo', ['tomado', 'concedido']);
            $table->string('descricao');
            $table->double('valor');
            $table->date('data_emprestimo');
            $table->date('data_vencimento');
            $table->enum('status', ['pendente', 'pago']);
            $table->text('observacao');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};
