<?php

namespace App\Http\Controllers;

use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Concert;
use App\Exceptions\NotEnoughTicketsException;
use Illuminate\Http\Request;

class ConcertsOrdersController extends Controller
{
    
    protected $paymentGateway;
    
    /**
     * ConcertsOrdersController constructor.
     *
     * @param \App\Billing\PaymentGateway $paymentGateway
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }
    
    public function store(Concert $publishedConcert, Request $request)
    {
        $this->validate($request, [
            'email'           => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token'   => ['required'],
        ]);
        try {
            $tickets = $publishedConcert->findTickets(request('ticket_quantity'));
            $this->paymentGateway->charge(request('ticket_quantity') * $publishedConcert->ticket_price,
                request('payment_token'));
            $order = $publishedConcert->createOrder(request('email'), $tickets);
            
            return response()->json($order->toArray(), 201);
            
        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json('', 422);
        }
    }
}
