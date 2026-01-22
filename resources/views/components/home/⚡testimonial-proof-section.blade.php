<?php

use Livewire\Component;

new class extends Component {
    public array $testimonials = [
        [
            'name' => 'Ana Silva',
            'role' => 'Consultora de Marketing',
            'content' => 'Gestum mudou minha relação com dinheiro. Finalmente consigo ver para onde meu dinheiro está indo e planejar o futuro com segurança.',
            'rating' => 5,
            'avatar' => 'AS',
        ],
        [
            'name' => 'Carlos Martins',
            'role' => 'Empreendedor',
            'content' => 'A interface é tão intuitiva que minha avó conseguiu usar! As análises automáticas me economizaram muitas horas de contabilidade manual.',
            'rating' => 5,
            'avatar' => 'CM',
        ],
        [
            'name' => 'Marina Costa',
            'role' => 'Profissional Autônoma',
            'content' => 'As projeções do Gestum me ajudaram a economizar o suficiente para meu próprio negócio. Não consigo viver sem!',
            'rating' => 5,
            'avatar' => 'MC',
        ],
    ];

    public array $stats = [['number' => '2.5K+', 'label' => 'Usuários Ativos'], ['number' => '98%', 'label' => 'Satisfação'], ['number' => '4.9/5', 'label' => 'Avaliação'], ['number' => '500K+', 'label' => 'Transações Processadas']];
};

?>

<section class="relative overflow-hidden px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-16 text-center">
            <h2 class="text-balance text-3xl font-bold text-base-content sm:text-4xl lg:text-5xl">
                Nossos usuários <span class="text-primary">amam Gestum</span>
            </h2>
            <p class="mt-4 text-lg text-base-content/70">
                Veja o que pessoas reais estão dizendo sobre nossa plataforma.
            </p>
        </div>

        {{-- Testimonials Grid --}}
        <div class="mb-20 grid gap-8 md:grid-cols-3">
            @foreach ($testimonials as $index => $testimonial)
                <div x-data="{ show: false }" x-intersect.once="setTimeout(() => show = true, {{ $index * 100 }})"
                    class="rounded-xl border border-base-300/50 bg-base-100 p-8 transition-all duration-700 hover:border-primary/50 hover:shadow-xl"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">

                    {{-- Rating --}}
                    <div class="mb-4">
                        <x-rating disabled :value="$testimonial['rating']" class="rating-sm bg-yellow-500!" />
                    </div>

                    {{-- Content --}}
                    <p class="mb-6 text-base-content/80 italic">"{{ $testimonial['content'] }}"</p>

                    {{-- Author --}}
                    <div class="flex items-center gap-3 border-t border-base-300/50 pt-6">
                        <x-avatar class="w-12! h-12! bg-primary/10 text-primary font-bold">
                            {{ $testimonial['avatar'] }}
                        </x-avatar>
                        <div>
                            <p class="font-semibold text-base-content">{{ $testimonial['name'] }}</p>
                            <p class="text-sm text-base-content/70">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Stats Banner --}}
        <div class="rounded-2xl border border-base-300/50 bg-linear-to-r from-primary/5 to-secondary/5 p-8 sm:p-12">
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-4">
                @foreach ($stats as $index => $stat)
                    <div x-data="{ show: false }"
                        x-intersect.once="setTimeout(() => show = true, {{ $index * 100 + 400 }})"
                        class="text-center transition-all duration-700"
                        :class="show ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">

                        <p class="text-3xl font-bold text-primary sm:text-4xl">
                            {{ $stat['number'] }}
                        </p>
                        <p class="mt-2 text-sm text-base-content/70">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
