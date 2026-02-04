<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lancamento extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'categoria_id',
        'conta_bancaria_id',
        'fatura_id',
        'recorrencia_id',
        'tipo',
        'descricao',
        'valor',
        'valor_pago',
        'data_vencimento',
        'data_pagamento',
        'status',
        'observacoes',
        'anexo',
    ];

    public function categoria(): BelongsTo {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function contaBancaria(): BelongsTo {
        return $this->belongsTo(ContaBancaria::class, 'conta_bancaria_id');
    }
}
