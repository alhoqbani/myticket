<?php

use App\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewConcertListingTest extends TestCase
{
    
    use DatabaseMigrations;
    
    /** @test */
    function user_can_view_a_published_concert_listing()
    {
        $concert = factory(Concert::class)->states('published')->create([
            'title'                  => 'The Red Chord',
            'subtitle'               => 'with Animosity and Lethargy',
            'date'                   => Carbon::parse('December 13, 2016 8:00pm'),
            'ticket_price'           => 3250,
            'venue'                  => 'The Mosh Pit',
            'venue_address'          => '123 Example Lane',
            'city'                   => 'Laraville',
            'state'                  => 'ON',
            'zip'                    => '17916',
            'additional_information' => 'For tickets, call (555) 555-5555.',
        ]);
        
        $this->get('/concerts/' . $concert->id)
            ->assertsee('The Red Chord')
            ->assertsee('with Animosity and Lethargy')
            ->assertsee('December 13, 2016')
            ->assertsee('8:00pm')
            ->assertsee('32.50')
            ->assertsee('The Mosh Pit')
            ->assertsee('123 Example Lane')
            ->assertsee('Laraville, ON 17916')
            ->assertsee('For tickets, call (555) 555-5555.');
    }
    
    /** @test */
    function user_cannot_view_unpublished_concert_listings()
    {
        
        $concert = factory(Concert::class)->states('unpublished')->create();
        
        $this->get('/concerts/' . $concert->id)
            ->assertStatus(404);
    }
}
