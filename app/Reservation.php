<?php

namespace App;

use Illuminate\Support\Collection;

class Reservation
{
    
    protected $email;
    protected $tickets;
    
    public function __construct(Collection $tickets, $email)
    {
        $this->tickets = $tickets;
        $this->email = $email;
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
    
    public function tickets()
    {
        return $this->tickets;
    }
    
    public function email()
    {
        return $this->email;
    }
    
    /**
     * @return \App\Order
     */
    public function complete()
    {
        return Order::forTickets($this->tickets, $this->email, $this->totalCost());;
    }
}
