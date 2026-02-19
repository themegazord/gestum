<?php

namespace App\Enums;

enum TipoEmprestimo: string {
    case TOMADO = 'tomado';
    case CONCEDIDO = 'concedido';

    public function label(): string {
        return match ($this) {
            self::TOMADO => 'Tomado',
            self::CONCEDIDO => 'Concedido',
        };
    }

    public function color(): string {
        return match ($this) {
            self::TOMADO => 'badge-warning',
            self::CONCEDIDO => 'badge-success',
        };
    }

    public static function options(): array {
        return collect(self::cases())->map(fn ($case) => [
            'id' => $case->value,
            'name' => $case->label()
        ])->toArray();
    }
}
