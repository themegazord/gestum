<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro: renomeia a coluna
        Schema::table('baixas_parciais', function (Blueprint $table) {
            $table->renameColumn('metodo_pagamento', 'metodo_pagamento_id');
        });

        // Segundo: adiciona a foreign key à coluna existente
        Schema::table('baixas_parciais', function (Blueprint $table) {
            $table->uuid('metodo_pagamento_id')->nullable()->change();
            $table->foreign('metodo_pagamento_id')
                ->references('id')
                ->on('metodo_pagamento')  // verifique o nome correto da tabela
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baixas_parciais', function (Blueprint $table) {
            $table->dropForeign(['metodo_pagamento_id']);
        });

        Schema::table('baixas_parciais', function (Blueprint $table) {
            $table->string('metodo_pagamento_id')->nullable()->change();
            $table->renameColumn('metodo_pagamento_id', 'metodo_pagamento');
        });
    }

};
