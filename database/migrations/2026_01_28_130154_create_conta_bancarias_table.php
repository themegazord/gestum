<?php

use App\Models\Banco;
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
        Schema::create('conta_bancaria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('banco_id')->nullable()->constrained('bancos')->nullOnDelete();
            $table->string('nome');
            $table->string('numero_conta')->nullable();
            $table->string('tipo');
            $table->decimal('saldo_inicial');
            $table->decimal('saldo_atual');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conta_bancaria');
    }
};
