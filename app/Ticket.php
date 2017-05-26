<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function scopeAvailable(Builder $query)
    {
        return $query->whereNull('order_id');
    }
}
