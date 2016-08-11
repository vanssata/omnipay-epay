<?php

namespace Omnipay\Epay\Message;

/**
 * 2Checkout Purchase Request
 */
class PurchaseEasyPayRequest extends  PurchaseRequest
{

    public $endpoint = 'https://www.epay.bg/ezp/reg_bill.cgi';

    public function setEndPoint()
    {
        return $this->endpoint= $this->getTestMode() ? 'https://demo.epay.bg/ezp/reg_bill.cgi' : $this->endpoint;
    }
//
    public function getMin()
    {
        return $this->getParameter('min');
    }

    public function setMin($value)
    {
        return $this->setParameter('min', $value);
    }

    public function getType()
    {
        return $this->getParameter('type')?$this->getParameter('type'):'payonlogin';
    }

    public function getSignature()
    {
        return $this->getParameter('signature');
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    public function sendData($data)
    {
        $url = $this->setEndPoint().'?'.http_build_query($data);
        $data['idn'] = $this->httpClient->get($url)->send()->getBody(true);
        return $this->response = new PurchaseEasyPayResponse($this, $data);
    }
}
