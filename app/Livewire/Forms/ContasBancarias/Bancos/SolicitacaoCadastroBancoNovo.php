<?php

namespace App\Livewire\Forms\ContasBancarias\Bancos;

use Livewire\Form;

class SolicitacaoCadastroBancoNovo extends Form
{
    public ?string $nome = null;
    public ?string $codigo = null;
    public ?string $observacao = null;

    protected function rules(): array
    {
        return [
            'nome' => ['required', 'max:100'],
            'codigo' => ['required', 'size:3'],
            'observacao' => ['nullable', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nome.required' => 'O nome do banco é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 100 caracteres',
            'codigo.required' => 'O código do banco é obrigatório',
            'codigo.size' => 'O código deve ter exatamente 3 dígitos',
        ];
    }
}
