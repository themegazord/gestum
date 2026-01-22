<?php

use Livewire\Component;

new class extends Component {
    public array $differentials = [
        [
            'icon' => 'o-device-phone-mobile',
            'title' => 'Interface Intuitiva',
            'description' => 'Design moderno e minimalista que qualquer um consegue usar no primeiro contato.',
        ],
        [
            'icon' => 'o-bolt',
            'title' => 'Análises Automáticas',
            'description' => 'Receba insights automáticos sobre seus padrões de gastos e comportamento financeiro.',
        ],
        [
            'icon' => 'o-light-bulb',
            'title' => 'Projeções Inteligentes',
            'description' => 'IA que aprende com seus dados e oferece projeções realistas do futuro financeiro.',
        ],
        [
            'icon' => 'o-lock-closed',
            'title' => 'Segurança Garantida',
            'description' => 'Criptografia de ponta a ponta e conformidade com os mais altos padrões de privacidade.',
        ],
    ];
};

?>

<section id="diferenciais" class="relative overflow-hidden px-4 py-20 sm:px-6 lg:px-8 bg-linear-to-b from-base-200/20 to-base-100">
    <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
            <h2 class="text-balance text-3xl font-bold text-base-content sm:text-4xl lg:text-5xl">
                Por que escolher <span class="text-primary">Gestum</span>?
            </h2>
            <p class="mt-4 text-lg text-base-content/70">
                Nossos diferenciais únicos fazem toda a diferença.
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2">
            @foreach ($differentials as $index => $differential)
                <div x-data="{ show: false, isEven: {{ $index % 2 === 0 ? 'true' : 'false' }} }"
                    x-intersect.once="setTimeout(() => show = true, {{ $index * 100 }})"
                    class="flex gap-6 rounded-xl border border-base-300/50 bg-base-100/50 backdrop-blur-sm p-8 transition-all duration-700 hover:border-primary/50 hover:shadow-lg"
                    :class="show ? 'opacity-100 translate-x-0' : (isEven ? 'opacity-0 -translate-x-8' : 'opacity-0 translate-x-8')">

                    {{-- Icon --}}
                    <div class="shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <x-icon :name="$differential['icon']" class="h-6 w-6 text-primary" />
                        </div>
                    </div>

                    {{-- Content --}}
                    <div>
                        <h3 class="mb-2 text-xl font-bold text-base-content">
                            {{ $differential['title'] }}
                        </h3>
                        <p class="text-base-content/70">
                            {{ $differential['description'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Banner --}}
        <div class="mt-16 rounded-2xl border border-base-300/50 bg-linear-to-r from-primary/5 to-secondary/5 p-8 text-center backdrop-blur-sm">
            <p class="text-lg text-base-content/80">
                <span class="font-bold text-primary">+2.5K usuários</span> já confiam no Gestum para gerenciar suas finanças. E você? 💰
            </p>
        </div>
    </div>
</section>
