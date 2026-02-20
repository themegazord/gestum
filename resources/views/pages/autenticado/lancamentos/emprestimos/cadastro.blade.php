<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public \App\Livewire\Forms\Lancamentos\Emprestimos\UpsertEmprestimoForm $emprestimo;

    public function mount(): void
    {
        $this->emprestimo->status = 'pendente';
    }

    public function render()
    {
        return $this->view()->title('Cadastro - Empréstimos')->layout('layouts.authenticated');
    }

    #[\Livewire\Attributes\Computed]
    public function categorias(): Collection
    {
        return \App\Models\Categoria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get()
            ->filter(fn($categoria) => !$categoria->ehCategoriaPai());
    }

    #[\Livewire\Attributes\Computed]
    public function contasBancarias(): Collection
    {
        return \App\Models\ContaBancaria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get();
    }

    public function cadastrar(): void
    {
        $this->validate();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                $emprestimoCriado = \App\Models\Emprestimo::query()->create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'conta_bancaria_id' => $this->emprestimo->conta_bancaria_id,
                    'categoria_principal_id' => $this->emprestimo->categoria_principal_id,
                    'categoria_retorno_id' => $this->emprestimo->categoria_retorno_id,
                    'tipo' => $this->emprestimo->tipo,
                    'descricao' => $this->emprestimo->descricao,
                    'valor_principal' => $this->emprestimo->valor_principal,
                    'valor_retorno' => $this->emprestimo->valor_retorno,
                    'data_emprestimo' => $this->emprestimo->data_emprestimo,
                    'data_vencimento' => $this->emprestimo->data_vencimento,
                    'status' => $this->emprestimo->status,
                    'observacao' => $this->emprestimo->observacao
                ]);

                $lancamentoPrincipal = \App\Models\Lancamento::query()->create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'categoria_id' => $this->emprestimo->categoria_principal_id,
                    'conta_bancaria_id' => $this->emprestimo->conta_bancaria_id,
                    'emprestimo_id' => $emprestimoCriado->getAttribute('id'),
                    'tipo' => $this->emprestimo->tipo === 'tomado' ? 'receita' : 'despesa',
                    'descricao' => $this->emprestimo->descricao,
                    'valor' => $this->emprestimo->valor_principal,
                    'status' => 'pendente',
                    'data_vencimento' => $this->emprestimo->tipo === 'tomado' ? $this->emprestimo->data_emprestimo : $this->emprestimo->data_vencimento,
                    'observacoes' => $this->emprestimo->observacao
                ]);

                $lancamentoPrincipal->baixar();

                $lancamentoRetorno = \App\Models\Lancamento::query()->create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'categoria_id' => $this->emprestimo->categoria_retorno_id,
                    'conta_bancaria_id' => $this->emprestimo->conta_bancaria_id,
                    'emprestimo_id' => $emprestimoCriado->getAttribute('id'),
                    'tipo' => $this->emprestimo->tipo === 'tomado' ? 'despesa' : 'receita',
                    'descricao' => $this->emprestimo->descricao,
                    'valor' => $this->emprestimo->valor_retorno,
                    'status' => 'pendente',
                    'data_vencimento' => $this->emprestimo->data_vencimento,
                    'observacoes' => $this->emprestimo->observacao
                ]);

            });
            $this->success('Emprestimo', 'Cadastro do empréstimo finalizado com sucesso', redirectTo: route('autenticado.lancamentos.emprestimos.listagem'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao cadastrar empréstimo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $this->error('Erro', 'Não foi possível cadastrar o empréstimo. Verifique os logs.');
        }
    }
}

?>

<div class="container">
    <x-header title="Cadastro de Empréstimos" subtitle="Registre e acompanhe os empréstimos realizados"/>

    <x-autenticado.lancamentos.emprestimos.form-emprestimo :contas="$this->contasBancarias()"
                                                           :categorias="$this->categorias()" submit-label="Cadastrar" submit-type="cadastrar" />
</div>
