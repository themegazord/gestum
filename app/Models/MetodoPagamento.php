<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPagamento extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'metodo_pagamento';

    protected $fillable = [
        'user_id',
        'nome',
        'cor'
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? 'id', $value)->firstOrFail();
    }

    public function baixasParciais(): HasMany {
        return $this->hasMany(BaixaParcial::class, 'metodo_pagamento_id');
    }

    public function badgeMetodoPagamento(): string {
        return \Blade::render(
            "<x-badge :value='\$nome' class='badge' style='background-color: {{ \$cor }}'/>",
            ['nome' => $this->nome, 'cor' => $this->cor]
        );
    }
}
