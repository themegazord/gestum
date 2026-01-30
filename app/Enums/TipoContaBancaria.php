<?php

namespace App\Enums;

enum TipoContaBancaria: string
{
    case CORRENTE = 'corrente';
    case POUPANCA = 'poupanca';
    case SALARIO = 'salario';
    case PAGAMENTO = 'pagamento';

    public function label(): string
    {
        return match ($this) {
            self::CORRENTE => 'Conta Corrente',
            self::POUPANCA => 'Conta Poupança',
            self::SALARIO => 'Conta Salário',
            self::PAGAMENTO => 'Conta Pagamento',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'id' => $case->value,
            'name' => $case->label(),
        ])->toArray();
    }
}
