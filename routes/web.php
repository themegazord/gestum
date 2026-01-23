<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home');
Route::livewire('/login', 'pages::autenticacao.login')->name('autenticacao.login');
Route::livewire('/cadastro', 'pages::autenticacao.cadastro')->name('autenticacao.cadastro');
