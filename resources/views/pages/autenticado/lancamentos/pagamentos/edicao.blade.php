<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public ?\App\Models\Lancamento $lancamentoAtual = null;
    public array $categorias = [];
    public Collection $contas;
    public \App\Livewire\Forms\Lancamentos\UpsertLancamentoForm $lancamento;

    public function mount(\App\Models\Lancamento $lancamentoAtual): void
    {
        $this->lancamentoAtual = $lancamentoAtual;

        $this->contas = \App\Models\ContaBancaria::query()->where('user_id', \Illuminate\Support\Facades\Auth::id())->get();

        $categoria = app(\App\Models\Categoria::class);

        $this->categorias = $categoria->getCategoriaAgrupadasPorTipo(\App\Enums\TipoCategoria::DESPESA);
        $this->lancamento->fill($this->lancamentoAtual->toArray());
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Edição - Contas a Receber');
    }

    public function editar(): void {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->lancamentoAtual->update($this->lancamento->all());
            });
            $this->success('Atualização de lançamento', 'Lançamento atualizado com sucesso', redirectTo: route('autenticado.lancamentos.recebimentos.listagem'));
        } catch (Exception $e) {
            $this->error('Atualização de lançamento', 'Erro ao atualizar o lançamento, verifique com o administrador');
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar o lançamento', [
                'lancamento' => $this->lancamentoAtual
            ]);
        }
    }
}

?>

<div class="container">
    <x-header title="Edição de contas a pagar"
              subtitle="Edite os valores que você tem a pagar, definindo vencimentos, categorias e formas de pagamento"/>

    <x-autenticado.lancamentos.form-lancamento :contas="$contas" :categorias="$categorias" submitLabel="Editar"
                                               submitType="editar" acao="pago"/>
</div>
