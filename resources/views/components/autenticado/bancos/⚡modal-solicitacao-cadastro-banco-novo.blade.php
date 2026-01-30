<?php

use App\Livewire\Forms\ContasBancarias\Bancos\SolicitacaoCadastroBancoNovo;
use App\Models\SolicitacaoBancoNovo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;
    public bool $mostraModal = false;
    public SolicitacaoCadastroBancoNovo $form;

    #[On('altera-status-modal-solicitacao-cadastro-banco')]
    public function alteraStatusModalSolicitacaoCadastroBanco(bool $status): void {
        $this->mostraModal = $status;
    }

    public function enviarSolicitacao(): void {
        $solicitacaoValidada = $this->form->validate();

        SolicitacaoBancoNovo::query()->create([
            'user_id' => Auth::id(),
            ...$solicitacaoValidada
        ]);

        $this->success('Solicitação de cadastro de banco novo.', 'Solicitação efetuada com sucesso, quando for aceita você receberá um email informando. Obrigado por melhorar nosso sistema 💚');

        $this->mostraModal = false;
    }
};
?>

<x-modal wire:model="mostraModal" title="Solicitar cadastro de banco"
    subtitle="Informe os dados do banco que deseja adicionar" class="backdrop-blur">
    <x-input label="Nome do banco" wire:model='form.nome' placeholder="Ex: Banco XYZ S.A." icon="o-building-library" />
    <x-input label="Código do banco" wire:model='form.codigo' placeholder="Ex: 999" icon="o-hashtag" />
    <x-textarea label="Observação" wire:model='form.observacao' placeholder="Informações adicionais..." rows="3" />

    <x-slot:actions>
        <x-button label="Cancelar" @click="$wire.mostraModal = false" />
        <x-button label="Enviar solicitação" class="btn-primary" icon="o-paper-airplane" wire:click='enviarSolicitacao' wire:loading.attr='disabled' spinner="enviarSolicitacao"/>
    </x-slot:actions>
</x-modal>
