<?php

namespace App\Models;

use App\Enums\StatusEmprestimo;
use App\Enums\TipoEmprestimo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Emprestimo extends Model
{
    use HasUuids;

    protected $table = 'emprestimos';

    protected $fillable = [
        'user_id',
        'conta_bancaria_id',
        'categoria_principal_id',
        'categoria_retorno_id',
        'tipo',
        'descricao',
        'valor_principal',
        'valor_retorno',
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

    public function categoriaPrincipal(): BelongsTo {
        return $this->belongsTo(Categoria::class, 'categoria_principal_id');
    }

    public function categoriaRetorno(): BelongsTo {
        return $this->belongsTo(Categoria::class, 'categoria_retorno_id');
    }

    public function contaBancaria(): BelongsTo {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function lancamentos(): HasMany {
        return $this->hasMany(Lancamento::class, 'emprestimo_id');
    }

    public function lancamentoPrincipal(): HasOne {
        $tipoLancamento = $this->tipo === TipoEmprestimo::TOMADO ? 'receita' : 'despesa';
        return $this->hasOne(Lancamento::class, 'emprestimo_id')->where('tipo', $tipoLancamento);
    }

    public function lancamentoRetorno(): HasOne {
        $tipoLancamento = $this->tipo === TipoEmprestimo::TOMADO ? 'despesa' : 'receita';
        return $this->hasOne(Lancamento::class, 'emprestimo_id')->where('tipo', $tipoLancamento);
    }
}
