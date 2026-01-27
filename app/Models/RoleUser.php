<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasUuids;

    protected $table = 'role_user';

    protected $fillable = [
        'user_id',
        'role_id'
    ];
}
