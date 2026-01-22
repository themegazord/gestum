<?php

use Livewire\Component;

new class extends Component {
    //
};

?>

<footer class="border-t border-base-300/50 bg-base-200/20 px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid gap-12 md:grid-cols-4 mb-12">

            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <x-avatar class="w-10! h-10! bg-primary text-primary-content font-bold">
                        G
                    </x-avatar>
                    <span class="text-xl font-bold text-base-content">Gestum</span>
                </div>
                <p class="text-sm text-base-content/70 mb-4">
                    Controle total sobre suas finanças, em um só lugar.
                </p>

                {{-- Social Media --}}
                <div class="flex gap-2">
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <x-icon name="o-heart" class="w-4 h-4" />
                    </a>
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <x-icon name="o-chat-bubble-left" class="w-4 h-4" />
                    </a>
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <x-icon name="o-share" class="w-4 h-4" />
                    </a>
                </div>
            </div>

            {{-- Produto --}}
            <div>
                <h3 class="mb-4 font-semibold text-base-content">Produto</h3>
                <ul class="space-y-2">
                    <li><a href="#funcionalidades" class="link link-hover text-sm text-base-content/70">Funcionalidades</a></li>
                    <li><a href="#diferenciais" class="link link-hover text-sm text-base-content/70">Diferenciais</a></li>
                    <li><a href="#beneficios" class="link link-hover text-sm text-base-content/70">Beneficios</a></li>
                </ul>
            </div>

            {{-- Empresa --}}
            <div>
                <h3 class="mb-4 font-semibold text-base-content">Empresa</h3>
                <ul class="space-y-2">
                    <li><a href="#sobre" class="link link-hover text-sm text-base-content/70">Sobre nós</a></li>
                    <li><a href="#blog" class="link link-hover text-sm text-base-content/70">Blog</a></li>
                    <li><a href="#carreira" class="link link-hover text-sm text-base-content/70">Carreira</a></li>
                    <li><a href="#contato" class="link link-hover text-sm text-base-content/70">Contato</a></li>
                </ul>
            </div>

            {{-- Contato --}}
            <div>
                <h3 class="mb-4 font-semibold text-base-content">Contato</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="mailto:contato.wanjalagus@outlook.com.br" class="flex items-center gap-2 text-sm text-base-content/70 hover:text-primary transition-colors">
                            <x-icon name="o-envelope" class="h-4 w-4 text-primary shrink-0" />
                            contato.wanjalagus@outlook.com.br
                        </a>
                    </li>
                    <li>
                        <a href="tel:+5567981590619" class="flex items-center gap-2 text-sm text-base-content/70 hover:text-primary transition-colors">
                            <x-icon name="o-phone" class="h-4 w-4 text-primary shrink-0" />
                            +55 (67) 9859-0619
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="divider"></div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-base-content/60">
                © {{ date('Y') }} Gestum. Todos os direitos reservados.
            </p>
            <div class="flex flex-wrap gap-4 sm:gap-6">
                <a href="#privacidade" class="link link-hover text-sm text-base-content/60">
                    Política de Privacidade
                </a>
                <a href="#termos" class="link link-hover text-sm text-base-content/60">
                    Termos de Serviço
                </a>
                <a href="#cookies" class="link link-hover text-sm text-base-content/60">
                    Cookies
                </a>
            </div>
        </div>
    </div>
</footer>
