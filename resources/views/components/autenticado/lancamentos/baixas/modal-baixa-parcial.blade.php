<?php

use Livewire\Component;

new class extends Component {
    use \Mary\Traits\Toast;

    public bool $showModal = false;
    public \App\Models\Lancamento $lancamentoSelecionado;
    public float $valorRecebido = 0.0;
    public ?string $dataRecebimento = null;
    public ?string $mteodoPagamento = null;
    public ?string $contaBancariaId = null;
    public ?string $contaBancariaNome = null;
    public ?string $observacoesRecebimento = null;

    #[\Livewire\Attributes\On('abrir-modal-baixa-parcial')]
    public function abrirModal(string $lancamento_id): void
    {
        $this->lancamentoSelecionado = \App\Models\Lancamento::query()->find($lancamento_id);
        $this->contaBancariaId = $this->lancamentoSelecionado->contaBancaria->id;
        $this->contaBancariaNome = $this->lancamentoSelecionado->contaBancaria->nome;
        $this->showModal = true;
    }

    public function baixar(): void
    {
        try {
            $this->lancamentoSelecionado->baixarParcial($this->valorRecebido,  $this->dataRecebimento,  $this->contaBancariaId, $this->mteodoPagamento,  $this->observacoesRecebimento);
        } catch (Exception $e) {
            $this->error('Baixa de lançamento', 'Erro ao baixar lançamento. =>' . $e->getMessage());
        }

        $this->dispatch('fechar-modal-baixa-parcial');
        $this->showModal = false;
    }
};
?>

<x-modal wire:model="showModal" title="Baixa Parcial"
         subtitle="Insira os dados abaixo para baixar parcialmente ou totalmente esse lançamento">
    <div class="flex flex-col gap-4">
        <x-input
            label="Valor em aberto"
            prefix="R$"
            :value="number_format($lancamentoSelecionado?->valor - $lancamentoSelecionado?->valor_pago, 2, ',', '.')"
            readonly
        />

        <x-input
            wire:model="valorRecebido"
            label="Valor a receber"
            prefix="R$"
            placeholder="0,00"
            locale="pt-BR"
            money
        />

        <x-input
            wire:model="dataRecebimento"
            label="Data do recebimento"
            type="date"
        />

        <x-input
            wire:model="metodoPagamento"
            label="Método de pagamento"
        />

        <x-input
            wire:model="contaBancariaNome"
            label="Conta bancária"
            readonly
        />

        <x-textarea
            wire:model="observacoesRecebimento"
            label="Observações"
            placeholder="Observações sobre o recebimento..."
            rows="2"
        />
    </div>

    <x-slot:actions>
        <x-button wire:click="$toggle('showModal')" label="Cancelar" />
        <x-button wire:click="baixar" label="Confirmar" class="btn-success" />
    </x-slot:actions>
</x-modal>
