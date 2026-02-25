<?php

namespace App\Models;

use App\Enums\TipoCategoria;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recorrencia extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'categoria_id',
        'conta_bancaria_id',
        'tipo',
        'descricao',
        'valor',
        'data_vencimento',
        'frequencia',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'tipo' => TipoCategoria::class,
        'data_vencimento' => 'date',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function categoria(): BelongsTo {
        return $this->belongsTo(Categoria::class);
    }

    public function contaBancaria(): BelongsTo {
        return $this->belongsTo(ContaBancaria::class);
    }

}
