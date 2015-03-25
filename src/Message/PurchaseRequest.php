<?php

namespace Omnipay\Epay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * 2Checkout Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{


    public $endpoint = 'https://www.epay.bg/';

    public function setEndPoint()
    {
        return $this->endpoint= $this->getTestMode() ? 'https://devep2.datamax.bg/ep2/epay2_demo/' : $this->endpoint;
    }


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

    public function getData()
    {

        $this->validate('amount', 'returnUrl');
        $data = array();

        if(!$this->getTransactionId()){
            $this->setTransactionId(substr(number_format(time() * rand(), 0, '', ''), 0, 10));
        }
        //  Secret word with which merchant make CHECKSUM on the ENCODED packet

        $exp_date = date('d.m.Y', mktime(0, 0, 0, date("m"), date("d") + 3, date("Y"))); // Expiration date - 3 days affter purchase
        $descr = $this->getDescription(); // Description

        $en = "<<<DATA\nMIN={$this->getMin()}\nINVOICE={$this->getTransactionId()}\nAMOUNT=" . $this->getAmount() . "\nEXP_TIME={$exp_date}\nDESCR={$descr}";

        $ENCODED = base64_encode($en);
        $CHECKSUM = $this->hmac('sha1', $ENCODED, $this->getSignature()); /* SHA-1 algorithm REQUIRED */

        $data['PAGE'] ='paylogin';
        $data['ENCODED'] = $ENCODED;
        $data['CHECKSUM'] = $CHECKSUM;
        $data['URL_OK'] = $this->getReturnUrl();
        $data['URL_CANCEL'] = $this->getCancelUrl();
//        die('<pre>'.print_r($this->getMin(),1).'</pre>');
//
//        if ($this->getTestMode()) {
//            $data['demo'] = 'Y';
//        }

        return $data;
    }

    /**
     * @param $algo
     * @param $data
     * @param $passwd
     * @return mixed
     */
    public function hmac($algo, $data, $passwd)
    {
        /* md5 and sha1 only */
        $algo = strtolower($algo);
        $p = array('md5' => 'H32', 'sha1' => 'H40');
        if (strlen($passwd) > 64) {
            $passwd = pack($p[$algo], $algo($passwd));
        }
        if (strlen($passwd) < 64) {
            $passwd = str_pad($passwd, 64, chr(0));
        }

        $ipad = substr($passwd, 0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($passwd, 0, 64) ^ str_repeat(chr(0x5C), 64);

        return ($algo($opad . pack($p[$algo], $algo($ipad . $data))));
    }

    public function sendData($data)
    {
        $this->setEndPoint();
        return $this->response = new PurchaseResponse($this, $data);
    }
}
