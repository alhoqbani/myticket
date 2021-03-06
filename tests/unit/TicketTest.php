<?php

namespace Tests\Unit;

use App\Concert;
use App\Ticket;
use Carbon\Carbon;
use TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TicketTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    public function a_ticket_can_be_reserved()
    {
        $ticket = factory(Ticket::class)->create();
        $this->assertNull($ticket->reserved_at);
        
        $ticket->reserve();
        
        $this->assertNotNull($ticket->fresh()->reserved_at);
    }
    
    /** @test */
    public function a_ticket_can_be_released()
    {
        $ticket = factory(Ticket::class)->states('reserved')->create();
        $this->assertNotNull($ticket->reserved_at);
    
        $ticket->release();
    
        $this->assertNull($ticket->fresh()->reserved_at);
    }
}
