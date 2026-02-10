<?php

use Livewire\Component;

new class extends Component
{
    use \Mary\Traits\Toast;

    public bool $showModal = false;
    public ?\App\Models\Lancamento $lancamentoAtual = null;

    #[\Livewire\Attributes\On('abrir-modal-confirmacao-remocao-lancamento')]
    public function abrirModal(string $lancamento_id): void {
        $this->lancamentoAtual = \App\Models\Lancamento::with('baixasParciais')->find($lancamento_id);
        $this->showModal = true;
    }

    public function confirmarRemocao(): void {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                if ($this->lancamentoAtual->contemBaixas()) {
                    foreach ($this->lancamentoAtual->baixasParciais as $baixa) {
                        $this->lancamentoAtual->estornarBaixa($baixa);
                    }
                    $this->lancamentoAtual->refresh();
                }

                $this->lancamentoAtual->forceDelete();
            });

            $this->dispatch('recarrega-lancamentos');
            $this->success('Lançamento removido', 'Lançamento removido com sucesso.');
            $this->showModal = false;
            $this->lancamentoAtual = null;
        } catch (Exception $e) {
            $this->error('Erro ao remover', 'Não foi possível remover o lançamento, verifique com o administrador.');
            \Illuminate\Support\Facades\Log::error("Erro ao remover lançamento: {$e->getMessage()}", [
                'lancamento' => $this->lancamentoAtual?->toArray(),
            ]);
        }
    }
};
?>

<x-modal wire:model="showModal" title="Remoção de lançamento" subtitle="Verifique os dados para decidir se irá ou não remover o lançamento" class="backdrop-blur" box-class="max-w-2xl">
    @if($lancamentoAtual)
        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-center gap-2 text-center">
                <x-icon name="o-exclamation-triangle" class="w-16 h-16 text-error" />
                <p class="text-lg font-semibold">{{ $lancamentoAtual->descricao }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-base-200 p-4 rounded-lg">
                <div>
                    <p class="text-xs text-base-content/60">Valor</p>
                    <p class="font-semibold">R$ {{ number_format($lancamentoAtual->valor, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Valor pago</p>
                    <p class="font-semibold">R$ {{ number_format($lancamentoAtual->valor_pago, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Vencimento</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($lancamentoAtual->data_vencimento)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Status</p>
                    <p class="font-semibold">{{ ucfirst($lancamentoAtual->status->value) }}</p>
                </div>
            </div>

            @if($lancamentoAtual->contemBaixas())
                <x-alert title="Este lançamento possui {{ $lancamentoAtual->baixasParciais->count() }} baixa(s) registrada(s)" description="Todas as baixas serão automaticamente estornadas antes da remoção do lançamento. Os saldos das contas bancárias vinculadas serão revertidos." icon="o-exclamation-triangle" class="alert-warning" />
            @endif

            <div class="bg-error/10 p-4 rounded-lg text-center">
                <p class="text-error font-semibold">Atenção! Esta ação não poderá ser desfeita.</p>
            </div>
        </div>

        <x-slot:actions>
            <x-button wire:click="$toggle('showModal')" label="Cancelar" />
            <x-button wire:click="confirmarRemocao" label="Remover lançamento" class="btn-error" icon="o-trash" spinner="confirmarRemocao" />
        </x-slot:actions>
    @endif
</x-modal>
