<?php

namespace PHPSTORM_META {

    /** @noinspection PhpIllegalArrayKeyTypeInspection */
    /** @noinspection PhpUnusedLocalVariableInspection */
    $STATIC_METHOD_TYPES = [
      \Omnipay\Omnipay::create('') => [
        'Payrix' instanceof \Omnipay\Payrix\Gateway,
      ],
      \Omnipay\Common\GatewayFactory::create('') => [
        'Payrix' instanceof \Omnipay\Payrix\Gateway,
      ],
    ];
}
