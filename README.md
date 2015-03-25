# Omnipay: Epay

**Epay driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements PayPal support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        ...
        "omnipay/epay": "1.2.*@dev",
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update


###The following gateways are provided by this package:
* Epay (Epay Chekcout)
* EasyPay (EasyPay check out provider)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

# BASIC USAGE 
# SEND DATA TO GATEWAY 
```php
use Omnipay\Omnipay;
$method = Epay; # Or Easypay 
$gateway = Omnipay::create($method);
$gateway->setSignature('EapyKey');
$gateway->setMin('Eapy user');
$gateway->setReturnUrl('ecample.com/{successful}'); ##retunr url after success pay
$gateway->setCancelUrl('ecample.com/{reject}'); ##return url after recject pay
//For demo pack 
$gateway->setTestMode(true);
//To generate transactionId/invoce we need basic function
$invoce = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
$params =  $params = [
            'amount'        => $amount,
            'transactionId' => $invoice,
            'currency'      => $currency,
        ];
$response = $gateway->purchase($params)->send();

if ($response->isSuccessful()) {
    // only EasyPay get IDN 
    echo($response->getRedirectData()); 
} elseif ($response->isRedirect()) {
    // redirect to epay payment gateway
    $response->redirect();
} else {
    // payment failed: display message to customer
    echo $response->getMessage();
}
```
## EPAY LISTENER 
* Listener for return payment status

```php
//Use only epay gateway
$gateway  = $gateway = Omnipay::create('epay');
$response = $gateway->capture($_POST)->send();
if ($response['invoice']) {
    if (isset($response['notify_text'])) {
        //This is requere to stop epay gateway sends data 
        //Status of pyments is $response['status']; 
        //
        echo $response['notify_text'];
    }
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

## EPAY BG 
[EPAY BG DOCUMENTATION](https://www.epay.bg/en/?page=front&p=demo)

##TODO 
* Add funtion for expire data 
* Add function for add description 
* Add for more property 

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/vanssata/omnipay-epay/issues),
or better yet, fork the library and submit a pull request.
