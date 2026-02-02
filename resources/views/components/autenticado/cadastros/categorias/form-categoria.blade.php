@props([
    'tiposCategorias',
    'categorias',
    'submitLabel' => 'Salvar',
    'submitType' => 'salvar'
])

<x-form wire:submit="{{ $submitType }}">
    <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
        <x-input wire:model="categoria.nome" label="Nome da categoria"/>
        <x-select wire:model="categoria.tipo" label="Tipo de categoria" :options="$tiposCategorias"
                  placeholder="Escolha um tipo de categoria..."/>
    </div>
    <div class="flex flex-col gap-4 md:grid md:grid-cols-2">
        <x-colorpicker wire:model="categoria.cor" label="Cor do badge" icon="o-swatch"/>
        <x-select wire:model="categoria.categoria_pai_id" label="Categoria pai" :options="$categorias" option-label="nome" option-value="id" placeholder="Selecione uma categoria pai"/>
    </div>

    <x-slot:actions>
        <x-button label="Cancelar" icon="o-arrow-left" class="btn-error" link="{{ route('autenticado.cadastros.categorias.listagem') }}"/>
        <x-button label="{{ $submitLabel }}" icon="o-check" class="btn-success" type="submit" spinner="{{ $submitType }}"/>
    </x-slot:actions>

</x-form>
