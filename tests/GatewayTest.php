<?php

namespace Omnipay\Payrix;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );
    }

//    public function testAuthorize()
//    {
//        $this->setMockHttpResponse('TransactionCreateSuccess.txt');
//
//        $response = $this->gateway->authorize($this->options)->send();
//
//        $this->assertTrue($response->isSuccessful());
//        $this->assertEquals('12345', $response->getTransactionReference());
//        $this->assertNull($response->getMessage());
//    }
}
