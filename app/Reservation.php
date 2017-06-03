<?php

namespace App;

use Illuminate\Support\Collection;

class Reservation
{
    //
    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }
    
    public function totalCost()
    {
        return $this->tickets->sum('price');
    }
}
