<?php
/**
 * Created by PhpStorm.
 * User: vansa
 * Date: 15-3-21
 * Time: 13:40
 */

namespace Omnipay\Epay\Message;

use Omnipay\Common\Message\AbstractRequest;

class CaptureRequest extends AbstractRequest
{
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

    public function getType()
    {
        return $this->getParameter('type') ? $this->getParameter('type') : 'payonlogin';
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

//        die('<pre>'.print_r($this->parameters,1).'</pre>');
        $ENCODED  = $this->getEncoded();
        $CHECKSUM = $this->getChecksum();

        # XXX Secret word with which merchant make CHECKSUM on the ENCODED packet
        $hmac = Hmac::sendEncoded('sha1', $ENCODED, $this->getSignature()); # XXX SHA-1 algorithm REQUIRED

        if ($hmac == $CHECKSUM) { # XXX Check if the received CHECKSUM is OK
            $data        = base64_decode($ENCODED);
            $lines_arr   = explode("\n", $data);
            $regs_text   = '';
            $notify_text = '';
            foreach ($lines_arr as $line) {
                //foreach e za multiple poru4ki, oburni vnimanie !!!
                if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) {
                    $invoice  = isset($regs[1]) ? $regs[1] : null;
                    $status   = isset($regs[2]) ? $regs[2] : null;
                    $pay_date = isset($regs[4]) ? $regs[4] : null;
                    $stan     = isset($regs[5]) ? $regs[5] : null;
                    $bcode    = isset($regs[6]) ? $regs[6] : null;
                    # XXX process $invoice, $status, $pay_date, $stan, $bcode here za multiple plashtaniq.
                    $notify_text = "INVOICE=$invoice:STATUS=ERR\n";
                    if ($status == 'PAID' || $status == 'DENIED' || $status == 'EXPIRED') {
                        $notify_text = "INVOICE=$invoice:STATUS=OK\n";
                    }
                }
            }

            return array ("status" => $status, "invoice" => $invoice, 'notify_text' => $notify_text);
        } else {
            return array ('notify_text' => "ERR=Not valid CHECKSUM\n"); # XXX The description of error is REQUIRED
        }
    }

    public function sendData($data) {
        return $this->response = $data;
    }
}