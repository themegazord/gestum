<?php

namespace App\Livewire\Forms\Lancamentos\Emprestimos;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UpsertEmprestimoForm extends Form
{
    public ?string $user_id = null;
    public ?string $descricao = null;
    public float $valor_principal = 0.0;
    public float $valor_retorno = 0.0;
    public ?string $categoria_principal_id = null;
    public ?string $categoria_retorno_id = null;
    public ?string $conta_bancaria_id = null;
    public ?string $tipo = null;
    public ?string $data_emprestimo = null;
    public ?string $data_vencimento = null;
    public ?string $status = null;
    public ?string $observacao = null;

    public function rules(): array {
        return [
            'user_id' => 'nullable',
            'descricao' => ['required', 'string', 'max:255'],
            'valor_principal' => ['required', 'numeric', 'min:0.01'],
            'valor_retorno' => ['required', 'numeric', 'min:0.01'],
            'categoria_principal_id' => 'required',
            'categoria_retorno_id' => 'required',
            'conta_bancaria_id' => 'required',
            'tipo' => 'required',
            'data_emprestimo' => 'required',
            'data_vencimento' => 'required',
            'status' => 'required',
            'observacao' => 'nullable',
        ];
    }

    public function messages(): array {
        return [
            'required' => 'O campo :attribute é obrigatorio.',
            'string' => 'O campo :attribute deve ser uma string.',
            'numeric' => 'O campo :attribute deve ser um valor real.',
            'max' => 'O campo :attribute deve conter no máximo :max caracteres.',
            'min' => 'O campo :attribute deve ser no minimo R$ :min',
        ];
    }

    public function attributes(): array {
        return [
            'descricao' => 'descrição',
            'valor_principal' => 'valor',
            'valor_retorno' => 'valor',
            'categoria_principal_id' => 'categoria principal',
            'categoria_retorno_id' => 'categoria de retorno',
            'conta_bancaria_id' => 'conta bancaria',
            'tipo' => 'tipo',
            'data_emprestimo' => 'data emprestimo',
            'data_vencimento' => 'data vencimento',
            'status' => 'status',
            'observacao' => 'observacao',
        ];
    }
}
