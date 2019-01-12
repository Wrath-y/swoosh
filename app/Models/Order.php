<?php

namespace App\Models;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
