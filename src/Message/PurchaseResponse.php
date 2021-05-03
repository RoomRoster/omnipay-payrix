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
            return $this->data['response']['data'][0]['status'] === static::APPROVED_STATUS;
        }

        if (isset($this->data['errors'][0]) || isset($this->data['response']['errors'][0])) {
            return false;
        }

        return isset($this->data['response']['data'][0]['id']);
    }
}
