<?php

use App\Models\ContaBancaria;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $showModal = false;
    public ?ContaBancaria $contaSelecionada = null;

    #[On('abre-modal-confirmacao-exclusao-conta')]
    public function abreModal(string $conta_id): void
    {
        $this->contaSelecionada = ContaBancaria::withTrashed()->findOrFail($conta_id);
        $this->showModal = true;
    }

    public function excluirDefinitivamente(): void
    {
        $this->contaSelecionada->forceDelete();

        $this->showModal = false;
        $this->success('Exclusão de conta bancária', 'Conta bancária excluída permanentemente');
        $this->dispatch('conta-excluida');
    }
};
?>

<div>
    <x-modal wire:model="showModal" title="Confirmar exclusão permanente" class="backdrop-blur" box-class="max-w-lg">
        @if ($contaSelecionada)
            <div class="flex flex-col items-center gap-4 text-center">
                <x-icon name="o-exclamation-triangle" class="w-16 h-16 text-error" />

                <div>
                    <p class="text-lg font-semibold">{{ $contaSelecionada->nome }}</p>
                    <p class="text-sm text-base-content/60">{{ $contaSelecionada->numero_conta }}</p>
                </div>

                <div class="bg-error/10 p-4 rounded-lg w-full">
                    <p class="text-error font-semibold">Atenção! Esta ação é irreversível.</p>
                    <p class="text-sm mt-2">
                        Ao excluir esta conta bancária permanentemente, todas as movimentações
                        financeiras atreladas a ela também serão excluídas.
                    </p>
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
