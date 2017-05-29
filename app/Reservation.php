<?php

namespace App;

class Reservation
{
    //
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }
    
    public function totalCost()
    {
        return $this->tickets->sum('price');
    }
}
