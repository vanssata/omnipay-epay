<?php

namespace Omnipay\Epay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Epay\Message\CompletePurchaseRequest;
use Omnipay\Epay\Message\PurchaseRequest;
use Omnipay\Epay\Message\CaptureRequest;

/**
 * 2Checkout Gateway
 *
 * @link http://www.2checkout.com/documentation/Advanced_User_Guide.pdf
 */
class EpayGateway extends AbstractGateway
{

    public function getName()
    {
        return 'Epay';
    }


    public function getDefaultParameters()
    {
        $settings = parent::getDefaultParameters();
        $settings['signature'] = '';
        $settings['min'] = '';
        $settings['encoded'] = '';
        $settings['checksum'] = '';
        $settings['endpoint'] = '';
        return $settings;
    }

    public function getSignature()
    {
        return $this->getParameter('signature');
    }

    public function setSignature($value)
    {
        return $this->setParameter('signature', $value);
    }

    public function getEncoded()
    {
        return $this->getParameter('encoded');
    }

    public function setEncoded($value)
    {
        return $this->setParameter('encoded', $value);
    }

    public function getChecksum()
    {
        return $this->getParameter('checksum');
    }

    public function setChecksum($value)
    {
        return $this->setParameter('checksum', $value);
    }


    public function getMin()
    {
        return $this->getParameter('min');
    }

    public function setMin($value)
    {
        return $this->setParameter('min', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\CompletePurchaseRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Epay\Message\CaptureRequest', $parameters);
    }
}
