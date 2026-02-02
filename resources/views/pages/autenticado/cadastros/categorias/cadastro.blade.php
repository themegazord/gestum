<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public ?array $tiposCategorias = null;
    public ?Collection $categorias = null;
    public \App\Livewire\Forms\Cadastros\Categorias\UpsertCategoriaForm $categoria;

    public function mount(): void
    {
        $this->categoria->user_id = \Illuminate\Support\Facades\Auth::id();
        $this->tiposCategorias = \App\Enums\TipoCategoria::options();
        $this->carregaCategorias();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Cadastro - Categorias');
    }

    public function carregaCategorias(): void
    {
        $this->categorias = \App\Models\Categoria::query()->where('categorias.user_id', \Illuminate\Support\Facades\Auth::id())->get();
    }

    public function cadastrar(): void
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            \App\Models\Categoria::query()->create($this->categoria->all());
        });

        $this->success(title: "Cadastro de Categoria", description: "Categoria cadastrada com sucesso");
    }
};

?>

<div class="container">
    <x-header title="Cadastro de categorias"
              subtitle="Crie categorias para classificar e organizar suas transações de contas a pagar e contas a receber"/>
    <x-autenticado.cadastros.categorias.form-categoria :categorias="$categorias" :tiposCategorias="$tiposCategorias" submitLabel="Cadastrar" submitType="cadastrar" />
</div>
