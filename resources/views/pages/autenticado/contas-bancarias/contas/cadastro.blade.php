<?php

use App\Enums\TipoContaBancaria;
use App\Livewire\Forms\ContasBancarias\Contas\CadastroContaBancariaForm;
use App\Models\Banco;
use App\Models\ContaBancaria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Collection $bancos = null;
    public array $tiposConta = [];
    public CadastroContaBancariaForm $conta;

    public function mount(): void
    {
        $this->tiposConta = TipoContaBancaria::options();
        $this->search();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Cadastro - Contas');
    }

    public function search(string $value = ''): void
    {
        $bancoSelecionado = Banco::query()->where('id', $this->conta->banco_id)->get();

        $this->bancos = Banco::query()
            ->where('nome', 'like', "%$value%")
            ->orWhere('codigo', 'like', "%$value%")
            ->orderBy('codigo')
            ->get()
            ->merge($bancoSelecionado);
    }

    public function cadastrar(): void {
        $dadosValidados = $this->validate();

        if (!$dadosValidados['saldo_atual']) $dadosValidados['saldo_atual'] = $dadosValidados['saldo_inicial'];

        ContaBancaria::query()->create([
            'user_id' => Auth::id(),
            ...$dadosValidados
        ]);

        // $this->success('Cadastro de conta bancária.', 'Conta bancária cadastrada com sucesso', redirectTo: route('autenticado.contas-bancarias.contas.listagem'));
        $this->success('Cadastro de conta bancária.', 'Conta bancária cadastrada com sucesso');
    }
};

?>

<div class="container">
    <x-header title="Cadastro de contas bancárias"
        subtitle="Defina os dados da conta bancária que vai receber as transações de contas a pagar e as transações de contas a receber" />
    <x-form wire:submit="cadastrar">
        <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
            <x-input label="Nome da conta bancária" wire:model="conta.nome" required/>
            <x-choices label="Bancos" wire:model="conta.banco_id" :options="$bancos" searchable single clearable required>
                @scope('item', $banco)
                    ({{ $banco->codigo }}) - {{ $banco->nome }}
                @endscope

                @scope('selection', $banco)
                    ({{ $banco->codigo }}) - {{ $banco->nome }}
                @endscope
            </x-choices>
        </div>
        <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
            <x-input label="Número da conta" wire:model="conta.numero_conta"/>
            <x-select label="Tipo da conta" wire:model="conta.tipo" :options="$tiposConta" placeholder="Selecione um tipo de conta..." required/>
        </div>
        <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
            <x-input label="Saldo inicial" wire:model="conta.saldo_inicial" prefix="R$" locale="pt-BR" money required/>
            <x-input label="Saldo atual" wire:model="conta.saldo_atual" prefix="R$" locale="pt-BR" money/>
        </div>
        <x-slot:actions>
            <div class="flex flex-col md:flex-row gap-4">
                <x-button label="Cancelar" class="btn-error" icon="o-arrow-left"/>
                <x-button label="Cadastrar" class="btn-success" icon="o-plus" type="submit" spinner="cadastrar"/>
            </div>
        </x-slot:actions>
    </x-form>
</div>
