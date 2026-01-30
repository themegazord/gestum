@props(['bancos', 'tiposConta', 'submitLabel' => 'Salvar', 'submitAction' => 'salvar'])
<x-form wire:submit="{{ $submitAction }}">
    <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
        <x-input label="Nome da conta bancária" wire:model="conta.nome" required />
        <x-choices label="Bancos" wire:model="conta.banco_id" :options="$bancos" searchable single clearable required>
            @scope('item', $banco)
                ({{ $banco->codigo }}) - {{ $banco->nome }}
            @endscope

            @scope('selection', $banco)
                ({{ $banco->codigo }}) - {{ $banco->nome }}
            @endscope
        </x-choices>
    </div>
    <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
        <x-input label="Número da conta" wire:model="conta.numero_conta" />
        <x-select label="Tipo da conta" wire:model="conta.tipo" :options="$tiposConta"
            placeholder="Selecione um tipo de conta..." required />
    </div>
    <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
        <x-input label="Saldo inicial" wire:model="conta.saldo_inicial" prefix="R$" locale="pt-BR" money
            @disabled($submitAction === 'editar') required />
        <x-input label="Saldo atual" wire:model="conta.saldo_atual" prefix="R$" locale="pt-BR" money
            @disabled($submitAction === 'editar') />
    </div>
    <x-slot:actions>
        <div class="flex flex-col md:flex-row gap-4">
            <x-button label="Cancelar" class="btn-error" icon="o-arrow-left"
                link="{{ route('autenticado.contas-bancarias.contas.listagem') }}" />
            <x-button label="{{ $submitLabel }}" type="submit" spinner="{{ $submitAction }}" />
        </div>
    </x-slot:actions>
</x-form>
