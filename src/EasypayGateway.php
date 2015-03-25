<?php

namespace Omnipay\Epay;

/**
 * Easypay Gateway
 *
 * @link http://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 */
class EasypayGateway extends Gateway
{
    public function getName()
    {
        return 'Easypay';
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\PurchaseEasyPayRequest', $parameters);
    }

}
