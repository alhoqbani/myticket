<?php

namespace Tests\Unit;

use App\Concert;
use TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TicketTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_ticket_can_be_released()
    {
        /** @var \App\Concert $concert */
        $concert = factory(Concert::class)->create();
        $concert->addTickets(1);
        /** @var \App\Order $order */
        $order = $concert->orderTickets('jane@example.com', 1);
        /** @var \App\Ticket $ticket */
        $ticket = $order->tickets()->first();
        $this->assertEquals($order->id, $ticket->order_id);
        
        $ticket->release();
        
        $this->assertNull($ticket->fresh()->order_id);
    }
}
