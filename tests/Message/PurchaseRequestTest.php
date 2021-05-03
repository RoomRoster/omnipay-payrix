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
                'merchant_id' => 't1_mer_607efb718a5f291ed0b77ce',
                'origin' => 2,
                'card' => new CreditCard($this->getValidCard()),
                'amount' => '10.99',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('t1_mer_607efb718a5f291ed0b77ce', $data['merchant_id']);
        $this->assertSame('10.99', $data['total']);
    }

    public function testCardData()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $expiryDate = gmdate('my', gmmktime(0, 0, 0, $card['expiryMonth'], 1, $card['expiryYear']));
        $this->assertSame($expiryDate, $data['expiration']);
        $this->assertSame([ 'number' => $card['number'] ], $data['payment']);
    }
}
