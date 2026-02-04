<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lancamentos', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('categoria_id')->constrained('categorias');
            $table->foreignUuid('conta_bancaria_id')->constrained('conta_bancaria');

            $table->uuid('fatura_id')->nullable();
            $table->uuid('recorrencia_id')->nullable();

            $table->enum('tipo', ['receita', 'despesa']);
            $table->string('descricao');
            $table->decimal('valor', 15, 2);
            $table->decimal('valor_pago', 15, 2)->default(0);

            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();

            $table->enum('status', ['pendente', 'parcial', 'pago', 'recebido', 'atrasado', 'cancelado'])
                ->default('pendente');

            $table->text('observacoes')->nullable();
            $table->string('anexo')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('tipo');
            $table->index('status');
            $table->index('data_vencimento');
            $table->index('data_pagamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lancamentos');
    }
};
