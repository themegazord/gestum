<?php

namespace App\Models;

use App\Enums\TipoContaBancaria;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancaria extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'conta_bancaria';

    protected $fillable = [
        'user_id',
        'banco_id',
        'nome',
        'numero_conta',
        'tipo',
        'saldo_inicial',
        'saldo_atual',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoContaBancaria::class,
        ];
    }

    public function banco(): BelongsTo {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function proprietario(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
