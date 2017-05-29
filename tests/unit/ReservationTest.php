<?php

use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReservationTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function calculating_the_total_cost()
    {
        /** @var \App\Concert $concert */
        $concert = factory(Concert::class)->create(['ticket_price' => 1200])->addTickets(3);
        $tickets = $concert->findTickets(3);
        
        $reservation = new \App\Reservation($tickets);
        
        $this->assertEquals(3600, $reservation->totalCost());
    
    }
}
