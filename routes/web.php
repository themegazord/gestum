<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::livewire('/login', 'pages::autenticacao.login')->name('autenticacao.login');
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
});
Route::prefix('contas-receber')->group(function () {
    Route::livewire('/', 'pages::autenticado.contas-receber.listagem')->name('autenticado.contas-receber.listagem');
    Route::livewire('/cadastro', 'pages::autenticado.contas-receber.cadastro')->name('autenticado.contas-receber.cadastro');
});
Route::prefix('admin')->middleware('can:is-admin')->group(function () {
    Route::livewire('/solicitacoes-bancos', 'pages::autenticado.admin.solicitacoes-bancos')->name('autenticado.admin.solicitacoes-bancos');
});
