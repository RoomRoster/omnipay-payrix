<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Response
 */
class Response extends AbstractResponse
{
    /**
     * The status of the Transaction is pending.
     *
     * @var int
     */
    const PENDING_STATUS = 0;

    /**
     * The status of the Transaction is approved.
     *
     * @var int
     */
    const APPROVED_STATUS = 1;

    /**
     * The status of the Transaction is failed.
     *
     * @var int
     */
    const FAILED_STATUS = 2;

    /**
     * The status of the Transaction is capture.
     *
     * @var int
     */
    const CAPTURED_STATUS = 3;

    /**
     * The status of the Transaction is settled.
     *
     * @var int
     */
    const SETTLED_STATUS = 4;

    /**
     * The status of the Transaction is returned.
     *
     * @var int
     */
    const RETURNED_STATUS = 5;

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if (isset($this->data['response']['data'][0]['status'])) {
            return $this->data['response']['data'][0]['status'] != static::FAILED_STATUS;
        }

        return false;
    }

    /**
     * Is the response pending?
     *
     * @return boolean
     */
    public function isPending()
    {
        if (isset($this->data['response']['data'][0]['status'])) {
            return $this->data['response']['data'][0]['status'] == static::PENDING_STATUS;
        }

        return false;
    }
}
