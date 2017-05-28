<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['email', 'amount'];
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
    
    public function cancel()
    {
        /** @var \App\Ticket $ticket */
        foreach($this->tickets as $ticket) {
            $ticket->release();
        }
        $this->delete();
    }
    
    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }
    
    public function toArray()
    {
        return [
          'email' => $this->email,
          'ticket_quantity' => $this->ticketQuantity(),
          'amount' => $this->amount,
        ];
    }
    
    
}
