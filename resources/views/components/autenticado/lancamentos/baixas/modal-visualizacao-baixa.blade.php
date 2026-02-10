<?php

use Livewire\Component;

new class extends Component
{
    public bool $showModal = false;
    public ?\App\Models\BaixaParcial $baixa = null;

    #[\Livewire\Attributes\On('abrir-modal-visualizacao-baixa')]
    public function abrirModal(string $baixa_id): void {
        $this->baixa = \App\Models\BaixaParcial::find($baixa_id);
        $this->showModal = true;
    }
};
?>

<x-modal wire:model="showModal" class="backdrop-blur" title="Visualização da baixa" subtitle="Veja os dados da baixa abaixo.">
    @if($baixa)
        <livewire:autenticado.lancamentos.baixas.form-dados-baixa :baixa="$baixa"/>
    @endif

    <x-slot:actions>
        <x-button wire:click="$toggle('showModal')" label="Cancelar" />
    </x-slot:actions>
</x-modal>
