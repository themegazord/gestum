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
    public ContaBancaria $contaBancaria;

    public function mount(ContaBancaria $contaBancaria): void
    {
        $this->tiposConta = TipoContaBancaria::options();
        $this->conta->fill($contaBancaria->toArray());
        $this->search();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Edição - Contas');
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

    public function edicao(): void
    {
        $dadosValidados = $this->validate();

        $this->contaBancaria->update($dadosValidados);

        $this->success('Edição de conta bancária.', 'Conta bancária editada com sucesso', redirectTo: route('autenticado.contas-bancarias.contas.listagem'));
    }
};

?>

<div class="container">
    <x-header title="Edição de contas bancárias"
        subtitle="Defina os dados da conta bancária que vai receber as transações de contas a pagar e as transações de contas a receber" />
    <x-autenticado.contas-bancarias.form-conta :bancos="$bancos" :tiposConta="$tiposConta" submitLabel="Editar"
        submitAction="edicao" />
</div>
