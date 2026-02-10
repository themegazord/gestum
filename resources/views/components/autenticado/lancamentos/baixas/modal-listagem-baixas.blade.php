<?php

use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

new class extends Component {
    public ?\App\Models\Lancamento $lancamentoAtual = null;
    public array $headers = [
        ['key' => 'valor_pago', 'label' => 'Valor pago', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'data_pagamento', 'label' => 'Data de pagamento', 'format' => ['date', 'd/m/Y']],
        ['key' => 'metodo_pagamento_id', 'label' => 'Método de pagamento']
    ];
    public bool $showModal = false;

    #[\Livewire\Attributes\On('abrir-modal-listagem-baixas')]
    public function abrirModal(string $lancamento_id): void
    {
        $this->lancamentoAtual = \App\Models\Lancamento::withTrashed()->find($lancamento_id);
        $this->showModal = true;
    }

    #[\Livewire\Attributes\Computed]
    #[\Livewire\Attributes\On('recarrega-lancamentos')]
    public function baixasParciais(): Collection
    {
        return $this->lancamentoAtual?->baixasParciais ?? collect();
    }
};
?>

<x-modal wire:model="showModal" class="backdrop-blur" box-class="max-w-3xl" title="Listagem de baixas do lançamento"
         subtitle="Verifique os detalhes da baixa ou faça estorno de uma baixas ou de todas as baixas deste lançamento.">
    <x-table :headers="$headers" :rows="$this->baixasParciais()" class="w-full"
             empty-text="Não contêm baixas cadastradas nesse lançamento" show-empty-text>
        @scope('cell_metodo_pagamento_id', $baixa)
        {!! $baixa->metodoPagamento?->badgeMetodoPagamento() !!}
        @endscope

        @scope('actions', $baixa)
        <div class="flex gap-2">
            <x-button class="btn-ghost" tooltip="Visualizar baixa" icon="o-eye" wire:click="$dispatch('abrir-modal-visualizacao-baixa', { baixa_id: '{{ $baixa->id }}' })"/>
            <x-button class="btn-ghost" tooltip="Estornar baixa" icon="o-trash"
                      wire:click="$dispatch('abrir-modal-confirmacao-estornar-baixa', { baixa_id: '{{ $baixa->id }}' })"/>
        </div>
        @endscope
    </x-table>

    <x-slot:actions>
        <x-button wire:click="$toggle('showModal')" label="Fechar" class="btn-error"/>
    </x-slot:actions>
</x-modal>
