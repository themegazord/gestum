@props([
    'contas',
    'categorias',
    'submitLabel' => 'Salvar',
    'submitType' => 'salvar'
])

<x-form wire:submit="{{ $submitType }}">

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Descrição do empréstimo" wire:model="emprestimo.descricao"/>
        <x-select label="Conta bancária" wire:model="emprestimo.conta_bancaria_id" :options="$contas" option-label="nome" option-value="id" placeholder="Selecione uma conta bancária"/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-select label="Status" wire:model="emprestimo.status" :options="\App\Enums\StatusEmprestimo::options()" disabled/>
        <x-select label="Tipo" wire:model="emprestimo.tipo" :options="\App\Enums\TipoEmprestimo::options()" placeholder="Selecione um tipo" />
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Valor principal" wire:model="emprestimo.valor_principal" locale="pt-BR" prefix="R$" money/>
        <x-input label="Valor retorno" wire:model="emprestimo.valor_retorno" locale="pt-BR" prefix="R$" money/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-select label="Categoria principal" wire:model="emprestimo.categoria_principal_id" :options="$categorias" option-label="nome" option-value="id" placeholder="Selecione uma categoria principal"/>
        <x-select label="Categoria retorno" wire:model="emprestimo.categoria_retorno_id" :options="$categorias" option-label="nome" option-value="id" placeholder="Selecione uma categoria de retorno"/>
    </div>

    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-datepicker label="Data do empréstimo" wire:model="emprestimo.data_emprestimo" :config="['altFormat' => 'd/m/Y']"/>
        <x-datepicker label="Data do vencimento" wire:model="emprestimo.data_vencimento" :config="['altFormat' => 'd/m/Y']"/>
    </div>

    <x-textarea label="Observação do empréstimo" rows="3" wire:model="emprestimo.observacao"/>

    <x-slot:actions>
        <x-button label="Voltar" class="btn-error" icon="o-arrow-left" link="{{ route('autenticado.lancamentos.emprestimos.listagem') }}"/>
        <x-button type="submit" label="{{ $submitLabel }}" icon="o-plus" class="btn-success" spiner="{{ $submitType }}"/>
    </x-slot:actions>
</x-form>
