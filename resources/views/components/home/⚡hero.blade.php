<?php

use Livewire\Component;

new class extends Component {
    public bool $showDemo = false;
};
?>

<section
    class="relative overflow-hidden bg-linear-to-b from-base-100 via-base-100 to-base-200/30 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid gap-12 items-center lg:grid-cols-2 lg:gap-8">

            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" class="flex flex-col gap-6 transition-all duration-1000"
                :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">

                <div
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-primary/30 bg-primary/5 px-4 py-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
                    </span>
                    <span class="text-sm font-medium text-primary">Novo: Projeções IA chegando em breve</span>
                </div>

                <h1 class="text-balance text-4xl font-bold leading-tight text-base-content sm:text-5xl lg:text-6xl">
                    Tome <span class="text-primary">controle total</span> de suas finanças
                </h1>

                <p class="text-lg text-base-content/70 sm:text-xl">
                    Gestum é o aplicativo completo para gestão financeira pessoal. Controle entradas, saídas, planeje o
                    futuro e tome decisões inteligentes com análises detalhadas.
                </p>

                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <x-button label="Tente você mesmo" icon="o-arrow-right" class="btn-primary btn-lg" />

                    <x-button label="Ver Demo" class="btn-outline btn-lg" wire:click="$toggle('showDemo')" />
                </div>

                <div class="flex flex-col gap-4 pt-4 sm:flex-row sm:gap-8">
                    <div class="flex items-center gap-2">
                        <x-avatar class="w-8! h-8! bg-primary/20 border-2 border-primary/30" />
                        <x-avatar class="w-8! h-8! bg-secondary/20 border-2 border-secondary/30" />
                        <x-avatar class="w-8! h-8! bg-accent/20 border-2 border-accent/30" />
                        <span class="text-sm text-base-content/70">+2.5K usuários ativos</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-rating readonly value="5" class="rating-sm" />
                        <span class="text-sm text-base-content/70">4.9 de 5 avaliações</span>
                    </div>
                </div>
            </div>

            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)"
                class="relative h-96 transition-all duration-1000 delay-200 sm:h-125 lg:h-150"
                :class="show ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-10'">

                <div class="absolute inset-0 rounded-2xl bg-linear-to-br from-primary/10 to-secondary/10 blur-3xl">
                </div>

                <div class="rounded-box bg-base-100 p-8 shadow-2xl h-full">

                    {{-- Header --}}
                    <div class="mb-6 flex items-center justify-between">
                        {{-- Logo/Title Skeleton --}}
                        <div class="h-12 w-32 animate-pulse rounded-lg bg-secondary"></div>

                        {{-- Button Skeleton --}}
                        <div class="h-8 w-20 animate-pulse rounded-field bg-warning/30"></div>
                    </div>

                    {{-- Divider Line --}}
                    <div class="mb-6 h-px w-24 animate-pulse bg-base-300"></div>

                    {{-- Main Content Area --}}
                    <div class="mb-6 h-48 animate-pulse rounded-box bg-base-200"></div>

                    {{-- Two Column Layout --}}
                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Left Card --}}
                        <div class="rounded-box bg-success/10 p-6">
                            <div class="h-6 w-20 animate-pulse rounded-field bg-success/50"></div>
                        </div>

                        {{-- Right Card --}}
                        <div class="rounded-box bg-warning/10 p-6">
                            <div class="h-6 w-20 animate-pulse rounded-field bg-warning/50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal wire:model="showDemo" title="Demo do Gestum" class="backdrop-blur">
        <div class="space-y-4">
            <p class="text-base-content/70">
                Explore todas as funcionalidades do Gestum e veja como é fácil gerenciar suas finanças!
            </p>

            <div class="rounded-lg bg-base-200 p-6">
                <x-icon name="o-play-circle" class="w-16 h-16 text-primary mx-auto mb-4" />
                <p class="text-center text-sm text-base-content/60">Vídeo demo será carregado aqui</p>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Fechar" @click="$wire.showDemo = false" />
            <x-button label="Tente você mesmo" class="btn-primary" />
        </x-slot:actions>
    </x-modal>
</section>
