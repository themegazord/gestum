<?php

namespace App\Livewire\Forms\Cadastros\Categorias;

use App\Enums\TipoCategoria;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpsertCategoriaForm extends Form
{
    public ?int $user_id = null;
    public ?string $nome = null;
    public ?string $tipo = null;
    public ?string $categoria_pai_id = null;
    public ?string $cor = null;

    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'nome' => 'required',
            'tipo' => ['required', Rule::enum(TipoCategoria::class)],
            'categoria_pai_id' => 'nullable',
            'cor' => 'nullable',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'categoria_pai_id' => 'categoria pai',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'enum' => 'O :attribute deve ser do tipo válido',
        ];
    }
}
