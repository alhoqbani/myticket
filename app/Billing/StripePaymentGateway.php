<?php

namespace App\Billing;

use Illuminate\Database\Eloquent\Model;
use Stripe\Charge;
use Stripe\Error\InvalidRequest;

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
        try {
            Charge::create([
                "amount"   => $amount,
                "currency" => "usd",
                "source"   => $token,
            ], ["api_key" => $this->apiKey]);
        } catch (InvalidRequest $exception) {
            throw new PaymentFailedException($exception->getMessage());
        }
    }
}
