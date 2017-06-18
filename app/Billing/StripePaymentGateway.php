<?php

namespace App\Billing;

use Stripe\Charge;
use Stripe\Error\InvalidRequest;
use Stripe\Token;

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
    
    public function getValidTestToken()
    {
        return Token::create([
            "card" => [
                "number"    => "4242424242424242",
                "exp_month" => 1,
                "exp_year"  => date('Y') + 1,
                "cvc"       => "123",
            ],
        ], ['api_key' => $this->apiKey])->id;
    }
    
    public function newChargesDuring($charge)
    {
        $latestCharge = $this->lastCharge();
        $charge($this);
        
        return $this->newChargesSince($latestCharge)->pluck('amount');
    }
    
    private function newChargesSince($lastCharge = null)
    {
        $charges = Charge::all(
            [
                'ending_before' => $lastCharge ? $lastCharge->id : null,
            ],
            ['api_key' => $this->apiKey]
        )['data'];
        
        return collect($charges);
    }
    
    private function lastCharge()
    {
        return array_first(Charge::all(
            ['limit' => 1],
            ['api_key' => $this->apiKey]
        )['data']);
    }
    
}
