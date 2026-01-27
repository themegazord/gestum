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
        Schema::table('solicitacao_banco_novo', function (Blueprint $table) {
            $table->text('observacao')->nullable()->change();
            $table->string('decisao')->nullable()->after('observacao');
            $table->text('motivo_decisao')->nullable()->after('decisao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacao_banco_novo', function (Blueprint $table) {
            $table->dropColumn(['decisao', 'motivo_decisao']);
        });
    }
};
