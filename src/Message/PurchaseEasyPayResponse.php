<?php

namespace Omnipay\Epay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * 2Checkout Purchase Response
 */
class PurchaseEasyPayResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
      $this->data = $data;
        $this->request = $request;
    }


    public function isSuccessful()
    {
        return true;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
       return $this->request->endpoint.'?'.http_build_query($this->data);
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getMessage()
    {
        if (!$this->isRedirect()) {
            return (string) parent::getMessage();
        }
    }
    public function getRedirectData()
    {
        return $this->data['idn'];
    }
}
