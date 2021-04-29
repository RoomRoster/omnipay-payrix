<?php

namespace Omnipay\Payrix\Message;

/**
 * Response
 */
class PurchaseResponse extends Response
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if (isset($this->data['response']['data'][0]['status'])) {
            return $this->data['response']['data'][0]['status'] == static::APPROVED_STATUS;
        }

        return isset($this->data['response']['data'][0]['id']);
    }
}
