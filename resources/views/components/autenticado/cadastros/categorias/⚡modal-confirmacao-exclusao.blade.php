<?php

use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $showModal = false;
    public ?\App\Models\Categoria $categoriaSelecionada = null;

    #[On('abre-modal-confirmacao-exclusao-categoria')]
    public function abreModal(string $categoria_id): void
    {
        $this->categoriaSelecionada = \App\Models\Categoria::withTrashed()->findOrFail($categoria_id);
        $this->showModal = true;
    }

    public function excluirDefinitivamente(): void
    {
        $this->categoriaSelecionada->forceDelete();

        $this->showModal = false;
        $this->success('Exclusão de categoria', 'Categoria excluída permanentemente');
        $this->dispatch('categoria-excluida');
    }
};
?>

<div>
    <x-modal wire:model="showModal" title="Confirmar exclusão permanente" class="backdrop-blur" box-class="max-w-lg">
        @if ($categoriaSelecionada)
            <div class="flex flex-col items-center gap-4 text-center">
                <x-icon name="o-exclamation-triangle" class="w-16 h-16 text-error" />

                <div>
                    <p class="text-lg font-semibold">{{ $categoriaSelecionada->nome }}</p>
                    <p class="text-sm text-base-content/60">Tipo {{ $categoriaSelecionada->tipo->label() }}</p>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancelar" wire:click="$toggle('showModal')" />
                <x-button label="Excluir permanentemente" class="btn-error" icon="o-trash"
                          wire:click="excluirDefinitivamente" spinner="excluirDefinitivamente" />
            </x-slot:actions>
        @endif
    </x-modal>
</div>
