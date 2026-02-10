<?php

use Livewire\Component;

new class extends Component
{
    use \Mary\Traits\Toast;

    public bool $showModal = false;
    public ?\App\Models\BaixaParcial $baixa = null;

    #[\Livewire\Attributes\On('abrir-modal-confirmacao-estornar-baixa')]
    public function abrirModal(string $baixa_id): void {
        $this->baixa = \App\Models\BaixaParcial::find($baixa_id);
        $this->showModal = true;
    }

    public function confirmarEstorno(): void {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                $this->baixa->lancamento->estornarBaixa($this->baixa);
            });

            $this->dispatch('recarrega-lancamentos');
            $this->success('Estorno de baixa', "Baixa estornada com sucesso");
            $this->showModal = false;
        } catch (Exception $e) {
            $this->error('Estorno de baixa', "Erro ao estornar a baixa atual, verifique com o administrador.");
            \Illuminate\Support\Facades\Log::error("Erro ao estornar a baixa atual $e->getMessage()", [
                'baixa' => $this->baixa->toArray(),
                'lancamento' => $this->baixa->lancamento->toArray()
            ]);
        }
    }
};
?>

<x-modal wire:model="showModal" class="backdrop-blur" title="Confirmar estorno de baixa" subtitle="Tem certeza que deseja estornar esta baixa? Esta ação não poderá ser desfeita.">
    @if($baixa)
        <livewire:autenticado.lancamentos.baixas.form-dados-baixa :baixa="$baixa"/>
    @endif

    <x-slot:actions>
        <x-button wire:click="$toggle('showModal')" label="Cancelar" />
        <x-button wire:click="confirmarEstorno" label="Confirmar estorno" class="btn-error" />
    </x-slot:actions>
</x-modal>
