<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['email'];
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function cancel()
    {
        /** @var \App\Ticket $ticket */
        foreach($this->tickets as $ticket) {
            $ticket->release();
        }
        $this->delete();
    }
}
