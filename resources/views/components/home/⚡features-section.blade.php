<?php

use Livewire\Component;

new class extends Component {
    public array $features = [
        [
            'icon' => 'wallet',
            'title' => 'Controle de Entrada e Saída',
            'description' => 'Registre todas as suas transações financeiras com facilidade e clareza.',
        ],
        [
            'icon' => 'chart-bar',
            'title' => 'Planejamento Futuro',
            'description' => 'Projete suas finanças e planeje com inteligência para o futuro.',
        ],
        [
            'icon' => 'presentation-chart-bar',
            'title' => 'Análises Detalhadas',
            'description' => 'Gráficos e relatórios completos para entender seus gastos e receitas.',
        ],
        [
            'icon' => 'bolt',
            'title' => 'Gestão de Adiantamentos',
            'description' => 'Controle inteligente de adiantamentos salariais e outras operações.',
        ],
        [
            'icon' => 'banknotes',
            'title' => 'Controle de Empréstimos',
            'description' => 'Gerencie empréstimos concedidos e recebidos em um só lugar.',
        ],
        [
            'icon' => 'shield-check',
            'title' => 'Segurança de Dados',
            'description' => 'Seus dados financeiros protegidos com os mais altos padrões de segurança.',
        ],
    ];
};

?>

<section id="features" class="relative px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
            <h2 class="text-balance text-3xl font-bold text-foreground sm:text-4xl lg:text-5xl">
                Funcionalidades <span class="text-primary">poderosas</span>
            </h2>
            <p class="mt-4 text-lg text-foreground/70">
                Tudo que você precisa para gerenciar suas finanças em um único aplicativo.
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $index => $feature)
                <div x-data="{ show: false }"
                    x-intersect.once="setTimeout(() => show = true, {{ $index * 50 }})"
                    class="group rounded-xl border border-border/50 bg-card p-8 transition-all duration-500 hover:border-primary/50 hover:shadow-xl hover:bg-card/80"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">

                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-primary/10 transition-colors group-hover:bg-primary/20">
                        <x-icon name="o-{{$feature['icon']}}" class="h-7 w-7 text-primary transition-colors group-hover:text-primary/80" />
                    </div>

                    <h3 class="mb-3 text-xl font-bold text-foreground transition-colors group-hover:text-primary">
                        {{ $feature['title'] }}
                    </h3>

                    <p class="text-foreground/70 transition-colors group-hover:text-foreground/80">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
