<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $this->success('Login realizado com sucesso!');

            return redirect()->intended('/dashboard');
        }

        $this->error('Credenciais inválidas. Verifique seu email e senha.');
    }

    public function loginWithGoogle(): void
    {
        // Implementar OAuth Google
        // return redirect()->route('auth.google');
    }

    public function loginWithGithub(): void
    {
        // Implementar OAuth GitHub
        // return redirect()->route('auth.github');
    }

    public function render()
    {
        return $this->view()->layout('layouts.guest')->title('login');
    }
};

?>

<div
    class="min-h-screen bg-linear-to-br from-base-100 via-base-100 to-base-200 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo/Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-primary mb-2">Gestum</h1>
            <p class="text-base-content/70">Controle suas finanças com facilidade</p>
        </div>

        {{-- Card de Login --}}
        <x-card class="shadow-2xl border-base-300">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-base-content mb-1">Bem-vindo de volta</h2>
                <p class="text-sm text-base-content/70">
                    Faça login para acessar sua conta
                </p>
            </div>

            <x-form wire:submit="login">
                {{-- Email --}}
                <x-input label="Email" wire:model="email" type="email" placeholder="seu@email.com" icon="o-envelope"
                    inline />

                {{-- Senha --}}
                <x-input label="Senha" wire:model="password" type="password" placeholder="••••••••"
                    icon="o-lock-closed" inline>
                </x-input>
                <a href="/forgot-password" class="text-xs text-primary hover:underline">
                    Esqueceu sua senha?
                </a>

                {{-- Remember Me --}}
                <x-checkbox label="Lembrar de mim" wire:model="remember" class="checkbox-sm" />

                {{-- Botão de Login --}}
                <x-button label="Entrar" type="submit" spinner="login" class="btn-primary w-full" />
            </x-form>

            {{-- Divider --}}
            <div class="divider">Ou</div>

            {{-- Social Login --}}
            <div class="space-y-2">
                <x-button label="Continuar com Google" wire:click="loginWithGoogle" class="btn-outline w-full"
                    spinner="loginWithGoogle" disabled />

                <x-button label="Continuar com GitHub" wire:click="loginWithGithub" class="btn-outline w-full"
                    spinner="loginWithGithub" disabled />
            </div>

            {{-- Link para Cadastro --}}
            <p class="mt-6 text-center text-sm text-base-content/70">
                Não tem uma conta?
                <x-button link="/cadastro" label="Cadastre-se agora" class="btn-link"/>
            </p>
        </x-card>

        {{-- Footer Info --}}
        <p class="mt-6 text-center text-xs text-base-content/60">
            Ao fazer login, você concorda com nossos
            <a href="/terms" class="text-primary hover:underline">
                Termos de Serviço
            </a>
            e
            <a href="/privacy" class="text-primary hover:underline">
                Política de Privacidade
            </a>
        </p>
    </div>
</div>
