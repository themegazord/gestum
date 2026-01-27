<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasUuids;

    protected $fillable = [
        'roles'
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }


    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function darPermissaoPara(string $permission): void {
        $p = Permission::query()->firstOrCreate(compact('permission'));

        $this->permission()->attach($p);
    }

    public function contemPermissaoPara(string $permission): bool {
        return $this->permissions()->where('permission', $permission)->exists();
    }
}
