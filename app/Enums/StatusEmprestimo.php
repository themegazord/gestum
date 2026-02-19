<?php

namespace App\Enums;

enum StatusEmprestimo: string {
    case PENDENTE = 'pendente';
    case PAGO = 'pago';

    public function label(): string {
        return match ($this) {
            self::PENDENTE => 'Pendente',
            self::PAGO => 'Pago',
        };
    }

    public function color(): string {
        return match ($this) {
            self::PENDENTE => 'badge-warning',
            self::PAGO => 'badge-success',
        };
    }

    public static function options(): array {
        return collect(self::cases())->map(fn ($case) => [
            'id' => $case->value,
            'name' => $case->label()
        ])->toArray();
    }
}
