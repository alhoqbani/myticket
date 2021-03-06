<?php

use App\Order;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrderTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function creating_an_order_from_tickets_and_email()
    {
        /** @var \App\Concert $concert */
        $concert = factory(Concert::class)->create()->addTickets(5);
        $this->assertEquals(5, $concert->ticketsRemaining());
        
        /** @var \App\Order $order */
        $order = Order::forTickets($concert->findTickets(3), 'jane@example.com', 3600);
        
        $this->assertEquals('jane@example.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(2, $concert->ticketsRemaining());
        
    }
    
    /** @test */
    public function converting_to_array()
    {
        $concert = factory(Concert::class)->create(['ticket_price' => 1200])->addTickets(10);
        $order = $concert->orderTickets('jane@example.com', 5);
        
        $result = $order->toArray();
        
        $this->assertEquals([
            'email'           => 'jane@example.com',
            'ticket_quantity' => 5,
            'amount'          => 6000,
        ], $result);
    }
    
}
