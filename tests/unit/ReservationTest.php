<?php

use App\Concert;
use App\Reservation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReservationTest extends TestCase
{
    /** @test */
    public function calculating_the_total_cost()
    {
        $reservation = new Reservation(collect([
            (object) ['price' => 1200],
            (object) ['price' => 1200],
            (object) ['price' => 1200],
        ]));
        $this->assertEquals(3600, $reservation->totalCost());
    
    }
}
