<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public array $categorias = [];
    public Collection $contas;
    public \App\Livewire\Forms\Lancamentos\UpsertLancamentoForm $lancamento;

    public function mount(): void
    {
        $this->contas = \App\Models\ContaBancaria::query()->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        $categoria = app(\App\Models\Categoria::class);

        $this->categorias = $categoria->getCategoriaAgrupadasPorTipo(\App\Enums\TipoCategoria::RECEITA);

        $this->lancamento->tipo = 'receita';
        $this->lancamento->status = 'pendente';
        $this->lancamento->user_id = \Illuminate\Support\Facades\Auth::id();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Cadastro - Contas a Receber');
    }

    public function cadastrar(): void
    {
        $this->validate();
        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                \App\Models\Lancamento::query()->create($this->lancamento->all());
            });

            $this->success('Cadastro de contas a receber', 'Lançamento cadastrado com sucesso', redirectTo: route('autenticado.lancamentos.recebimentos.listagem'));
        } catch (Exception $e) {
            $this->error('Cadastro de contas a receber', 'Erro ao cadastrar o lançamento, verifique com o administrador');
            \Illuminate\Support\Facades\Log::error('Erro ao cadastrar o lançamento', [
                'error' => $e->getMessage(),
                'data' => $this->lancamento->all()
            ]);
        }
    }

}

?>

<div class="container">
    <x-header title="Cadastro de contas a receber"
              subtitle="Registre os valores que você tem a receber, definindo vencimentos, categorias e formas de pagamento"/>

    <x-autenticado.lancamentos.form-lancamento :contas="$contas" :categorias="$categorias" submitLabel="Cadastrar" submitType="cadastrar" acao="recebido"/>
</div>
