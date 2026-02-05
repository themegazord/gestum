<?php

use Livewire\Component;

new class extends Component {
    #[\Livewire\Attributes\Validate(rule: [
        'metodo_pagamento.id' => 'nullable',
        'metodo_pagamento.nome' => 'required',
        'metodo_pagamento.cor' => 'required'
    ], message: [
        'metodo_pagamento.nome.required' => 'O nome é obrigatório.',
        'metodo_pagamento.cor.required' => 'A cor é obrigatório.',
    ])]
    public array $metodo_pagamento = [
        'id' => null,
        'nome' => null,
        'cor' => null
    ];
    public bool $showModal = false;

    #[\Livewire\Attributes\On('abrir-modal-upsert-metodo-pagamento')]
    public function abrirModal(?string $metodo_pagamento_id = null): void
    {
        if (!is_null($metodo_pagamento_id)) {
            $metodo = \App\Models\MetodoPagamento::find($metodo_pagamento_id);
            $this->metodo_pagamento = [
                'id' => $metodo->id,
                'nome' => $metodo->nome,
                'cor' => $metodo->cor
            ];
        } else {
            $this->metodo_pagamento = [
                'id' => null,
                'nome' => null,
                'cor' => null
            ];
        }
        $this->showModal = true;
    }

    public function upsert(): void
    {
        $this->validate();

        DB::transaction(function () {
            if (!isset($this->metodo_pagamento['id'])) {
                \App\Models\MetodoPagamento::query()
                    ->create([
                        'user_id' => \Illuminate\Support\Facades\Auth::id(),
                        'nome' => $this->metodo_pagamento['nome'],
                        'cor' => $this->metodo_pagamento['cor']
                    ]);
            }

            \App\Models\MetodoPagamento::query()
                ->where('id', $this->metodo_pagamento['id'])
                ->update([
                    'nome' => $this->metodo_pagamento['nome'],
                    'cor' => $this->metodo_pagamento['cor']
                ]);
        });

        $this->dispatch('fechar-modal-upsert-metodo-pagamento', ['tipo' => !is_null($this->metodo_pagamento['id']) ? 'edicao' : 'cadastro']);
        $this->showModal = false;
    }

};
?>

<x-modal wire:model="showModal" title="Cadastro de Metodo de Pagamento" subtitle="Insira os dados seguintes para cadastrar um método de pagamento" class="backdrop-blur" box-class="max-w-lg">
    <x-form wire:submit="upsert">
        <x-input wire:model="metodo_pagamento.nome" label="Nome" />
        <x-colorpicker wire:model="metodo_pagamento.cor" label="Cor" />

        <x-slot:actions>
            <x-button label="Cancelar" class="btn-error" wire:click="$toggle('showModal')" />
            <x-button label="Cadastrar" class="btn-success" type="submit" spinner="upsert"/>
        </x-slot:actions>
    </x-form>
</x-modal>
