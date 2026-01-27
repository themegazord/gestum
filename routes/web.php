<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::livewire('/login', 'pages::autenticacao.login')->name('autenticacao.login');
Route::livewire('/cadastro', 'pages::autenticacao.cadastro')->name('autenticacao.cadastro');
Route::livewire('/dashboard', 'pages::autenticado.dashboard')->name('autenticado.dashboard');
Route::prefix('contas-bancarias')->group(function () {
    Route::livewire('bancos', 'pages::autenticado.bancos.listagem')->name('autenticado.contas-bancarias.bancos');
});
Route::prefix('admin')->middleware('can:is-admin')->group(function () {
    Route::livewire('/solicitacoes-bancos', 'pages::autenticado.admin.solicitacoes-bancos')->name('autenticado.admin.solicitacoes-bancos');
});
