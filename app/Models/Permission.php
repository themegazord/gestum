<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasUuids;

    protected $fillable = [
        'permission'
    ];

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
