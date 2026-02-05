<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaixaParcial extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'baixas_parciais';

    protected $fillable = [
        'user_id',
        'lancamento_id',
        'conta_bancaria_id',
        'valor_pago',
        'data_pagamento',
        'metodo_pagamento_id',
        'observacoes',
    ];

    public function lancamento(): BelongsTo {
        return $this->belongsTo(Lancamento::class, 'lancamento_id');
    }

    public function contaBancaria(): BelongsTo {
        return $this->belongsTo(ContaBancaria::class, 'conta_bancaria_id');
    }

    public function metodoPagamento(): BelongsTo {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
