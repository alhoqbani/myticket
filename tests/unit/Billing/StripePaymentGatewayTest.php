<?php


use App\Billing\StripePaymentGateway;

class StripePaymentGatewayTest extends TestCase
{
    
    /** @test */
    public function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = new StripePaymentGateway(config('services.stripe.key'));
        $token = \Stripe\Token::create([
            "card" => [
                "number"    => "4242424242424242",
                "exp_month" => 1,
                "exp_year"  => date('Y') + 1,
                "cvc"       => "123",
            ],
        ], ["api_key" => config('services.stripe.key')])->id;
        $paymentGateway->charge(2500, $token);
        
        $lastCharge = \Stripe\Charge::all(
            ["limit" => 3],
            ["api_key" => config('services.stripe.key')]
        )['data'][0];
        
        $this->assertEquals(2500, $lastCharge->amount);
    }
}
