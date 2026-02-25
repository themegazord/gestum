<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination;

    #[\Livewire\Attributes\Url]
    public ?string $descricao = null;
    #[\Livewire\Attributes\Url]
    public ?string $categoria = null;
    #[\Livewire\Attributes\Url]
    public ?string $conta_bancaria = null;
    #[\Livewire\Attributes\Url]
    public ?string $data_vencimento = null;

    public array $headers = [
        ['key' => 'descricao', 'label' => 'Descrição'],
        ['key' => 'valor', 'label' => 'Valor em aberto', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'categoria_id', 'label' => 'Categoria'],
        ['key' => 'conta_bancaria_id', 'label' => 'Conta bancária'],
        ['key' => 'data_vencimento', 'label' => 'Data de Vencimento', 'format' => ['date', 'd/m/Y']],
    ];

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Recorrência - Receitas');
    }

    #[\Livewire\Attributes\Computed]
    public function recorrencias(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Recorrencia::query()
            ->where('tipo', 'receita')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->when($this->descricao, fn($q) => $q->where('descricao', 'like', "%$this->descricao%"))
            ->when($this->categoria, fn($q) => $q->where('categoria_id', "%$this->categoria%"))
            ->when($this->conta_bancaria, fn($q) => $q->where('conta_bancaria_id', "%$this->conta_bancaria%"))
            ->when($this->data_vencimento, fn($q) => $q->where('recorrencias.data_vencimento', "%$this->data_vencimento%"))
            ->paginate();
    }

    #[\Livewire\Attributes\Computed]
    public function categorias(): Collection
    {
        return \App\Models\Categoria::query()
            ->where('tipo', 'receita')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function contaBancaria(): Collection {
        return \App\Models\ContaBancaria::query()
            ->where('user_id', Auth::id())
            ->get();
    }

    public function limparFiltro(): void
    {
        $this->reset(['descricao', 'categoria', 'conta_bancaria', 'data_vencimento']);
    }
}

?>

<div class="container">
    <x-header title="Listagem de receita em recorrências"
              subtitle="Gerencie suas receitas que estão em recorrência, crie, edite e apague as recorrências.">
        <x-slot:actions>
            <x-button class="btn-success" label="Adicionar recorrência" icon="o-plus"/>
        </x-slot:actions>
    </x-header>

    <x-autenticado.recorrencias.receita.table-recorrencia/>
</div>
