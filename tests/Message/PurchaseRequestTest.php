<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'api_key' => 'test_key',
                'merchant_id' => 't1_mer_id',
                'origin' => 2,
                'card' => new CreditCard($this->getValidCard()),
                'amount' => '10.99',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('t1_mer_id', $data['merchant']);
        $this->assertSame(2, $data['origin']);
        $this->assertSame('4111111111111111', $data['payment']['number']);
        $this->assertSame(1099, $data['total']);
    }
}
