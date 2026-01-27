<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::livewire('/login', 'pages::autenticacao.login')->name('autenticacao.login');
Route::livewire('/cadastro', 'pages::autenticacao.cadastro')->name('autenticacao.cadastro');
Route::livewire('/dashboard', 'pages::autenticado.dashboard')->name('autenticado.dashboard');
Route::prefix('bancos')->group(function () {
    Route::livewire('/', 'pages::autenticado.bancos.listagem')->name('autenticacado.bancos.listagem');
});
Route::prefix('admin')->middleware('can:is-admin')->group(function () {
    // rotas para admin
});
