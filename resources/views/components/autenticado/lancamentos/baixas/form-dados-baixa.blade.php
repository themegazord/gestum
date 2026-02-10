<?php

use Livewire\Component;

new class extends Component
{
    public \App\Models\BaixaParcial $baixa;

    public function mount(\App\Models\BaixaParcial $baixa): void {
        $this->baixa = $baixa;
    }
};
?>

<div class="flex flex-col gap-3">
    <x-input label="Valor pago" prefix="R$" :value="number_format($baixa->valor_pago, 2, ',', '.')" readonly />
    <x-input label="Data de pagamento" :value="\Carbon\Carbon::parse($baixa->data_pagamento)->format('d/m/Y')" readonly />
    <x-input label="Método de pagamento" :value="$baixa->metodoPagamento->nome" readonly />
    <x-input label="Conta bancária" :value="$baixa->contaBancaria->nome" readonly />

    @if($baixa->observacoes)
        <x-textarea label="Observações" :value="$baixa->observacoes" readonly rows="2" />
    @endif
</div>
