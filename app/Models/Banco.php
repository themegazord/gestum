<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banco extends Model
{
    use HasUuids;

    protected $fillable = [
        'nome',
        'codigo',
    ];

    public function contasBancaria(): HasMany {
        return $this->hasMany(ContaBancaria::class, 'banco_id');
    }
}
