<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast, \Livewire\WithPagination;

    public ?string $pesquisa = null;
    public int $perPage = 10;
    public array $sortBy = ['column' => 'nome', 'direction' => 'asc'];
    public array $headers = [
        ['key' => 'nome', 'label' => 'Nome da categoria'],
        ['key' => 'tipo', 'label' => 'Tipo da categoria'],
        ['key' => 'categoria_pai_id', 'label' => 'Categoria pai', 'sortable' => false],
        ['key' => 'deleted_at', 'label' => 'Status', 'sortable' => false]
    ];

    public function mount(): void
    {
        $this->categorias();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Categorias');
    }

    #[\Livewire\Attributes\On('categoria-excluida')]
    #[\Livewire\Attributes\Computed]
    public function categorias(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Categoria::withTrashed()
            ->where('categorias.user_id', \Illuminate\Support\Facades\Auth::id())
            ->when($this->pesquisa, fn($q) => $q->where('categorias.nome', 'like', "%{$this->pesquisa}%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function alterarStatus(string $categoria_id): void {
        $categoria = \App\Models\Categoria::withTrashed()->find($categoria_id);

        if (!$categoria) {
            $this->categorias();
            $this->error('Erro de atualização', 'Categoria não identificada');
        }

        $categoria->trashed() ? $categoria->restore() : $categoria->delete();

        $this->success('Atualização de categoria', 'Categoria ' . ($categoria->trashed() ? 'inativada' : 'ativada') . ' com sucesso');
        $this->categorias();
    }
}

?>

<div class="container">
    <x-header title="Minhas categorias" subtitle="Visualize, edite ou exclua as categorias de suas transações">
        <x-slot:middle>
            <x-input placeholder="Pesquise..." wire:model.live="pesquisa" icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" icon="o-plus" class="btn-success" link="{{ route('autenticado.cadastros.categorias.cadastro') }}"/>
        </x-slot:actions>
    </x-header>
    <x-table :rows="$this->categorias" :headers="$headers" :sort-by="$sortBy" per-page="perPage" with-pagination :per-page-values="[10, 20, 30]">
        @scope('cell_tipo', $categoria)
        <x-badge :value="$categoria->tipo->label()" :class="$categoria->tipo->color()"/>
        @endscope
        @scope('cell_categoria_pai_id', $categoria)
        <x-badge class="{{ $categoria->ehCategoriaPai() ? 'badge-success' : 'badge-error' }}"
                 value="{{ $categoria->ehCategoriaPai() ? 'Sim' : 'Não' }}"/>
        @endscope
        @scope('cell_deleted_at', $categoria)
        <x-badge class="{{ $categoria->trashed() ? 'badge-error' : 'badge-success' }}"
                 value="{{ $categoria->trashed() ? 'Inativo' : 'Ativo' }}"/>
        @endscope
        @scope('actions', $categoria)
        <x-dropdown>
            <x-menu-item label="{{ $categoria->trashed() ? 'Ativar categoria' : 'Inativar categoria' }}"
                         icon="{{ $categoria->trashed() ? 'o-eye' : 'o-eye-slash' }}" wire:click="alterarStatus('{{ $categoria->id }}')"/>
                <x-menu-item label="Editar categoria" icon="o-pencil-square" link="{{ route('autenticado.cadastros.categorias.edicao', ['categoriaAtual' => $categoria->id]) }}"/>
                <x-menu-item label="Remover categoria" icon="o-trash" wire:click="$dispatch('abre-modal-confirmacao-exclusao-categoria', { categoria_id: '{{ $categoria->id }}' })"/>
            </x-dropdown>
        @endscope
    </x-table>

    <livewire:autenticado.cadastros.categorias.modal-confirmacao-exclusao />
</div>
