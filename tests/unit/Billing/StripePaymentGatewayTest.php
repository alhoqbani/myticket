<?php


use App\Billing\PaymentFailedException;
use App\Billing\PaymentGateway;
use App\Billing\StripePaymentGateway;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    
    use PaymentGatewayContractTests;
    
    /**
     * @return \App\Billing\StripePaymentGateway
     */
    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }
    
}
