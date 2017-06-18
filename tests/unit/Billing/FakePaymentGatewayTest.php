<?php

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;

class FakePaymentGatewayTest extends TestCase
{
    
    use PaymentGatewayContractTests;
  
    /** @test */
    public function running_a_hook_before_the_first_charge()
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
    
    /**
     * @return \App\Billing\FakePaymentGateway
     */
    protected function getPaymentGateway()
    {
        return new FakePaymentGateway;
    }
}
