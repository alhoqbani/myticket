<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use App\Concert;
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
    
    public function store(Concert $concert)
    {
        $this->paymentGateway->charge(request('ticket_quantity') * $concert->ticket_price, request('payment_token'));
        $order = $concert->orderTickets(request()->email, request('ticket_quantity'));
        
        return response()->json('', 201);
    }
}
