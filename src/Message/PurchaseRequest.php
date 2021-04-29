<?php

namespace Omnipay\Payrix\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Payrix\Message\AbstractRequest;
use Omnipay\Payrix\Message\PurchaseResponse;

class PurchaseRequest extends AbstractRequest
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
     * American Express
     *
     * @var int
     */
    const AMEX_PAYMENT_METHOD = 1;

    /**
     * Visa
     *
     * @var int
     */
    const VISA_PAYMENT_METHOD = 2;

    /**
     * MasterCard
     *
     * @var int
     */
    const MASTERCARD_PAYMENT_METHOD = 3;

    /**
     * Diners Club
     *
     * @var int
     */
    const DINERS_CLUB_PAYMENT_METHOD = 4;

    /**
     * Discover
     *
     * @var int
     */
    const DISCOVER_PAYMENT_METHOD = 5;

    /**
     * List of valid credit_card brands to payment methods
     *
     * @var array
     */
    protected $supported_brands = array(
        CreditCard::BRAND_AMEX => self::AMEX_PAYMENT_METHOD,
        CreditCard::BRAND_VISA => self::VISA_PAYMENT_METHOD,
        CreditCard::BRAND_MASTERCARD => self::MASTERCARD_PAYMENT_METHOD,
        CreditCard::BRAND_DINERS_CLUB => self::DINERS_CLUB_PAYMENT_METHOD,
        CreditCard::BRAND_DISCOVER => self::DISCOVER_PAYMENT_METHOD
    );

    /**
     * Gets the supported credit_card brands.
     *
     * @return array
     */
    public function getSupportedBrands()
    {
        return $this->supported_brands;
    }

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
        $this->validate('api_key', 'merchant_id', 'origin', 'amount', 'order', 'description');

        $data = array(
            'merchant' => $this->getMerchantId(),
            'signature' => 0,
            'pin' => 0,
            'origin' => $this->getOrigin(),
            'total' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        );

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
                'method' => $this->getCardPaymentMethod(),
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
     * Gets the card payment method.
     *
     * @return int
     */
    protected function getCardPaymentMethod()
    {
        $creditCard = $this->getCard();
        $creditCardBrand = $creditCard->getBrand();

        return isset($this->supported_brands, $creditCardBrand)
            ? $this->supported_brands[$creditCardBrand]
            : 0;
    }

    /**
     * @param $data
     * @return TransactionResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
