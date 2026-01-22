<?php

use Livewire\Component;

new class extends Component {
    public array $problems = [
        [
            'icon' => 'trending-down',
            'title' => 'Descontrole Financeiro',
            'problem' => 'Sem visibilidade clara sobre entradas e saídas',
            'solution' => 'Categorização automática e controle em tempo real',
        ],
        [
            'icon' => 'alert-circle',
            'title' => 'Falta de Planejamento',
            'problem' => 'Dificuldade em planejar o futuro financeiro',
            'solution' => 'Projeções inteligentes e metas personalizadas',
        ],
        [
            'icon' => 'eye',
            'title' => 'Desconhecimento',
            'problem' => 'Não saber para onde vai o dinheiro',
            'solution' => 'Análises detalhadas e relatórios completos',
        ],
    ];
};

?>

<section class="relative px-4 py-20 sm:px-6 lg:px-8 bg-muted/20">
    <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
            <h2 class="text-balance text-3xl font-bold text-foreground sm:text-4xl lg:text-5xl">
                Problemas que <span class="text-primary">resolvemos</span>
            </h2>
            <p class="mt-4 text-lg text-foreground/70">
                Entendemos as dificuldades que você enfrenta. O Gestum foi criado para resolver cada uma delas.
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-3">
            @foreach ($problems as $index => $problem)
                <div x-data="{ show: false }"
                    x-intersect.once="setTimeout(() => show = true, {{ $index * 100 }})"
                    class="rounded-xl border border-border/50 bg-card p-8 transition-all duration-700 hover:border-primary/50 hover:shadow-lg"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'">

                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-primary/10">
                        @switch($problem['icon'])
                            @case('trending-down')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-7 w-7 text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                                </svg>
                            @break

                            @case('alert-circle')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-7 w-7 text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                            @break

                            @case('eye')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-7 w-7 text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            @break
                        @endswitch
                    </div>

                    <h3 class="mb-3 text-xl font-bold text-foreground">{{ $problem['title'] }}</h3>

                    <p class="mb-4 text-foreground/70">
                        <span class="font-semibold text-red-600">Problema:</span> {{ $problem['problem'] }}
                    </p>

                    <p class="text-foreground/70">
                        <span class="font-semibold text-primary">Solução:</span> {{ $problem['solution'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
