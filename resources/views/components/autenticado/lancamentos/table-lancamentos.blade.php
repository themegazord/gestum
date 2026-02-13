@props([
    'verbo' => null,
    'acao' => null,
    'headers'
])

<div>
    <x-header title="Listagem de contas a {{ $verbo }}"
              subtitle="Visualize e gerencie os valores que você tem a {{ $verbo }}, acompanhando vencimentos e status de pagamento">
        <x-slot:actions>
            <x-button label="Novo {{$this->acao}}" icon="o-plus" class="btn-success"
                      link="{{ route($this->acao === 'recebimento' ? 'autenticado.lancamentos.recebimentos.cadastro' : 'autenticado.lancamentos.pagamentos.cadastro') }}"/>
        </x-slot:actions>
    </x-header>

    <div class="flex flex-col md:grid md:grid-cols-6 gap-4">
        <x-input
            wire:model.live.debounce.300ms="descricao"
            label="Descrição"
            placeholder="Buscar por descrição..."
            icon="o-magnifying-glass"
        />

        <x-input
            wire:model.live="data_vencimento_inicio"
            label="Vencimento de"
            type="date"
        />

        <x-input
            wire:model.live="data_vencimento_fim"
            label="Vencimento até"
            type="date"
        />

        <x-select
            wire:model.live="status"
            label="Status"
            placeholder="Todos"
            :options="\App\Enums\StatusLancamento::options()"
        />

        <x-select
            wire:model.live="categoria_id"
            label="Categoria"
            placeholder="Todas"
            :options="$this->categorias"
            option-value="id"
            option-label="nome"
        />

        <x-select
            wire:model.live="conta_bancaria_id"
            label="Conta bancária"
            placeholder="Todas"
            :options="$this->contasBancarias"
            option-value="id"
            option-label="nome"
        />
    </div>

    <div class="flex justify-end mt-2">
        <x-button
            wire:click="limparFiltros"
            label="Limpar filtros"
            icon="o-x-mark"
            class="btn-ghost btn-sm"
        />
    </div>

    <x-table :headers="$headers" :rows="$this->lancamentos" empty-text="Nenhum pagamento lançado." show-empty-text with-pagination>
        @scope('cell_tipo', $lancamento)
        <x-badge :value="$lancamento->tipo->label()" :class="$lancamento->tipo->color()"/>
        @endscope

        @scope('cell_categoria_id', $lancamento)
        <x-badge
            :value="$lancamento->categoria->nome"
            class="badge"
            style="background-color: {{ $lancamento->categoria->cor }}"
        />
        @endscope

        @scope('cell_conta_bancaria_id', $lancamento)
        {{ $lancamento->contaBancaria->nome }}
        @endscope

        @scope('cell_status', $lancamento)
        <x-badge :value="$lancamento->status->label()" :class="$lancamento->status->color()" />
        @endscope

        @scope('actions', $lancamento)
        <x-dropdown>
            <x-menu-item label="Cancelar {{$this->acao}}" icon="o-trash" wire:click="$dispatch('abrir-modal-confirmacao-remocao-lancamento', { lancamento_id: '{{ $lancamento->id }}' })"/>
            <x-menu-item label="Editar {{$this->acao}}" icon="o-pencil-square" :disabled="$lancamento->contemBaixas()" link="{{ route($this->acao === 'recebimento' ? 'autenticado.lancamentos.recebimentos.edicao' : 'autenticado.lancamentos.pagamentos.edicao', ['lancamentoAtual' => $lancamento->getAttribute('id')]) }}"/>
            <x-menu-item label="Baixas" icon="o-document-duplicate" wire:click="$dispatch('abrir-modal-listagem-baixas', {lancamento_id: '{{ $lancamento->id }}'})"  :disabled="!$lancamento->contemBaixas()"/>
            <x-menu-item label="{{ ucfirst($this->verbo) }}" icon="o-currency-dollar" wire:click="$dispatch('abrir-modal-baixa-parcial', {lancamento_id: '{{ $lancamento->id }}'})" :disabled="$lancamento->estaPago()"/>
        </x-dropdown>
        @endscope
    </x-table>
</div>
