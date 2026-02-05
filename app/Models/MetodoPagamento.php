<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
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
}
