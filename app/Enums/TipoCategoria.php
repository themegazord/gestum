<?php

namespace App\Enums;

enum TipoCategoria: string {
    case RECEITA = 'receita';
    case DESPESA = 'despesa';

    public function label(): string {
        return match ($this) {
            self::RECEITA => 'Receita',
            self::DESPESA => 'Despesa'
        };
    }

    public function color(): string {
        return match ($this) {
            self::RECEITA => 'badge-success',
            self::DESPESA => 'badge-error'
        };
    }

    public static function options(): array {
        return collect(self::cases())->map(fn ($case) => [
            'id' => $case->value,
            'name' => $case->label()
        ])->toArray();
    }
}
