<?php

use App\Models\Banco;
use App\Models\SolicitacaoBancoNovo;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $showModalVisualizacao = false;
    public bool $showModalMotivoDecisao = false;
    #[Validate(rule: [
        'required'
    ], message: [
        'required' => 'Motivo obrigatório.'
    ])]
    public string $motivo = '';
    public ?SolicitacaoBancoNovo $solicitacaoSelecionada = null;

    #[On('altera-status-modal-visualizacao-solicitacao-banco-novo')]
    public function recebeDadosModal(string $solicitacao_id, bool $status): void
    {
        $this->solicitacaoSelecionada = SolicitacaoBancoNovo::query()->where('id', $solicitacao_id)->firstOrFail();
        $this->showModalVisualizacao = $status;
    }

    public function aprovar(): void
    {
        if (Banco::query()->where('codigo', $this->solicitacaoSelecionada->getAttribute('codigo'))->first()) {
            $nome = $this->solicitacaoSelecionada->getAttribute('nome');
            $this->warning('Atenção!!!', "Já contêm um banco com esse código cadastrado no sistema. [ $nome ]");
            return;
        }

        $this->solicitacaoSelecionada->update([
            'decisao' => 'aprovado',
        ]);

        Banco::query()->create([
            'nome' => $this->solicitacaoSelecionada->getAttribute('nome'),
            'codigo' => $this->solicitacaoSelecionada->getAttribute('codigo')
        ]);

        $this->showModalVisualizacao = false;
        $this->success('Solicitação de novo banco', 'Solicitação aceita com sucesso');
        // TODO: criar notificacao depois por email ou por telegram se possivel
    }

    public function recusar(): void {
        $this->validate();

        $this->solicitacaoSelecionada->update([
            'decisao' => 'recusado',
            'motivo_decisao' => $this->motivo
        ]);

        $this->showModalMotivoDecisao = false;
        $this->showModalVisualizacao = false;
        $this->success('Solicitação de novo banco', 'Solicitação recusada com sucesso');
        // TODO: criar notificacao depois por email ou por telegram se possivel
    }

};
?>

<div>
    <x-modal wire:model="showModalVisualizacao" title="Detalhes da Solicitação" class="backdrop-blur" box-class="max-w-2xl">
        @if ($this->solicitacaoSelecionada)
            {{-- Dados do Solicitante --}}
            <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg mb-4">
                <x-avatar :value="$this->solicitacaoSelecionada->solicitante->name" class="w-14!" />
                <div>
                    <p class="font-semibold text-lg">{{ $this->solicitacaoSelecionada->solicitante->name }}</p>
                    <p class="text-sm text-base-content/60">{{ $this->solicitacaoSelecionada->solicitante->email }}</p>
                    <p class="text-xs text-base-content/40">
                        Solicitado em {{ $this->solicitacaoSelecionada->created_at->format('d/m/Y \à\s H:i') }}
                    </p>
                </div>
            </div>

            {{-- Dados do Banco Solicitado --}}
            <div class="space-y-3">
                <x-input label="Nome do Banco" value="{{ $this->solicitacaoSelecionada->nome }}" readonly
                    icon="o-building-library" />
                <x-input label="Código" value="{{ $this->solicitacaoSelecionada->codigo }}" readonly icon="o-hashtag" />

                @if ($this->solicitacaoSelecionada->observacao)
                    <x-textarea label="Observação" value="{{ $this->solicitacaoSelecionada->observacao }}" readonly
                        rows="3" />
                @endif
            </div>

            {{-- Status atual --}}
            @if ($this->solicitacaoSelecionada->decisao)
                <div
                    class="mt-4 p-3 rounded-lg {{ $this->solicitacaoSelecionada->decisao === 'aprovado' ? 'bg-success/10' : 'bg-error/10' }}">
                    <p
                        class="font-semibold {{ $this->solicitacaoSelecionada->decisao === 'aprovado' ? 'text-success' : 'text-error' }}">
                        {{ $this->solicitacaoSelecionada->decisao === 'aprovado' ? 'Aprovado' : 'Recusado' }}
                    </p>
                    @if ($this->solicitacaoSelecionada->motivo_decisao)
                        <p class="text-sm mt-1">{{ $this->solicitacaoSelecionada->motivo_decisao }}</p>
                    @endif
                </div>
            @endif

            <x-slot:actions>
                <x-button label="Fechar" wire:click="$toggle('showModalVisualizacao')" />
                @unless ($this->solicitacaoSelecionada->decisao)
                    <x-button label="Recusar" class="btn-error" icon="o-x-mark" wire:click="$toggle('showModalMotivoDecisao')" />
                    <x-button label="Aprovar" class="btn-success" icon="o-check" wire:click="aprovar" />
                @endunless
            </x-slot:actions>
        @endif
    </x-modal>

    <x-modal wire:model="showModalMotivoDecisao" title="Motivo da decisão" class="backdrop-blur" box-class="max-w-xl">
        @if ($solicitacaoSelecionada)
            <x-textarea wire:model='motivo' label="Motivo" rows="3"/>
            <x-slot:actions>
                <x-button label="Fechar" wire:click="$toggle('showModalMotivoDecisao')" />
                <x-button label="Recusar" class="btn-error" icon="o-x-mark" wire:click="recusar"/>
            </x-slot:actions>
        @endif
    </x-modal>
</div>
