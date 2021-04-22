<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\AbstractResponse;

abstract class AbstractRequest extends BaseAbstractRequest
{
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
    public function getMerchant()
    {
        return $this->getParameter('merchant');
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setMerchant($value)
    {
        return $this->setParameter('merchant', $value);
    }

    /**
     * @return integer|string
     */
    public function getTotal()
    {
        return $this->getParameter('total');
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setTotal($value)
    {
        return $this->setParameter('total', $value);
    }

    /**
     * @return integer|string
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
