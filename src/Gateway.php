<?php

namespace Omnipay\WanPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\WanPay\Message\AcceptNotificationRequest;
use Omnipay\WanPay\Message\CompletePurchaseRequest;
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

    public function completePurchase(array $options = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    /**
     * @return RequestInterface|NotificationInterface
     */
    public function acceptNotification(array $options = [])
    {
        return $this->createRequest(AcceptNotificationRequest::class, $options);
    }

    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }
}
