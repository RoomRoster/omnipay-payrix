<?php

namespace Omnipay\Payrix;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array(
            'amount' => '10.00'
        ));

        $this->assertInstanceOf('Omnipay\Payrix\Message\TransactionCreateRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
