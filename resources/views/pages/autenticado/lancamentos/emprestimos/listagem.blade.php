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
    public ?string $tipo = null;
    #[Url]
    public ?string $status = null;
    #[Url]
    public ?string $categoria_id = null;
    #[Url]
    public ?string $conta_bancaria_id = null;

    public ?string $data_vencimento = null;

    public array $headers = [
        ['key' => 'descricao', 'label' => 'Descrição'],
        ['key' => 'tipo', 'label' => 'Tipo'],
        ['key' => 'valor_principal', 'label' => 'Valor principal', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'valor_retorno', 'label' => 'Valor de retorno', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'conta_bancaria_id', 'label' => 'Conta bancária'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'data_emprestimo', 'label' => 'Data do empréstimo', 'format' => ['date', 'd/m/Y']],
        ['key' => 'data_vencimento', 'label' => 'Vencimento', 'format' => ['date', 'd/m/Y']],
    ];

    #[Computed]
    public function categorias(): Collection
    {
        return \App\Models\Categoria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
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

    #[Computed]
    public function emprestimos(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Emprestimo::query()
            ->where('emprestimos.user_id', \Illuminate\Support\Facades\Auth::id())
            ->when($this->descricao, fn ($q) => $q->where('descricao', 'like', "%{$this->descricao}%"))
            ->when($this->data_vencimento_inicio, fn ($q) => $q->whereDate('data_vencimento', '>=', $this->data_vencimento_inicio))
            ->when($this->data_vencimento_fim, fn ($q) => $q->whereDate('data_vencimento', '<=', $this->data_vencimento_fim))
            ->when($this->tipo, fn ($q) => $q->where('tipo', $this->tipo))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->categoria_id, fn ($q) => $q->where('categoria_id', $this->categoria_id))
            ->when($this->conta_bancaria_id, fn ($q) => $q->where('conta_bancaria_id', $this->conta_bancaria_id))
            ->latest()
            ->with('categoria', 'contaBancaria')
            ->paginate();
    }

    public function updatedDataVencimento(?string $value): void
    {
        if (!$value) {
            $this->data_vencimento_inicio = null;
            $this->data_vencimento_fim = null;
            return;
        }

        $datas = explode(' até ', $value);

        $this->data_vencimento_inicio = $datas[0] ?? null;
        $this->data_vencimento_fim = $datas[1] ?? null;


    }

    public function limparFiltros(): void
    {
        $this->reset(['descricao', 'data_vencimento', 'data_vencimento_inicio', 'data_vencimento_fim', 'tipo', 'status', 'categoria_id', 'conta_bancaria_id']);
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Empréstimos');
    }
}

?>

<div class="container">

    <x-header title="Listagem de empréstimos"
              subtitle="Visualize e gerencie os empréstimos tomados e concedidos">
        <x-slot:actions>
            <x-button label="Novo empréstimo" icon="o-plus" class="btn-success" link="{{ route('autenticado.lancamentos.emprestimos.cadastro') }}"/>
        </x-slot:actions>
    </x-header>

    @php
        $configDatapicker = ['mode' => 'range', 'dateFormat' => 'Y-m-d', 'altFormat' => 'd/m/Y', 'altInput' => true, 'conjunction' => ' , '];
    @endphp

    <div class="flex flex-col md:grid md:grid-cols-6 gap-4">
        <x-input
            wire:model.live.debounce.300ms="descricao"
            label="Descrição"
            placeholder="Buscar por descrição..."
            icon="o-magnifying-glass"
        />

        <x-datepicker
            wire:model.live="data_vencimento"
            label="Período de vencimento"
            icon="o-calendar"
            :config="$configDatapicker"
        />

        <x-select
            wire:model.live="tipo"
            label="Tipo"
            placeholder="Todos"
            :options="\App\Enums\TipoEmprestimo::options()"
        />

        <x-select
            wire:model.live="status"
            label="Status"
            placeholder="Todos"
            :options="\App\Enums\StatusEmprestimo::options()"
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

    <x-table :headers="$headers" :rows="$this->emprestimos" empty-text="Nenhum empréstimo cadastrado." show-empty-text with-pagination>
        @scope('cell_tipo', $emprestimo)
        <x-badge :value="$emprestimo->tipo->label()" :class="$emprestimo->tipo->color()"/>
        @endscope

        @scope('cell_conta_bancaria_id', $emprestimo)
        {{ $emprestimo->contaBancaria->nome }}
        @endscope

        @scope('cell_status', $emprestimo)
        <x-badge :value="$emprestimo->status->label()" :class="$emprestimo->status->color()"/>
        @endscope
    </x-table>

</div>
