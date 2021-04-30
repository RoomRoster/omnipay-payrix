<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Payrix\Message\AbstractRequest;
use Omnipay\Payrix\Message\TransactionResponse;

class TransactionCreateRequest extends AbstractRequest
{
    /**
     * Credit Card Only: Sale Transaction.
     * This is the most common type of Transaction, it processes a sale and charges the customer
     *
     * @var int
     */
    const CC_SALE_TYPE = 1;

    /**
     * Echeck Only: Echeck Sale Transaction. Sale Transaction for ECheck payment.
     *
     * @var int
     */
    const ECHECK_SALE_TYPE = 7;

    /**
     * CVV was not provided.
     *
     * @var string
     */
    const CVV_NOT_PROVIDED_STATUS = 'notProvided';

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
        $this->validate('amount');

        $data = array(
            'merchant' => $this->getMerchantId(),
            'signature' => 0,
            'pin' => 0,
            'origin' => $this->getOrigin(),
            'currency' => $this->getCurrency(),
        );

        /**
         * The total amount of this Transaction.
         * This field is specified as an integer in cents.
         *
         * @link https://portal.payrix.com/docs/api#txnsPost
         */
        $data['total'] = $this->getAmountInteger();

        $transactionId = $this->getTransactionId();
        if (! empty($transactionId)) {
            $data['order'] = $transactionId;
        }

        $description = $this->getDescription();
        if (! empty($description)) {
            $data['description'] = $description;
        }

        $clientIp = $this->getClientIp();
        if (! empty($clientIp)) {
            $data['clientIp'] = $clientIp;
        }

        $this->addPayment($data);
        $this->addBillingData($data);

        return $data;
    }

    /**
     * Adds the payment information.
     *
     * @param array $data
     */
    protected function addPayment(array &$data)
    {
        /** @var CreditCard $creditCard */
        if ($creditCard = $this->getCard()) {
            // Validate the standard credit card number.
            $this->validate('card');

            $creditCard->validate();

            $data['type'] = static::CC_SALE_TYPE;
            $data['expiration'] = $creditCard->getExpiryDate('my');
            $data['payment'] = array(
                'number' => $creditCard->getNumber()
            );

            if (! empty($creditCard->getCvv())) {
                $data['payment']['cvv'] = $creditCard->getCvv();
            } else {
                $data['cvvStatus'] = static::CVV_NOT_PROVIDED_STATUS;
            }
        }
    }

    /**
     * Adds the billing data.
     *
     * @param array $data
     */
    protected function addBillingData(array &$data)
    {
        /** @var CreditCard $creditCard */
        if ($card = $this->getCard()) {
            // A card is present, so include billing/shipping details
            $data['email'] = $card->getEmail();

            $data['first'] = $card->getBillingFirstName();
            $data['last'] = $card->getBillingLastName();
            $data['company'] = $card->getBillingCompany();
            $data['address1'] = $card->getBillingAddress1();
            $data['address2'] = $card->getBillingAddress2();
            $data['city'] = $card->getBillingCity();
            $data['state'] = $card->getBillingState();
            $data['zip'] = $card->getBillingPostcode();
            $data['country'] = $card->getBillingCountry();
            $data['phone'] = $card->getBillingPhone();
        }
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
