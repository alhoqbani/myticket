<?php

namespace App\Billing;

class FakePaymentGateway implements PaymentGateway
{
    
    private $charges;
    protected $beforeFirstChargeCallback;
    
    /**
     * FakePaymentGateway constructor.
     */
    public function __construct()
    {
        $this->charges = collect();
    }
    
    public function getValidTestToken()
    {
        return 'valid-token';
    }
    
    public function charge($amount, $token)
    {
        if ($this->beforeFirstChargeCallback !== null) {
            $callBack = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callBack->__invoke($this);
        }
        
        if ($token !== $this->getValidTestToken()) {
            throw new PaymentFailedException;
        }
        $this->charges[] = $amount;
    }
    
    public function totalCharges()
    {
        return $this->charges->sum();
    }
    
    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }
    
    public function newChargesDuring($charge)
    {
        $chargesFrom = $this->charges->count();
        $charge($this);
        
        return $this->charges->slice($chargesFrom)->reverse()->values();
    }
}
