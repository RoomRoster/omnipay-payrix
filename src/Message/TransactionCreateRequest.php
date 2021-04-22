<?php

namespace Omnipay\Payrix\Message;

class TransactionCreateRequest extends AbstractRequest
{
    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/txns';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'expiration' => $this->getExpirationDate(),
            'merchant_id' => $this->getMerchantId(),
            'origin' => 2,
            'payment' => array(
                'number' => $this->getCard()->getNumber(),
            ),
            'total' => $this->getTotal(),
            'type' => 1,
        );
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new TransactionResponse($this, $data);
    }
}
