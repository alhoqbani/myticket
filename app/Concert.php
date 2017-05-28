<?php

namespace App;

use App\Exceptions\NotEnoughTicketsException;
use App\Order;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    
    protected $guarded = [];
    protected $dates = ['date'];
    
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
    
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }
    
    public function getFormattedStartTimeAttribute()
    {
        return $this->date->format('g:ia');
    }
    
    public function getTicketPriceInDollarsAttribute()
    {
        return number_format($this->ticket_price / 100, 2);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    /**
     * @param          $email
     * @param          $ticketQuantity
     *
     * @var \App\Order $order
     * @return \App\Order
     */
    public function orderTickets($email, $ticketQuantity)
    {
        $tickets = $this->findTickets($ticketQuantity);
    
        return $this->createOrder($email, $tickets);
    }
    
    public function addTickets($quantity)
    {
        foreach (range(1, $quantity) as $i) {
            $this->tickets()->create([]);
        }
        
        return $this;
        
    }
    
    public function ticketsRemaining()
    {
        return $this->tickets()->available()->count();
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function hasOrderFor($customerEmail)
    {
        return $this->orders()->where('email', 'john@example.com')->count() > 0;
    }
    
    public function ordersFor($customerEmail)
    {
        return $this->orders()->whereEmail($customerEmail)->get();
    }
    
    /**
     * @param $quantity
     *
     * @return mixed
     */
    public function findTickets($quantity)
    {
        $tickets = $this->tickets()->available()->take($quantity)->get();
        
        if ($tickets->count() < $quantity) {
            throw new NotEnoughTicketsException;
        }
        
        return $tickets;
    }
    
    /**
     * @param $email
     * @param $tickets
     *
     * @return \App\Order
     */
    public function createOrder($email, $tickets): Order
    {
        $order = $this->orders()->create([
            'email'  => $email,
            'amount' => $tickets->count() * $this->ticket_price,
        ]);
        
        foreach ($tickets as $ticket) {
            $order->tickets()->save($ticket);
        }
        
        return $order;
    }
}
