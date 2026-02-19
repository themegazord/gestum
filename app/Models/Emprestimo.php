<?php

namespace App\Models;

use App\Enums\StatusEmprestimo;
use App\Enums\TipoEmprestimo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprestimo extends Model
{
    protected $table = 'emprestimos';

    protected $fillable = [
        'user_id',
        'conta_bancaria_id',
        'categoria_id',
        'tipo',
        'descricao',
        'valor',
        'data_emprestimo',
        'data_vencimento',
        'status',
        'observacao',
    ];

    protected $casts = [
        'status' => StatusEmprestimo::class,
        'tipo' => TipoEmprestimo::class,
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
