<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roles extends Model
{
    protected $guarded = [];

    public function user(): HasMany
    {
        return $this->this(User::class);
    }
}
