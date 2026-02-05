<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Livewire\WithPagination, \Mary\Traits\Toast;

    public array $headers = [
        ['key' => 'nome', 'label' => 'Nome'],
        ['key' => 'deleted_at', 'label' => 'Ativo?']
    ];

    #[\Livewire\Attributes\Url]
    public ?string $pesquisa = null;

    #[\Livewire\Attributes\Computed]
    public function metodos(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\MetodoPagamento::withTrashed()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->when($this->pesquisa, fn($q) => $q->where('nome', 'like', "%{$this->pesquisa}%"))
            ->latest()
            ->paginate();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Gerencimento - Métodos de Pagamento');
    }

    #[\Livewire\Attributes\On('fechar-modal-upsert-metodo-pagamento')]
    public function fecharModalUpsert(array $dados): void
    {
        $acao = $dados['tipo'] === 'cadastro' ? 'cadastrado' : 'editado';
        $this->success('Gerenciamento de método de pagamento', "Método de pagamento {$acao} com sucesso");
    }

    public function alterarStatusMetodoPagamento(\App\Models\MetodoPagamento $metodo): void {
        $metodo->trashed() ? $metodo->restore() : $metodo->delete();

        $this->metodos();
        $this->success('Gerenciamento de método de pagamento',  "Método de pagamento atualizado com sucesso");
    }
}

?>

<div class="container">
    <x-header title="Métodos de Pagamento"
              subtitle="Gerencie as formas de pagamentos cadastradas, cadastre, edite, altere o status e remova definitivamente as formas de pagamentos.">
        <x-slot:middle>
            <x-input icon="o-magnifying-glass" placeholder="Nome do método..." wire:model="pesquisa"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" icon="o-plus" class="btn-success"
                      wire:click="$dispatch('abrir-modal-upsert-metodo-pagamento')"/>
        </x-slot:actions>
    </x-header>

    <x-table :rows="$this->metodos" :headers="$headers" empty-text="Nenhum método de pagamento cadastrado."
             show-empty-text with-pagination>
        @scope('cell_deleted_at', $metodo)
        {{ $metodo->trashed() ? 'Não' : 'Sim' }}
        @endscope

        @scope('actions', $metodo)
        <x-dropdown>
            <x-menu-item label="Edita método pagamento" icon="o-pencil" wire:click="$dispatch('abrir-modal-upsert-metodo-pagamento', {metodo_pagamento_id: '{{ $metodo->id }}' })" />
            <x-menu-item label="{{ $metodo->trashed() ? 'Ativar' : 'Inativar'}} método pagamento" icon="o-{{ $metodo->trashed() ? 'eye' : 'eye-slash' }}" wire:click="alterarStatusMetodoPagamento('{{ $metodo->id }}')"/>
        </x-dropdown>
        @endscope
    </x-table>

    <livewire:autenticado.cadastros.metodos-pagamentos.modal-cadastro-metodo-pagamento/>
</div>
