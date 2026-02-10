<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast, \Livewire\WithPagination;

    #[Url]
    public ?string $descricao = null;
    #[Url]
    public ?string $data_vencimento_inicio = null;
    #[Url]
    public ?string $data_vencimento_fim = null;
    #[Url]
    public ?string $status = null;
    #[Url]
    public ?string $categoria_id = null;
    #[Url]
    public ?string $conta_bancaria_id = null;

    public array $headers = [
        ['key' => 'descricao', 'label' => 'Descrição'],
        ['key' => 'tipo', 'label' => 'Tipo'],
        ['key' => 'valor', 'label' => 'Valor em aberto', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'valor_pago', 'label' => 'Valor pago', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'categoria_id', 'label' => 'Categoria'],
        ['key' => 'conta_bancaria_id', 'label' => 'Conta bancária'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'data_vencimento', 'label' => 'Data de Vencimento', 'format' => ['date', 'd/m/Y']],
    ];

    #[Computed]
    #[\Livewire\Attributes\On('recarrega-lancamentos')]
    public function categorias(): Collection
    {
        return \App\Models\Categoria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('tipo', \App\Enums\TipoCategoria::RECEITA)
            ->get()
            ->filter(fn ($categoria) => !$categoria->ehCategoriaPai());
    }

    #[Computed]
    public function contasBancarias(): Collection
    {
        return \App\Models\ContaBancaria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get();
    }

    #[\Livewire\Attributes\On('fechar-modal-baixa-parcial')]
    public function fechaModalBaixaParcial(): void {
        $this->lancamentos();

        $this->success('Baixa de lançamento', 'Baixa efetivada com sucesso');
    }

    #[Computed]
    public function lancamentos(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Lancamento::withTrashed()
            ->where('lancamentos.user_id', \Illuminate\Support\Facades\Auth::id())
            ->when($this->descricao, fn ($query) => $query->where('descricao', 'like', "%{$this->descricao}%"))
            ->when($this->data_vencimento_inicio, fn ($query) => $query->whereDate('data_vencimento', '>=', $this->data_vencimento_inicio))
            ->when($this->data_vencimento_fim, fn ($query) => $query->whereDate('data_vencimento', '<=', $this->data_vencimento_fim))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->categoria_id, fn ($query) => $query->where('categoria_id', $this->categoria_id))
            ->when($this->conta_bancaria_id, fn ($query) => $query->where('conta_bancaria_id', $this->conta_bancaria_id))
            ->latest()
            ->with('categoria', 'contaBancaria')
            ->paginate();
    }

    public function limparFiltros(): void
    {
        $this->reset(['descricao', 'data_vencimento_inicio', 'data_vencimento_fim', 'status', 'categoria_id', 'conta_bancaria_id']);
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Contas a Receber');
    }
}

?>

<div class="container">
    <x-header title="Listagem de contas a receber"
              subtitle="Visualize e gerencie os valores que você tem a receber, acompanhando vencimentos e status de pagamento">
        <x-slot:actions>
            <x-button label="Novo recebimento" icon="o-plus" class="btn-success"
                      link="{{ route('autenticado.contas-receber.cadastro') }}"/>
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

    <x-table :headers="$headers" :rows="$this->lancamentos" empty-text="Nenhum recebimento lançado." show-empty-text with-pagination>
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
                <x-menu-item label="Cancelar recebimento" icon="o-trash" />
                <x-menu-item label="Editar recebimento" icon="o-pencil-square" />
                <x-menu-item label="Baixas" icon="o-document-duplicate" wire:click="$dispatch('abrir-modal-listagem-baixas', {lancamento_id: '{{ $lancamento->id }}'})"  :disabled="!$lancamento->contemBaixas()"/>
                <x-menu-item label="Receber" icon="o-currency-dollar" wire:click="$dispatch('abrir-modal-baixa-parcial', {lancamento_id: '{{ $lancamento->id }}'})" :disabled="$lancamento->estaPago()"/>
            </x-dropdown>
        @endscope
    </x-table>

    <livewire:autenticado.lancamentos.baixas.modal-baixa-parcial :metodos="\App\Models\MetodoPagamento::query()->where('user_id', \Illuminate\Support\Facades\Auth::id())->get()"/>
    <livewire:autenticado.lancamentos.baixas.modal-listagem-baixas />
    <livewire:autenticado.lancamentos.baixas.modal-confirmacao-estornar-baixa />
    <livewire:autenticado.lancamentos.baixas.modal-visualizacao-baixa />
</div>
