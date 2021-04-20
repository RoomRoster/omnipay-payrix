<?php

namespace Omnipay\Payrix\Message;

/**
 * Response
 */
class TransactionResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['response']['data'][0]['id']);
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['response']['data'][0]['id'])) {
            return $this->data['response']['data'][0]['id'];
        }
    }
}
