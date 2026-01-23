<?php

use App\Models\Banco;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use WithPagination, Toast;

    public string $pesquisa = '';
    public int $perPage = 10;
    public bool $showModalSolicitacaoCadastroBanco = false;
    public array $headers = [['key' => 'codigo', 'label' => 'Código', 'class' => 'w-2'], ['key' => 'nome', 'label' => 'Nome']];

    #[Computed]
    public function bancos()
    {
        return Banco::query()->when($this->pesquisa, fn($q) => $q->where('nome', 'like', "%{$this->pesquisa}%"))->paginate($this->perPage);
    }

    public function render(): View
    {
        return $this->view()->layout('layouts.authenticated')->title('Bancos');
    }

    public function abreModalSolicitacaoCadastroBancoNovo(): void {
        $this->dispatch('altera-status-modal-solicitacao-cadastro-banco', true);
    }
};

?>

<div>
    <x-header title="Bancos"
        subtitle="Aqui você poderá gerenciar todos os bancos que temos cadastrado dentro do sistema, caso queira você também poderá cadastrar um caso não encontre o seu">
        <x-slot:middle class="justify-end!">
            <x-input icon="o-magnifying-glass" placeholder="Pesquise..." wire:model.live='pesquisa' />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Solicitar banco novo" icon="o-plus"
                wire:click="abreModalSolicitacaoCadastroBancoNovo" />
        </x-slot:actions>
    </x-header>

    <x-table :rows="$this->bancos" :headers="$headers" :per-page-values="[10, 20, 30]" per-page="perPage" with-pagination />

    <livewire:autenticado.bancos.modal-solicitacao-cadastro-banco-novo />
</div>
