<?php

use App\Reservation;
use App\Ticket;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReservationTest extends TestCase
{
    
    /** @test */
    public function calculating_the_total_cost()
    {
        $reservation = new Reservation(collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200],
        ]));
        $this->assertEquals(3600, $reservation->totalCost());
    }
    
    /** @test */
    public function reserved_tickets_are_released_when_a_reservation_is_canceled()
    {
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);

        $reservation = new Reservation($tickets);
        
        $reservation->cancel();
        
        foreach ($tickets as $ticket) {
            $ticket->shouldHaveReceived('release');
        }
    }
}
