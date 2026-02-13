<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast, \Livewire\WithPagination;

    public string $acao = 'recebimento';
    public string $verbo = 'receber';

    #[Url]
    public ?string $descricao = null;
    #[Url]
    public ?string $data_vencimento_inicio = null;
    #[Url]
    public ?string $data_vencimento_fim = null;
    #[Url]
    public ?string $status = null;
    #[Url]
    public ?string $categoria_id = null;
    #[Url]
    public ?string $conta_bancaria_id = null;

    public array $headers = [
        ['key' => 'descricao', 'label' => 'Descrição'],
        ['key' => 'tipo', 'label' => 'Tipo'],
        ['key' => 'valor', 'label' => 'Valor em aberto', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'valor_pago', 'label' => 'Valor pago', 'format' => ['currency', '2,.', 'R$ ']],
        ['key' => 'categoria_id', 'label' => 'Categoria'],
        ['key' => 'conta_bancaria_id', 'label' => 'Conta bancária'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'data_vencimento', 'label' => 'Data de Vencimento', 'format' => ['date', 'd/m/Y']],
    ];

    #[Computed]
    #[\Livewire\Attributes\On('recarrega-lancamentos')]
    public function categorias(): Collection
    {
        return \App\Models\Categoria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('tipo', \App\Enums\TipoCategoria::RECEITA)
            ->get()
            ->filter(fn ($categoria) => !$categoria->ehCategoriaPai());
    }

    #[Computed]
    public function contasBancarias(): Collection
    {
        return \App\Models\ContaBancaria::query()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->get();
    }

    #[\Livewire\Attributes\On('fechar-modal-baixa-parcial')]
    public function fechaModalBaixaParcial(): void {
        $this->lancamentos();

        $this->success('Baixa de lançamento', 'Baixa efetivada com sucesso');
    }

    #[Computed]
    public function lancamentos(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return \App\Models\Lancamento::withTrashed()
            ->where('lancamentos.user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('lancamentos.tipo', \App\Enums\TipoCategoria::RECEITA)
            ->when($this->descricao, fn ($query) => $query->where('descricao', 'like', "%{$this->descricao}%"))
            ->when($this->data_vencimento_inicio, fn ($query) => $query->whereDate('data_vencimento', '>=', $this->data_vencimento_inicio))
            ->when($this->data_vencimento_fim, fn ($query) => $query->whereDate('data_vencimento', '<=', $this->data_vencimento_fim))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->categoria_id, fn ($query) => $query->where('categoria_id', $this->categoria_id))
            ->when($this->conta_bancaria_id, fn ($query) => $query->where('conta_bancaria_id', $this->conta_bancaria_id))
            ->latest()
            ->with('categoria', 'contaBancaria')
            ->paginate();
    }

    public function limparFiltros(): void
    {
        $this->reset(['descricao', 'data_vencimento_inicio', 'data_vencimento_fim', 'status', 'categoria_id', 'conta_bancaria_id']);
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Listagem - Contas a Receber');
    }
}

?>

<div class="container">

    <x-autenticado.lancamentos.table-lancamentos verbo="receber" :headers="$headers" />

    <livewire:autenticado.lancamentos.baixas.modal-baixa-parcial :acao="$acao" :metodos="\App\Models\MetodoPagamento::query()->where('user_id', \Illuminate\Support\Facades\Auth::id())->get()"/>
    <livewire:autenticado.lancamentos.baixas.modal-listagem-baixas />
    <livewire:autenticado.lancamentos.baixas.modal-confirmacao-estornar-baixa />
    <livewire:autenticado.lancamentos.baixas.modal-visualizacao-baixa />
    <livewire:autenticado.lancamentos.modal-confirmacao-remocao-lancamento />
</div>
