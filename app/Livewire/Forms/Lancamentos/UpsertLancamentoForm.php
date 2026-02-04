<?php

namespace App\Livewire\Forms\Lancamentos;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpsertLancamentoForm extends Form
{
    public ?string $id = null;
    public ?string $categoria_id = null;
    public ?string $conta_bancaria_id = null;
    public ?string $fatura_id = null;
    public ?string $recorrencia_id = null;
    public ?string $tipo = null;
    public ?string $descricao = null;
    public float $valor = 0.0;
    public float $valor_pago = 0.0;
    public ?string $data_vencimento = null;
    public ?string $data_pagamento = null;
    public ?string $status = null;
    public ?string $observacoes = null;
    public ?string $anexo = null;

    public function rules(): array {
        return [
            'id' => 'nullable',
            'categoria_id' => ['required', Rule::exists('categorias', 'id')],
            'conta_bancaria_id' => ['required', Rule::exists('conta_bancaria', 'id')],
            'fatura_id' => 'nullable',
            'recorrencia_id' => 'nullable',
            'tipo' => ['required', Rule::in(['receita', 'despesa'])],
            'descricao' => ['required'],
            'valor' => ['required', 'numeric', 'min:0'],
            'valor_pago' => ['nullable', 'numeric', 'min:0'],
            'data_vencimento' => ['required'],
            'data_pagamento' => 'nullable',
            'status' => ['required', Rule::in(['pendente', 'parcial', 'pago', 'recebido', 'atrasado', 'cancelado'])],
            'observacoes' => 'nullable',
            'anexo' => 'nullable',
        ];
    }

    public function validationAttributes(): array {
        return [
            'categoria_id' => 'categoria',
            'conta_bancaria_id' => 'conta bancária',
            'fatura_id' => 'fatura',
            'recorrencia_id' => 'recorrência',
            'descricao' => 'descrição',
            'valor_pago' => 'valor pago',
            'data_pagamento' => 'data pagamento',
            'data_vencimento' => 'data vencimento',
            'observacoes' => 'observação'
        ];
    }

    public function messages(): array {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'exists' => 'O :attribute não existe.',
            'in' => 'O :attribute informado não é válido.',
            'min' => 'O :attribute informado deve ser maior que :min.'
        ];
    }
}
