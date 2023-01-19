<?php

namespace PHPSTORM_META {

    /** @noinspection PhpIllegalArrayKeyTypeInspection */
    /** @noinspection PhpUnusedLocalVariableInspection */
    $STATIC_METHOD_TYPES = [
      \Omnipay\Omnipay::create('') => [
        'WanPay' instanceof \Omnipay\WanPay\Gateway,
      ],
      \Omnipay\Common\GatewayFactory::create('') => [
        'WanPay' instanceof \Omnipay\WanPay\Gateway,
      ],
    ];
}
