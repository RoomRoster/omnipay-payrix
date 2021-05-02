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
    public function getData(): array
    {
        $card = $this->getCard();

        return array(
            'expiration' => $card->getExpiryDate('my'),
            'merchant_id' => $this->getMerchantId(),
            'origin' => 2,
            'payment' => array(
                'number' => $card->getNumber(),
            ),
            'total' => $this->getAmount(),
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
