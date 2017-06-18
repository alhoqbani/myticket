<?php


use App\Billing\PaymentGateway;
use App\Billing\StripePaymentGateway;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    
    private $lastCharge;
    
    protected function setUp()
    {
        parent::setUp();
        $this->lastCharge = $this->lastCharge();
    }
    
    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = $this->getPaymentGateway();
        
        $newCharges = $paymentGateway->newChargesDuring(function (PaymentGateway $paymentGateway) {
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        });
        
        $this->assertCount(1, $newCharges);
        $this->assertEquals(2500, $newCharges->sum());
    }
    
    /**
     * @test
     */
    public function charges_with_invalid_payments_token_fail()
    {
//        try {
//            $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
//            $paymentGateway->charge(2500, 'invalid-token');
//        } catch (PaymentFailedException $e) {
//            $this->assertCount(0, $this->newCharges());
//            return;
//        }
//        $this->fail('Charging with invalid payment token did not throw an exception');
        
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
        $resault = $paymentGateway->charge(2500, 'invalid-token');
        
        $this->assertFalse($resault);
    }
    
    
    /**
     * @return \App\Billing\StripePaymentGateway
     */
    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }
    
    private function lastCharge()
    {
        return array_first(\Stripe\Charge::all(
            ['limit' => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data']);
    }
    
    private function newCharges()
    {
        return \Stripe\Charge::all(
            [
                'ending_before' => $this->lastCharge ? $this->lastCharge->id : null,
            ],
            ['api_key' => config('services.stripe.secret')]
        )['data'];
    }
    
    /**
     * @return string
     */
    private function validToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number"    => "4242424242424242",
                "exp_month" => 1,
                "exp_year"  => date('Y') + 1,
                "cvc"       => "123",
            ],
        ], ['api_key' => config('services.stripe.secret')])->id;
    }
    
}
