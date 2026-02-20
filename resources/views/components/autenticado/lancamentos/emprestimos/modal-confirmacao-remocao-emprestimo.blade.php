<?php

use Livewire\Component;

new class extends Component
{
    use \Mary\Traits\Toast;

    public bool $showModal = false;
    public ?\App\Models\Emprestimo $emprestimoAtual = null;

    #[\Livewire\Attributes\On('abrir-modal-confirmacao-remocao-emprestimo')]
    public function abrirModal(string $emprestimo_id): void {
        $this->emprestimoAtual = \App\Models\Emprestimo::with([
            'lancamentos.baixasParciais',
            'categoriaPrincipal',
            'categoriaRetorno',
        ])->find($emprestimo_id);
        $this->showModal = true;
    }

    public function confirmarRemocao(): void {
        foreach ($this->emprestimoAtual->lancamentos as $lancamento) {
            if ($lancamento->baixasParciais->isNotEmpty()) {
                foreach ($lancamento->baixasParciais as $baixa) {
                    $lancamento->estornarBaixa($baixa);
                }
            }

            $lancamento->forceDelete();
        }

        $this->emprestimoAtual->delete();

        $this->success('Empréstimo', 'Empréstimo removido com sucesso');
        $this->showModal = false;
        $this->dispatch('fechar-modal-remocao-emprestimo');
    }
};
?>

<x-modal wire:model="showModal" title="Remoção de empréstimo" subtitle="Verifique os dados antes de confirmar a remoção" class="backdrop-blur" box-class="max-w-2xl">
    @if($emprestimoAtual)
        @php
            $totalBaixas = $emprestimoAtual->lancamentos->sum(fn($l) => $l->baixasParciais->count());
            $contemBaixas = $totalBaixas > 0;
        @endphp

        <div class="flex flex-col gap-4">
            <div class="flex flex-col items-center gap-2 text-center">
                <x-icon name="o-exclamation-triangle" class="w-16 h-16 text-error" />
                <p class="text-lg font-semibold">{{ $emprestimoAtual->descricao }}</p>
                <x-badge :value="$emprestimoAtual->tipo->label()" :class="$emprestimoAtual->tipo->color()" />
            </div>

            <div class="grid grid-cols-2 gap-3 bg-base-200 p-4 rounded-lg">
                <div>
                    <p class="text-xs text-base-content/60">Valor principal</p>
                    <p class="font-semibold">R$ {{ number_format($emprestimoAtual->valor_principal, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Valor retorno</p>
                    <p class="font-semibold">R$ {{ number_format($emprestimoAtual->valor_retorno, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Data do empréstimo</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($emprestimoAtual->data_emprestimo)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Vencimento</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($emprestimoAtual->data_vencimento)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Categoria principal</p>
                    <p class="font-semibold">{{ $emprestimoAtual->categoriaPrincipal->nome }}</p>
                </div>
                <div>
                    <p class="text-xs text-base-content/60">Categoria retorno</p>
                    <p class="font-semibold">{{ $emprestimoAtual->categoriaRetorno->nome }}</p>
                </div>
            </div>

            <div class="bg-base-200 p-4 rounded-lg">
                <p class="text-sm font-semibold mb-2">Lançamentos vinculados que serão removidos:</p>
                <ul class="flex flex-col gap-1">
                    @foreach($emprestimoAtual->lancamentos as $lancamento)
                        <li class="flex items-center justify-between text-sm">
                            <span>{{ $lancamento->tipo->label() }} — R$ {{ number_format($lancamento->valor, 2, ',', '.') }}</span>
                            <x-badge :value="$lancamento->status->label()" :class="$lancamento->status->color()" />
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($contemBaixas)
                <x-alert
                    title="{{ $totalBaixas }} baixa(s) registrada(s) serão estornadas"
                    description="Todas as baixas dos lançamentos vinculados serão estornadas antes da remoção. Os saldos das contas bancárias vinculadas serão revertidos."
                    icon="o-exclamation-triangle"
                    class="alert-warning"
                />
            @endif

            <div class="bg-error/10 p-4 rounded-lg text-center">
                <p class="text-error font-semibold">Atenção! Esta ação não poderá ser desfeita.</p>
            </div>
        </div>

        <x-slot:actions>
            <x-button wire:click="$toggle('showModal')" label="Cancelar" />
            <x-button wire:click="confirmarRemocao" label="Remover empréstimo" class="btn-error" icon="o-trash" spinner="confirmarRemocao" />
        </x-slot:actions>
    @endif
</x-modal>
