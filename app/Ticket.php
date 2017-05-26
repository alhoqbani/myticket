<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    
    protected $fillable = ['order_id'];
    
    public function scopeAvailable(Builder $query)
    {
        return $query->whereNull('order_id');
    }
    
    public function release()
    {
        $this->update(['order_id' => null]);
    }
}
