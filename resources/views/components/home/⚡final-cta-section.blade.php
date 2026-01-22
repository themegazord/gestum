<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Validate('required', message: 'Campo obrigatório')]
    #[Validate('email', message: 'Email inválido')]
    public string $email = '';

    public function submit(): void
    {
        $this->validate();

        // Salvar lead
        // Lead::create(['email' => $this->email]);

        $this->success(
            'Cadastro realizado com sucesso!',
            description: 'Enviamos um email de confirmação para ' . $this->email,
            position: 'toast-top toast-center',
            icon: 'o-check-circle',
            css: 'alert-success'
        );

        $this->email = '';
    }
};

?>

<section class="relative overflow-hidden px-4 py-20 sm:px-6 lg:px-8 bg-linear-to-b from-base-100 via-primary/5 to-base-100">

    {{-- Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -right-40 -top-40 h-80 w-80 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute -left-40 -bottom-40 h-80 w-80 rounded-full bg-secondary/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-4xl">
        <x-card class="border-base-300/50 bg-base-100/80 backdrop-blur-sm p-8 sm:p-12 text-center" shadow>

            <h2 class="text-balance text-3xl font-bold text-base-content sm:text-4xl lg:text-5xl">
                Comece a <span class="text-primary">controlar suas finanças</span> hoje
            </h2>

            <p class="mt-6 text-lg text-base-content/70">
                Junte-se a milhares de usuários que já estão transformando sua relação com dinheiro.
                Sem cartão de crédito necessário, sem compromisso.
            </p>

            <x-form wire:submit="submit" class="mt-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:gap-3 justify-center">
                    <x-input
                        wire:model="email"
                        type="email"
                        placeholder="seu@email.com"
                        icon="o-envelope"
                        class="flex-1 sm:max-w-sm input-lg"
                        inline />

                    <x-button
                        type="submit"
                        label="Começar Grátis"
                        icon-right="o-arrow-right"
                        class="btn-primary btn-lg"
                        spinner="submit" />
                </div>
            </x-form>

            <p class="mt-6 text-sm text-base-content/60">
                ✨ Acesso instantâneo • 100% Gratuito • Sem cartão de crédito
            </p>

            <div class="mt-12 grid gap-8 sm:grid-cols-3">
                <div class="rounded-lg border border-base-300/30 bg-base-200/20 p-6 hover:bg-base-200/40 transition-colors">
                    <p class="text-2xl font-bold text-primary">30 dias</p>
                    <p class="mt-1 text-sm text-base-content/70">Teste gratuito completo</p>
                </div>
                <div class="rounded-lg border border-base-300/30 bg-base-200/20 p-6 hover:bg-base-200/40 transition-colors">
                    <p class="text-2xl font-bold text-secondary">∞</p>
                    <p class="mt-1 text-sm text-base-content/70">Plano free para sempre</p>
                </div>
                <div class="rounded-lg border border-base-300/30 bg-base-200/20 p-6 hover:bg-base-200/40 transition-colors">
                    <p class="text-2xl font-bold text-accent">24/7</p>
                    <p class="mt-1 text-sm text-base-content/70">Suporte em português</p>
                </div>
            </div>
        </x-card>
    </div>
</section>
