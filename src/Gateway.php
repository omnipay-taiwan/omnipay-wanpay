<?php

namespace Omnipay\WanPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\WanPay\Message\FetchTransactionRequest;
use Omnipay\WanPay\Message\PurchaseRequest;
use Omnipay\WanPay\Traits\HasWanPay;

/**
 * WanPay Gateway
 */
class Gateway extends AbstractGateway
{
    use HasWanPay;

    public function getName()
    {
        return 'WanPay';
    }

    public function getDefaultParameters()
    {
        return [
            'orgno' => '21008024',
            'key' => 'QGbZvggxNdGgMUnp',
            'testMode' => false,
        ];
    }

    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }
}
