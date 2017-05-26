<?php

namespace Tests\Feature;

use App\Concert;
use App\Order;
use TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class OrderTest
 *
 * @package Tests\Feature
 */
class OrderTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function tickets_are_released_when_an_order_is_cancelled()
    {
        
        /** @var \App\Concert $concert */
        $concert = factory(Concert::class)->create();
        $concert->addTickets(10);
        $order = $concert->orderTickets('jane@example.com', 5);
        $this->assertEquals(5, $concert->ticketsRemaining());
        
        $order->cancel();
        
        $this->assertEquals(10, $concert->ticketsRemaining());
        $this->assertNull(Order::find($order->id));
    }
}
