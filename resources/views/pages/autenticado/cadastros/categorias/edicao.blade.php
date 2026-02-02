<?php

use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public ?array $tiposCategorias = null;
    public ?Collection $categorias = null;
    public ?\App\Models\Categoria $categoriaAtual;
    public \App\Livewire\Forms\Cadastros\Categorias\UpsertCategoriaForm $categoria;

    public function mount(\App\Models\Categoria $categoriaAtual): void
    {
        $this->categoria->fill($categoriaAtual->toArray());
        $this->tiposCategorias = \App\Enums\TipoCategoria::options();
        $this->carregaCategorias();
    }

    public function render()
    {
        return $this->view()->layout('layouts.authenticated')->title('Edição - Categorias');
    }

    public function carregaCategorias(): void
    {
        $this->categorias = \App\Models\Categoria::query()
            ->where('categorias.user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereNot('categorias.id', $this->categoriaAtual->getAttribute('id'))
            ->get();
    }

    public function editar(): void
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            $this->categoriaAtual->update($this->categoria->all());
        });

        $this->success(title: "Edição de Categoria", description: "Categoria editada com sucesso", redirectTo: route('autenticado.cadastros.categorias.listagem'));
    }
};

?>

<div class="container">
    <x-header title="Edição de categorias"
              subtitle="Edite categorias para classificar e organizar suas transações de contas a pagar e contas a receber"/>
    <x-autenticado.cadastros.categorias.form-categoria :categorias="$categorias" :tiposCategorias="$tiposCategorias" submitLabel="Editar" submitType="editar" />
</div>
