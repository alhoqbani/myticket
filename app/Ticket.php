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
    
    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
    
    public function getPriceAttribute()
    {
        return $this->concert->ticket_price;
    }
}
