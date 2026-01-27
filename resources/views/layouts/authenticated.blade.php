<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="lemonade">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestum - {{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <div class="ml-5 pt-5">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-primary-foreground font-bold">
                    G
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 -my-2! rounded">
                        <x-slot:actions>
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs -mx-2" tooltip-left="logoff"
                                no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-item title="Dashboard" icon="o-sparkles" link="/dashboard" />
                <x-menu-sub title="Contas a receber" icon="o-arrow-trending-up">
                    <x-menu-item title="Recebimentos" icon="o-banknotes" link="####" />
                    <x-menu-item title="Baixas" icon="o-check-circle" link="####" />
                </x-menu-sub>

                <x-menu-sub title="Contas a pagar" icon="o-arrow-trending-down">
                    <x-menu-item title="Pagamentos" icon="o-credit-card" link="####" />
                    <x-menu-item title="Baixas" icon="o-clipboard-document-check" link="####" />
                </x-menu-sub>

                <x-menu-sub title="Recorrências" icon="o-arrow-path">
                    <x-menu-item title="Receitas fixas" icon="o-plus-circle" link="####" />
                    <x-menu-item title="Despesas fixas" icon="o-minus-circle" link="####" />
                </x-menu-sub>

                <x-menu-sub title="Relatórios" icon="o-document-chart-bar">
                    <x-menu-item title="DRE" icon="o-table-cells" link="####" />
                    <x-menu-item title="Extrato" icon="o-document-text" link="####" />
                    <x-menu-item title="Por categoria" icon="o-chart-pie" link="####" />
                    <x-menu-item title="Por período" icon="o-calendar-days" link="####" />
                </x-menu-sub>

                <x-menu-sub title="Cadastros" icon="o-folder-open">
                    <x-menu-item title="Categorias" icon="o-tag" link="####" />
                    <x-menu-item title="Clientes" icon="o-users" link="####" />
                    <x-menu-item title="Fornecedores" icon="o-truck" link="####" />
                    <x-menu-item title="Centros de custo" icon="o-building-office" link="####" />
                </x-menu-sub>

                <x-menu-sub title="Planejamento" icon="o-light-bulb">
                    <x-menu-item title="Orçamento" icon="o-calculator" link="####" />
                    <x-menu-item title="Metas" icon="o-flag" link="####" />
                </x-menu-sub>

                @can ('is-admin')
                    <x-menu-sub title="Administração" icon="o-cog">
                        <x-menu-item title="Solicitações de bancos" icon="o-inbox-stack"
                            link="{{ route('autenticado.admin.solicitacoes-bancos') }}" />
                        <x-menu-item title="Permissões" icon="o-shield-check" link="/admin/permissoes" />
                        <x-menu-item title="Usuários" icon="o-users" link="/admin/usuarios" />
                    </x-menu-sub>
                @endif

                <x-menu-item title="Bancos" icon="o-building-library" link="{{ route('autenticado.bancos.listagem') }}" />
                <x-menu-item title="Transferências" icon="o-arrows-right-left" link="####" />
                <x-menu-item title="Cartões de crédito" icon="o-credit-card" link="####" />
                <x-menu-item title="Fluxo de caixa" icon="o-chart-bar" link="####" />
            </x-menu>
        </x-slot:sidebar>


        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>
    <x-toast />
</body>

</html>
