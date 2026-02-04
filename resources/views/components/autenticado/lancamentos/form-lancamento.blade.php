@props([
    'contas',
    'categorias',
    'submitLabel' => 'Salvar',
    'submitType' => 'salvar'
])
<x-form wire:submit="{{ $submitType }}">
    @php
        $configuracaoDatePicker = ['altFormat' => 'd/m/Y'];
    @endphp
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Descrição" wire:model="lancamento.descricao"/>
        <x-select :options="App\Enums\StatusLancamento::options()" wire:model="lancamento.status" label="Status" disabled/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-select :options="$contas" wire:model="lancamento.conta_bancaria_id" label="Contas bancárias" placeholder="Selecione uma conta..." option-label="nome"/>
        <x-select-group :options="$categorias" wire:model="lancamento.categoria_id" label="Categorias" placeholder="Selecione uma categoria..."/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input  label="Valor" wire:model="lancamento.valor" prefix="R$" locale="pt-BR" money />
        <x-datepicker label="Data do Vencimento" wire:model="lancamento.data_vencimento" icon="o-calendar" :config="$configuracaoDatePicker"/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input  label="Valor recebido" wire:model="lancamento.valor_pago" prefix="R$" locale="pt-BR" readonly />
        <x-datepicker label="Data de Pagamento" wire:model="lancamento.data_pagamento" icon="o-calendar" :config="$configuracaoDatePicker" readonly />
    </div>
    <x-textarea label="Observações" rows="3" wire:model="lancamento.observacoes"/>
    <x-slot:actions>
        <x-button label="Voltar" icon="o-arrow-left" class="btn-error" link="{{ route('autenticado.contas-receber.listagem') }}"/>
        <x-button label="{{ $submitLabel }}" icon="o-plus" class="btn-success" type="submit" spinner="{{ $submitType }}"/>
    </x-slot:actions>
</x-form>
