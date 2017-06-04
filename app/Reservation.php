<?php

namespace App;

use Illuminate\Support\Collection;

class Reservation
{
    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }
    
    public function cancel()
    {
        /** @var \App\Ticket $ticket */
        foreach ($this->tickets as $ticket) {
            $ticket->release();
        }
    }
    
    public function totalCost()
    {
        return $this->tickets->sum('price');
    }
}
