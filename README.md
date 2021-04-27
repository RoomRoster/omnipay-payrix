# Omnipay: Payrix

**Payrix driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Stripe support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply require `league/omnipay` and `eventconnect/omnipay-payrix` with Composer:

```
composer require league/omnipay eventconnect/omnipay-payrix
```

## Basic Usage
```php
$gateway = Omnipay::create('Payrix');
$gateway->setApiKey(API_KEY);
$gateway->setMerchantId(MERCHANT_ID);

$formData = [
    'number' => '4242424242424242',
    'expiryMonth' => '6',
    'expiryYear' => '2030',
    'cvv' => '123'
];
$response = $gateway->purchase([
    'amount' => '10.00',
    'currency' => 'CAD',
    'card' => $formData,
])->send();

if ($response->isSuccessful()) {
    // Payment was successful
} else {
    // Payment failed
}
```
For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay) repository.

## Limitations
Currently this package supports the following methods:
- purchase

## Test Mode
TODO

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release announcements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/RoomRoster/omnipay-payrix/issues),
or better yet, fork the library and submit a pull request.

## TODO
- [ ] Tests (!)
- [ ] Support for additional Payrix functionality
