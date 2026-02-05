<?php

namespace App\Models;

use App\Enums\StatusLancamento;
use App\Enums\TipoCategoria;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lancamento extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'user_id',
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

    public $casts = [
        'tipo' => TipoCategoria::class,
        'status' => StatusLancamento::class,
    ];

    public function categoria(): BelongsTo {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function contaBancaria(): BelongsTo {
        return $this->belongsTo(ContaBancaria::class, 'conta_bancaria_id');
    }

    public function baixasParciais(): HasMany {
        return $this->hasMany(BaixaParcial::class, 'lancamento_id');
    }

    /**
     * @throws \Exception
     */
    public function baixarParcial(
        float $valorPagamento,
        ?string $dataPagamento = null,
        ?string $contaBancariaId = null,
        ?string $metodoPagamento = null,
        ?string $observacoes = null
    ): BaixaParcial {
        return DB::transaction(function () use ($valorPagamento, $dataPagamento, $contaBancariaId, $metodoPagamento, $observacoes) {
            $dataPagamento = $dataPagamento ?? now();
            $contaBancariaId = $contaBancariaId ?? $this->conta_bancaria_id;

            // Validações
            if ($valorPagamento <= 0) {
                throw new \Exception('Valor deve ser maior que zero');
            }

            if ($valorPagamento > $this->valorRestante()) {
                throw new \Exception('Valor maior que o restante a pagar');
            }

            // Criar registro de baixa
            $baixa = $this->baixasParciais()->create([
                'user_id' => Auth::id(),
                'conta_bancaria_id' => $contaBancariaId,
                'valor_pago' => $valorPagamento,
                'data_pagamento' => $dataPagamento,
                'metodo_pagamento' => $metodoPagamento,
                'observacoes' => $observacoes,
            ]);

            // Atualizar lançamento
            $novoValorPago = $this->valor_pago + $valorPagamento;
            $novoStatus = $novoValorPago >= $this->valor ?
                ($this->tipo === 'receita' ? 'recebido' : 'pago') :
                'parcial';

            $this->update([
                'valor_pago' => $novoValorPago,
                'data_pagamento' => $dataPagamento,
                'status' => $novoStatus,
            ]);

            // Atualizar saldo da conta
            $conta = ContaBancaria::find($contaBancariaId);
            if ($this->tipo->value === 'receita') {
                $conta->increment('saldo_atual', $valorPagamento);
            } else {
                $conta->decrement('saldo_atual', $valorPagamento);
            }

            return $baixa;
        });
    }

    /**
     * @throws \Exception
     */
    public function baixar(?string $dataPagamento = null): self
    {
        // Baixa total = baixa parcial do valor restante
        $this->baixarParcial($this->valorRestante(), $dataPagamento);
        return $this->fresh();
    }

    /**
     * @throws \Exception
     */
    public function estornarBaixa(BaixaParcial $baixa): void
    {
        if ($baixa->lancamento_id !== $this->id) {
            throw new \Exception('Baixa não pertence a este lançamento');
        }

        // Reverter saldo
        $conta = $baixa->contaBancaria;
        if ($this->tipo->value === 'receita') {
            $conta->decrement('saldo_atual', $baixa->valor_pago);
        } else {
            $conta->increment('saldo_atual', $baixa->valor_pago);
        }

        // Atualizar lançamento
        $novoValorPago = $this->valor_pago - $baixa->valor_pago;

        if ($novoValorPago <= 0) {
            $novoStatus = 'pendente';
        } else {
            $novoStatus = 'parcial';
        }

        $this->update([
            'valor_pago' => $novoValorPago,
            'status' => $novoStatus,
        ]);

        // Deletar baixa
        $baixa->delete();
    }
    public function valorRestante(): float
    {
        return (float) ($this->valor - $this->valor_pago);
    }

    public function percentualPago(): float
    {
        if ($this->valor == 0) return 0;
        return ($this->valor_pago / $this->valor) * 100;
    }

    public function estaPago(): bool
    {
        return in_array($this->status->value, ['pago', 'recebido']);
    }

    public function estaPendente(): bool
    {
        return $this->status->value === 'pendente';
    }

    public function estaParcial(): bool
    {
        return $this->status->value === 'parcial';
    }

    public function estaAtrasado(): bool
    {
        return $this->estaPendente() &&
            $this->data_vencimento < now();
    }
}
