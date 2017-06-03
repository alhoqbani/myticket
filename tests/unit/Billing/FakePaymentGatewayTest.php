<?php

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FakePaymentGatewayTest extends TestCase
{
    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = new FakePaymentGateway;
        
        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        
        $this->assertEquals(2500, $paymentGateway->totalCharges());
    }
    
    /**
     * @test
     */
    public function charges_with_invalid_payments_token_fail()
    {
        try {
            $paymentGateway = new FakePaymentGateway;
            $paymentGateway->charge(2500, 'invalid-token');
        } catch (PaymentFailedException $e) {
            return;
        }
        $this->fail('An exception should be thrown');
    }
    
    /** @test */
    function running_a_hook_before_the_first_charge()
    {
        $paymentGateway = new FakePaymentGateway;
        $timesCallbackRun = 0;
        $paymentGateway->beforeFirstCharge(function ($paymentGateway) use (&$timesCallbackRun) {
            $timesCallbackRun++;
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
            $this->assertEquals(2500, $paymentGateway->totalCharges());
        });
        $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        $this->assertEquals(1, $timesCallbackRun);
        $this->assertEquals(5000, $paymentGateway->totalCharges());
    }
}