<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::livewire('/login', 'pages::autenticacao.login')->name('autenticacao.login');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});
Route::livewire('/cadastro', 'pages::autenticacao.cadastro')->name('autenticacao.cadastro');
Route::livewire('/dashboard', 'pages::autenticado.dashboard')->name('autenticado.dashboard');
Route::prefix('contas-bancarias')->group(function () {
    Route::livewire('bancos', 'pages::autenticado.contas-bancarias.bancos.listagem')->name('autenticado.contas-bancarias.bancos');
    Route::prefix('contas')->group(function () {
       Route::livewire('/', 'pages::autenticado.contas-bancarias.contas.listagem')->name('autenticado.contas-bancarias.contas.listagem');
       Route::livewire('cadastro', 'pages::autenticado.contas-bancarias.contas.cadastro')->name('autenticado.contas-bancarias.contas.cadastro');
       Route::livewire('/{contaBancaria}', 'pages::autenticado.contas-bancarias.contas.edicao')->name('autenticado.contas-bancarias.contas.edicao');
    });
});
Route::prefix('cadastros')->group(function () {
    Route::prefix('categorias')->group(function () {
        Route::livewire('/', 'pages::autenticado.cadastros.categorias.listagem')->name('autenticado.cadastros.categorias.listagem');
        Route::livewire('/cadastro', 'pages::autenticado.cadastros.categorias.cadastro')->name('autenticado.cadastros.categorias.cadastro');
        Route::livewire('/{categoriaAtual}', 'pages::autenticado.cadastros.categorias.edicao')->name('autenticado.cadastros.categorias.edicao');
    });
    Route::prefix('metodos-pagamentos')->group(function () {
       Route::livewire('/', 'pages::autenticado.cadastros.metodos-pagamentos.listagem')->name('autenticado.cadastros.metodos-pagamentos.listagem');
    });
});
Route::prefix('lancamentos')->group(function () {
    Route::prefix('recebimentos')->group(function () {
        Route::livewire('/', 'pages::autenticado.lancamentos.recebimentos.listagem')->name('autenticado.lancamentos.recebimentos.listagem');
        Route::livewire('/cadastro', 'pages::autenticado.lancamentos.recebimentos.cadastro')->name('autenticado.lancamentos.recebimentos.cadastro');
        Route::livewire('/{lancamentoAtual}', 'pages::autenticado.lancamentos.recebimentos.edicao')->name('autenticado.lancamentos.recebimentos.edicao');
    });
    Route::prefix('pagamentos')->group(function () {
        Route::livewire('/', 'pages::autenticado.lancamentos.pagamentos.listagem')->name('autenticado.lancamentos.pagamentos.listagem');
        Route::livewire('/cadastro', 'pages::autenticado.lancamentos.pagamentos.cadastro')->name('autenticado.lancamentos.pagamentos.cadastro');
        Route::livewire('/{lancamentoAtual}', 'pages::autenticado.lancamentos.pagamentos.edicao')->name('autenticado.lancamentos.pagamentos.edicao');
    });
    Route::prefix('emprestimos')->group(function () {
        Route::livewire('/', 'pages::autenticado.lancamentos.emprestimos.listagem')->name('autenticado.lancamentos.emprestimos.listagem');
    });
});
Route::prefix('admin')->middleware('can:is-admin')->group(function () {
    Route::livewire('/solicitacoes-bancos', 'pages::autenticado.admin.solicitacoes-bancos')->name('autenticado.admin.solicitacoes-bancos');
});
