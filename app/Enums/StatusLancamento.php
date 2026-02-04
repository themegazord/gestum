<?php

namespace App\Enums;

enum StatusLancamento: string {
    case PENDENTE = 'pendente';
    case PARCIAL = 'parcial';
    case PAGO = 'pago';
    case RECEBIDO = 'recebido';
    case ATRASADO = 'atrasado';
    case CANCELADO = 'cancelado';

    public function label(): string {
        return match ($this) {
            self::PENDENTE => 'Pendente',
            self::PARCIAL => 'Parcial',
            self::PAGO => 'Pago',
            self::RECEBIDO => 'Recebido',
            self::ATRASADO => 'Atrasado',
            self::CANCELADO => 'Cancelado'
        };
    }

    public function color(): string {
        return match ($this) {
            self::PENDENTE => 'badge-warning',
            self::PARCIAL => 'badge-info',
            self::PAGO, self::RECEBIDO => 'badge-success',
            self::ATRASADO => 'badge-error',
            self::CANCELADO => 'badge-neutral'
        };
    }

    public static function options(): array {
        return collect(self::cases())->map(fn ($case) => [
            'id' => $case->value,
            'name' => $case->label()
        ])->toArray();
    }
}
