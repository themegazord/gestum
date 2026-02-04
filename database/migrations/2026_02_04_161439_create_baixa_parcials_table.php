<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baixas_parciais', function (Blueprint $table) {
            $table->uuid('id')->primary();


            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('lancamento_id')
                ->constrained('lancamentos')
                ->cascadeOnDelete();

            $table->foreignUuid('conta_bancaria_id')
                ->nullable()
                ->constrained('conta_bancaria');


            $table->decimal('valor_pago', 15, 2);
            $table->date('data_pagamento');
            $table->string('metodo_pagamento')->nullable();
            $table->text('observacoes')->nullable();

            $table->timestamps();


            $table->index('data_pagamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baixas_parciais');
    }
};
