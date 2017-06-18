<?php
/**
 * Created by PhpStorm.
 * User: hamoud
 * Date: 5/25/17
 * Time: 8:25 PM
 */

namespace App\Billing;


interface PaymentGateway
{
    public function charge($amount, $token);
    
    public function getValidTestToken();
    
    public function newChargesDuring($callback);
    
    
}