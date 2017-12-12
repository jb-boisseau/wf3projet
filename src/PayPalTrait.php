<?php

namespace wf3\Payments;

use wf3\Payments\ApiContext;
use wf3\Payments\CreditCardPayment;
use wf3\Payments\ExpressCheckout;

trait PayPalTrait
{

    /**
     * @return ExpressCheckout
     */
    public function createExpressCheckout()
    {
        return $this['paypal']->createExpressCheckout();
    }

    /**
     * @return CreditCardPayment
     */
    public function createCreditCreditCardPayment()
    {
        return $this['paypal']->createCreditCardPayment();
    }

    /**
     * @return ApiContext
     */
    public function getPayPalApiContext()
    {
        return $this['paypal']->getPayPalApiContext();
    }

    /**
     * @param string $payerId
     * @param string $paymentID
     * @return bool
     */
    public function executePayment($payerId, $paymentID)
    {
        return $this['paypal']->executePayment($payerId, $paymentID);
    }

    /**
     * @return string
     */
    public function generateInvoiceNumber()
    {
        return $this['paypal']->generateInvoiceNumber();
    }
}