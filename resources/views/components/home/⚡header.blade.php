<?php

use Livewire\Component;

new class extends Component {
    public bool $estaAberto = false;
};
?>

<header
    class="sticky top-0 z-50 w-full border-b border-border/50 bg-background/95 backdrop-blur supports-backdrop-filter:bg-background/60">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-primary-foreground font-bold">
                G
            </div>
            <span class="text-xl font-bold text-foreground">Gestum</span>
        </div>

        <div class="hidden gap-8 md:flex">
            <a href="#funcionalidades" class="text-sm font-medium text-foreground hover:text-primary transition-colors">
                Funcionalidades
            </a>
            <a href="#diferenciais" class="text-sm font-medium text-foreground hover:text-primary transition-colors">
                Diferenciais
            </a>
            <a href="#beneficios" class="text-sm font-medium text-foreground hover:text-primary transition-colors">
                Benefícios
            </a>
        </div>

        <div class="hidden gap-3 md:flex">
            <x-button class="btn-sm btn-outline" label="Entrar" link="/login"/>
            <x-button class="btn-primary hover:btn-primary/90 btn-sm" label="Começar Grátis" />
        </div>

        <x-button class="btn-ghost md:hidden" wire:click="$toggle('estaAberto')"
            icon="{{ $estaAberto ? 'o-x-mark' : 'o-bars-3' }}" />
    </nav>

    @if ($estaAberto)
        <div class="border-t border-border/50 bg-background px-4 py-4 md:hidden">
            <div class="flex flex-col gap-4">
                <a href="#funcionalidades" class="text-sm font-medium text-foreground hover:text-primary">
                    Funcionalidades
                </a>
                <a href="#diferenciais" class="text-sm font-medium text-foreground hover:text-primary">
                    Diferenciais
                </a>
                <a href="#beneficios" class="text-sm font-medium text-foreground hover:text-primary">
                    Benefícios
                </a>
                <div class="flex flex-col gap-2 border-t border-border/50 pt-4">
                    <x-button class="w-full btn-outline btn-sm" label="Entrar" link="/login"/>
                    <x-button size="sm" class="btn-sm w-full btn-primary hover:btn-primary/90" label="Começar Grátis"/>
                </div>
            </div>
        </div>
    @endif
</header>
