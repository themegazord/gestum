<?php

use App\Models\ContaBancaria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Collection $contas = null;
    public array $headers = [['key' => 'nome', 'label' => 'Nome da conta'], ['key' => 'tipo', 'label' => 'Tipo da conta'], ['key' => 'saldo_atual', 'label' => 'Saldo atual da conta'], ['key' => 'deleted_at', 'label' => 'Está conta está ativa?']];
    public function mount(): void
    {
        $this->carregarContas();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Contas');
    }

    public function alteraStatusConta(?ContaBancaria $conta): void
    {
        $conta->trashed() ? $conta->restore() : $conta->delete();

        $this->carregarContas();

        $this->success('Atualização de conta bancária', 'Status da conta bancária atualizada com sucesso');
    }

    private function carregarContas(): void
    {
        $this->contas = ContaBancaria::withTrashed()->where('user_id', Auth::id())->get();
    }
};

?>

<div class="container">
    <x-header title="Listagem de contas bancárias"
        subtitle="Pesquisa, visualize, inative, cadastre e edite suas contas bancárias">
        <x-slot:middle>
            <x-input placeholder="Nome, número da conta..." icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" icon="o-plus" class="btn-success"
                link="{{ route('autenticado.contas-bancarias.contas.cadastro') }}" />
        </x-slot:actions>
    </x-header>

    <x-table :headers="$headers" :rows="$contas">
        @scope('cell_tipo', $conta)
        <x-badge :value="$conta->tipo->label()" :class="$conta->tipo->color()" />
        @endscope

        @scope('cell_saldo_atual', $conta)
        R$ {{ number_format($conta->saldo_atual, 2, ',', '.') }}
        @endscope

        @scope('cell_deleted_at', $conta)
        <x-badge :value="$conta->trashed() ? 'Não' : 'Sim'" :class="$conta->trashed() ? 'badge-error' : 'badge-success'" />
        @endscope

        @scope('actions', $conta)
        <x-dropdown>
            <x-menu-item title="{{ $conta->trashed() ? 'Ativar conta' : 'Inativar conta' }}"
                icon="{{ $conta->trashed() ? 'o-eye' : 'o-eye-slash' }}" wire:click.stop="alteraStatusConta('{{ $conta->id }}')" spinner="alteraStatusConta" />
            <x-menu-item title="Editar conta" icon="o-pencil-square" link="{{ route('autenticado.contas-bancarias.contas.edicao', ['contaBancaria' => $conta->id]) }}" />
            <x-menu-item title="Remover conta" icon="o-trash" />
        </x-dropdown>
        @endscope
    </x-table>
</div>
