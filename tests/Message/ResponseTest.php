<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('TransactionCreateSuccess.txt');
        $data = json_decode($httpResponse->getBody(), true);
        $response = new TransactionResponse($this->getMockRequest(), $data);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('t1_txn_6088a329472c671e4963e30', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
