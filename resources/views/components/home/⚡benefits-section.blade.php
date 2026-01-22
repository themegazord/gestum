<?php

use Livewire\Component;

new class extends Component {
    public array $benefits = [
        [
            'title' => 'Tome Decisões Inteligentes',
            'description' => 'Com dados precisos e análises detalhadas, cada decisão financeira é mais consciente e estratégica.',
            'points' => ['Dados em tempo real', 'Análises preditivas', 'Recomendações personalizadas'],
        ],
        [
            'title' => 'Alcance Suas Metas',
            'description' => 'Defina objetivos e acompanhe seu progresso com as ferramentas certas para atingir seus sonhos financeiros.',
            'points' => ['Metas customizáveis', 'Acompanhamento visual', 'Alertas de progresso'],
        ],
        [
            'title' => 'Tenha Visão Completa',
            'description' => 'Veja todas as suas finanças em um dashboard unificado. Nada fica escondido, tudo é transparente.',
            'points' => ['Dashboard centralizado', 'Relatórios automáticos', 'Histórico completo'],
        ],
    ];
};

?>

<section id="beneficios" class="relative px-4 py-20 sm:px-6 lg:px-8 bg-base-200/20">
    <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
            <h2 class="text-balance text-3xl font-bold text-base-content sm:text-4xl lg:text-5xl">
                Benefícios que você vai <span class="text-primary">desfrutar</span>
            </h2>
        </div>

        <div class="grid gap-12 md:grid-cols-3">
            @foreach ($benefits as $index => $benefit)
                <div x-data="{ show: false }"
                    x-intersect.once="setTimeout(() => show = true, {{ $index * 150 }})"
                    class="flex flex-col gap-6 rounded-2xl border border-base-300/50 bg-base-100 p-8 transition-all duration-700 hover:border-primary/50 hover:shadow-xl"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-base-content">
                            {{ $benefit['title'] }}
                        </h3>
                        <p class="text-base-content/70">{{ $benefit['description'] }}</p>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-base-300/50 pt-6">
                        @foreach ($benefit['points'] as $point)
                            <div class="flex items-center gap-3">
                                <x-icon name="o-check-circle" class="h-5 w-5 flex-shrink-0 text-primary" />
                                <span class="text-sm text-base-content/80">{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
