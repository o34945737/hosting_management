<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studios extends Model
{
    protected $guarded = [];

    // Studios.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
