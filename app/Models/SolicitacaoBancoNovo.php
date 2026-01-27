<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitacaoBancoNovo extends Model
{
    use HasUuids;

    protected $table = 'solicitacao_banco_novo';

    protected $fillable = [
        'user_id',
        'nome',
        'codigo',
        'observacao',
        'decisao',
        'motivo_decisao'
    ];

    public function solicitante(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
