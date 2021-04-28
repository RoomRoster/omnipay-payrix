<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class TransactionCreateRequestTest extends TestCase
{
    /**
     * @var TransactionCreateRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new TransactionCreateRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'expiration_date' => '1222',
                'merchant_id' => 't1_mer_607efb718a5f291ed0b77ce',
                'card' => new CreditCard($this->getValidCard()),
                'total' => 10.99,
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('1222', $data['expiration']);
        $this->assertSame('t1_mer_607efb718a5f291ed0b77ce', $data['merchant_id']);
        $this->assertSame([ 'number' => '4111111111111111' ], $data['payment']);
        $this->assertSame(10.99, $data['total']);
    }
}
