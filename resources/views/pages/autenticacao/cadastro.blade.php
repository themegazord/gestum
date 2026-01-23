<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Validate('required', message: 'O nome completo é obrigatório')]
    #[Validate('min:3', message: 'O nome deve ter pelo menos 3 caracteres')]
    public string $fullName = '';

    #[Validate('required', message: 'O email é obrigatório')]
    #[Validate('email', message: 'Digite um email válido')]
    #[Validate('unique:users,email', message: 'Este email já está cadastrado')]
    public string $email = '';

    #[Validate('required', message: 'A senha é obrigatória')]
    #[Validate('min:8', message: 'A senha deve ter pelo menos 8 caracteres')]
    public string $password = '';

    #[Validate('required', message: 'Confirme sua senha')]
    #[Validate('same:password', message: 'As senhas não coincidem')]
    public string $confirmPassword = '';

    public bool $acceptTerms = false;
    public bool $success = false;

    public function signup(): void
    {
        if (!$this->acceptTerms) {
            $this->error('Você deve aceitar os Termos de Serviço');
            return;
        }

        $this->validate();

        // Criar usuário
        $user = User::create([
            'name' => $this->fullName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Fazer login automaticamente
        Auth::login($user);

        $this->success = true;

        $this->success('Cadastro realizado com sucesso!', 'Redirecionando para o dashboard...');

        // Redirecionar após 2 segundos
        $this->dispatch('redirect-dashboard');
    }

    public function resetSuccess(): void
    {
        $this->success = false;
    }

    public function signupWithGoogle(): void
    {
        // return redirect()->route('auth.google');
    }

    public function signupWithGithub(): void
    {
        // return redirect()->route('auth.github');
    }

    public function getPasswordStrength(): string
    {
        $password = $this->password;
        $length = strlen($password);

        if ($length === 0) {
            return '';
        }
        if ($length < 6) {
            return 'weak';
        }
        if ($length < 10) {
            return 'medium';
        }

        $hasNumber = preg_match('/[0-9]/', $password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasSpecial = preg_match('/[^a-zA-Z0-9]/', $password);

        if ($hasNumber && $hasUpper && $hasSpecial) {
            return 'strong';
        }
        return 'medium';
    }

    public function render()
    {
        return $this->view()->layout('layouts.guest')->title('Cadastro');
    }
};

?>

<div
    class="min-h-screen bg-linear-to-br from-base-100 via-base-100 to-base-200 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo/Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-primary mb-2">Gestum</h1>
            <p class="text-base-content/70">Comece a controlar suas finanças agora</p>
        </div>

        {{-- Card de Cadastro --}}
        <x-card class="shadow-2xl border-base-300">
            @if ($success)
                {{-- Success Message --}}
                <div class="space-y-4 text-center py-4">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-success/20">
                        <x-icon name="o-check" class="h-8 w-8 text-success" />
                    </div>
                    <h2 class="text-2xl font-bold text-base-content">
                        Cadastro realizado!
                    </h2>
                    <p class="text-base-content/70">
                        Sua conta foi criada com sucesso. Você será redirecionado para o dashboard em breve.
                    </p>
                    <x-button label="Voltar ao formulário" wire:click="resetSuccess" class="btn-outline w-full" />
                </div>
            @else
                {{-- Signup Form --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-base-content mb-1">
                        Criar conta
                    </h2>
                    <p class="text-sm text-base-content/70">
                        Preencha os dados abaixo para começar
                    </p>
                </div>

                <x-form wire:submit="signup">
                    {{-- Nome Completo --}}
                    <x-input label="Nome Completo" wire:model="fullName" placeholder="João Silva" icon="o-user"
                        inline />

                    {{-- Email --}}
                    <x-input label="Email" wire:model="email" type="email" placeholder="seu@email.com"
                        icon="o-envelope" inline />

                    {{-- Senha --}}
                    <div>
                        <x-input label="Senha" wire:model.live="password" type="password" placeholder="••••••••"
                            icon="o-lock-closed" inline />

                        {{-- Password Strength --}}
                        @if (strlen($password) > 0)
                            <div class="mt-2">
                                @php
                                    $strength = $this->getPasswordStrength();
                                    $strengthColors = [
                                        'weak' => 'progress-error',
                                        'medium' => 'progress-warning',
                                        'strong' => 'progress-success',
                                    ];
                                    $strengthValues = [
                                        'weak' => 33,
                                        'medium' => 66,
                                        'strong' => 100,
                                    ];
                                    $strengthLabels = [
                                        'weak' => 'Fraca',
                                        'medium' => 'Média',
                                        'strong' => 'Forte',
                                    ];
                                @endphp

                                <div class="flex items-center gap-2">
                                    <progress class="progress {{ $strengthColors[$strength] ?? '' }} w-full h-2"
                                        value="{{ $strengthValues[$strength] ?? 0 }}" max="100">
                                    </progress>
                                    <span class="text-xs text-base-content/70">
                                        {{ $strengthLabels[$strength] ?? '' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Confirmar Senha --}}
                    <x-input label="Confirmar Senha" wire:model="confirmPassword" type="password" placeholder="••••••••"
                        icon="o-lock-closed" inline />

                    {{-- Termos --}}
                    <x-checkbox wire:model="acceptTerms" class="checkbox-sm">
                        <x-slot:label>
                            <span class="text-xs text-base-content/70">
                                Eu concordo com os
                                <a href="/terms" class="link link-primary">Termos de Serviço</a>
                                e
                                <a href="/privacy" class="link link-primary">Política de Privacidade</a>
                            </span>
                        </x-slot:label>
                    </x-checkbox>

                    {{-- Submit Button --}}
                    <x-button label="Criar conta" type="submit" spinner="signup" class="btn-primary w-full" />
                </x-form>

                {{-- Divider --}}
                <div class="divider">Ou</div>

                {{-- Social Signup --}}
                <div class="space-y-2">
                    <x-button label="Cadastro com Google" wire:click="signupWithGoogle" class="btn-outline w-full"
                        spinner="signupWithGoogle" disabled />

                    <x-button label="Cadastro com GitHub" wire:click="signupWithGithub" class="btn-outline w-full"
                        spinner="signupWithGithub" disabled />
                </div>

                {{-- Link para Login --}}
                <p class="mt-6 text-center text-sm text-base-content/70">
                    Já tem uma conta?
                    <a href="/login" class="font-semibold text-primary hover:underline">
                        Faça login
                    </a>
                </p>
            @endif
        </x-card>
    </div>
</div>

@script
    <script>
        $wire.on('redirect-dashboard', () => {
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        });
    </script>
@endscript
