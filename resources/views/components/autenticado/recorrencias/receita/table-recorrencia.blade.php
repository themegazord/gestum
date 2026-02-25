@props([])

<div class="flex flex-col gap-4">
    <div>
        <div class="flex flex-col md:grid md:grid-cols-5 md:items-end gap-4">
            <div class="col-span-2">
                <x-input
                    wire:model.live.debounce.300ms="descricao"
                    label="Descrição"
                    placeholder="Buscar por descrição..."
                    icon="o-magnifying-glass"
                />
            </div>
            <x-select
                wire.model.live.debounce.300ms="categoria"
                label="Categoria"
                :options="$this->categorias"
                option-label="nome"
                option-value="id"
                placeholder="Buscar por categoria..."
            />
            <x-select
                wire:model.live.debounce.300ms="conta_bancaria"
                label="Conta bancária"
                :options="$this->contaBancaria"
                option-label="nome"
                option-value="id"
                placeholder="Buscar por conta bancária..."
            />
            <x-datepicker
                wire:model.live.debounce.300ms="data_vencimento"
                label="Data de vencimento"
                :config="['altFormat' => 'd/m/Y']"
                icon="o-calendar"
                placeholder="Buscar pela data de vencimento..."
            />
        </div>
        <x-button class="btn-link" label="Limpar filtro" wire:click="limparFiltro" icon="o-x-mark" />
    </div>

    <x-table :headers="$this->headers" :rows="$this->recorrencias()" empty-text="Nenhuma recorrência cadastrada" with-pagination show-empty-text>

    </x-table>
</div>
