<?php

namespace App\Billing;

use Illuminate\Database\Eloquent\Model;
use Stripe\Charge;

class StripePaymentGateway implements PaymentGateway
{
    
    /**
     * @var
     */
    private $apiKey;
    
    /**
     * StripePaymentGateway constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    function charge($amount, $token)
    {
        Charge::create([
            "amount" => $amount,
            "currency" => "usd",
            "source" => $token,
        ], ["api_key" => $this->apiKey]);
    }
}
