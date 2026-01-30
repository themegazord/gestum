<?php

namespace App\Livewire\Forms\ContasBancarias\Contas;

use Livewire\Attributes\Validate;
use Livewire\Form;

#[Validate(rule: [
    'banco_id' => ['required', 'uuid'],
    'nome' => ['required'],
    'numero_conta' => ['nullable'],
    'tipo' => ['required'],
    'saldo_inicial' => ['required', 'min:0'],
    'saldo_atual' => ['nullable', 'min:0'],
], attribute: [
    'banco_id' => 'id do banco',
    'numero_conta' => 'número da conta',
    'saldo_inicial' => 'saldo inicial',
    'saldo_atual' => 'saldo atual',
], message: [
    'required' => 'O(a) :attribute é obrigatório',
    'min' => 'O(a) :attribute não poder ser menor que :min'
])]
class CadastroContaBancariaForm extends Form
{
    public ?string $banco_id = null;
    public ?string $nome = null;
    public ?string $numero_conta = null;
    public ?string $tipo = null;
    public ?float $saldo_inicial = null;
    public ?float $saldo_atual = null;
}
