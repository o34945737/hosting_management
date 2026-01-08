<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendances extends Model
{
    protected $guarded = [];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedules::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
