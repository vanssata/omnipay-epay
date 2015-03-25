<?php

namespace Omnipay\Epay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Epay Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{
    

    public function getData()
    {

        $ENCODED = @$_POST['encoded'];
        $CHECKSUM = @$_POST['checksum'];

        # XXX Secret word with which merchant make CHECKSUM on the ENCODED packet
        $hmac = $this->hmac('sha1', $ENCODED, $this->getParameter('signature')); # XXX SHA-1 algorithm REQUIRED

        if ($hmac == $CHECKSUM) { # XXX Check if the received CHECKSUM is OK
            $data = base64_decode($ENCODED);
            $lines_arr = explode("\n", $data);
            $regs_text = "";
            $notify_text = '';
            foreach ($lines_arr as $line) {
                //foreach e za multiple poru4ki, oburni vnimanie !!!
                if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) {
                    $invoice = $regs[1];
                    $status = $regs[2];
                    @$pay_date = $regs[4]; # XXX if PAID
                    @$stan = $regs[5]; # XXX if PAID
                    @$bcode = $regs[6]; # XXX if PAID

                    # XXX process $invoice, $status, $pay_date, $stan, $bcode here za multiple plashtaniq.
                    $notify_text .= "INVOICE=$invoice:STATUS=ERR\n";
                    if ($status == 'PAID') {
                        $notify_text .= "INVOICE=$invoice:STATUS=OK\n";
                    } else {
                        if ($status == 'DENIED') {
                            $notify_text .= "INVOICE=$invoice:STATUS=NO\n";
                        } else {
                            $notify_text .= "INVOICE=$invoice:STATUS=NO\n";
                        }
                    }
                }
            }
            return array("status" => $status, "invoice" => $invoice,'notify_text'=>$notify_text);
        } else {
            return array('notify_text'=> "ERR=Not valid CHECKSUM\n"); # XXX The description of error is REQUIRED
        }
    }

    public function sendData($data)
    {
        return $this->response = $data;
    }
}
