<?php

use App\Models\SolicitacaoBancoNovo;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use WithPagination, Toast;

    public string $pesquisa = '';
    public int $perPage = 10;
    public array $headers = [['key' => 'codigo', 'label' => 'Código', 'class' => 'w-2'], ['key' => 'nome', 'label' => 'Nome'], ['key' => 'decisao', 'label' => 'Decisão']];

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Admin - Solicitação de novo banco');
    }

    #[Computed]
    public function solicitacoes()
    {
        return SolicitacaoBancoNovo::query()->when($this->pesquisa, fn($q) => $q->where('nome', 'like', "%{$this->pesquisa}%"))->latest()->paginate($this->perPage);
    }

    public function abrirModalVisualizacaoSolicitacao(string $solicitacao_id): void
    {
        $this->dispatch('altera-status-modal-visualizacao-solicitacao-banco-novo', solicitacao_id: $solicitacao_id, status: true);
    }
};

?>

<div>
    <x-header title="Solicitações de Bancos"
        subtitle="Gerencie as solicitações de cadastro de novos bancos enviadas pelos usuários">
        <x-slot:middle class="justify-end!">
            <x-input icon="o-magnifying-glass" placeholder="Pesquise..." wire:model.live='pesquisa' />
        </x-slot:middle>
    </x-header>

    <x-table :rows="$this->solicitacoes" :headers="$headers" :per-page-values="[10, 20, 30]" per-page="perPage"
        empty-text="Não existem solicitações de cadastro" with-pagination show-empty-text>

        @scope('cell_decisao', $solicitacao)
            <p
                class="font-bold {{ match ($solicitacao->decisao) {
                    'aprovado' => 'text-green-500',
                    'recusado' => 'text-red-500',
                    default => 'text-gray-500',
                } }}">
                {{ match ($solicitacao->decisao) {
                    'aprovado' => 'Aprovado',
                    'recusado' => 'Recusado',
                    default => 'Pendente',
                } }}
            </p>
        @endscope

        @scope('actions', $solicitacao)
            <x-button icon="o-eye" tooltip="Visualizar"
                wire:click="abrirModalVisualizacaoSolicitacao('{{ $solicitacao->id }}')" />
        @endscope
    </x-table>

    <livewire:autenticado.admin.solicitacoes-banco.modal-visualizacao-solicitacao-banco-novo />
</div>
