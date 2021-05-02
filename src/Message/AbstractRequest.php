<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Payrix\Message\Response;

abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * Originated at a credit card terminal.
     *
     * @var int
     */
    const TERMINAL_ORIGIN = 1;

    /**
     * Originated through an eCommerce system.
     *
     * @var int
     */
    const ECOMMERCE_ORIGIN = 2;

    /**
     * Originated as a mail order or telephone order transaction.
     *
     * @var int
     */
    const MAIL_ORDER_ORIGIN = 3;

    /**
     * Originated with Apple Pay.
     *
     * @var int
     */
    const APPLE_PAY_ORIGIN = 4;

    /**
     * Originated as a Successful 3D Secure transaction.
     *
     * @var int
     */
    const SECURE_3D_SUCCESS_ORIGIN = 5;

    /**
     * Originated as an Attempted 3D Secure transaction.
     *
     * @var int
     */
    const SECURE_3D_ATTEMPT_ORIGIN = 6;

    /**
     * Originated as a recurring transaction on the card.
     *
     * @var int
     */
    const RECURRING_ORIGIN = 7;

    /**
     * Originated in a Payframe.
     *
     * @var int
     */
    const PAYFRAME_ORIGIN = 8;

    /**
     * Allowable values for the origin of the transaction.
     *
     * @link https://portal.payrix.com/docs/api#txnsPost
     * @var array
     */
    const VALID_ORIGIN_VALUES = [
        self::TERMINAL_ORIGIN,
        self::ECOMMERCE_ORIGIN,
        self::MAIL_ORDER_ORIGIN,
        self::APPLE_PAY_ORIGIN,
        self::SECURE_3D_SUCCESS_ORIGIN,
        self::SECURE_3D_ATTEMPT_ORIGIN,
        self::RECURRING_ORIGIN,
        self::PAYFRAME_ORIGIN,
    ];

    protected $liveEndpoint = 'https://api.payrix.com';
    protected $testEndpoint = 'https://test-api.payrix.com';

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('api_key');
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setApiKey($value)
    {
        return $this->setParameter('api_key', $value);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchant_id', $value);
    }

    /**
     * @return string
     */
    public function getExpirationDate()
    {
        return $this->getParameter('expiration_date');
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setExpirationDate($value)
    {
        return $this->setParameter('expiration_date', $value);
    }

    /**
     * Sets the origin.
     *
     * @param $value
     * @return AbstractRequest
     */
    public function setOrigin($value)
    {
        return $this->setParameter('origin', $value);
    }

    /**
     * Gets the origin.
     *
     * @return int
     */
    public function getOrigin()
    {
        return $this->getParameter('origin');
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function sendData($data)
    {
        $url = $this->getEndpoint();
        $headers = array(
            'APIKEY' => $this->getApiKey(),
            'Content-Type' => 'application/json',
        );
        $response = $this->httpClient->request('POST', $url, $headers, json_encode($data));

        $data = json_decode($response->getBody(), true);
        $data['status'] = $response->getStatusCode();
        $data['message'] = $response->getReasonPhrase();

        return $this->createResponse($data);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @param $data
     * @return AbstractResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
