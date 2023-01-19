<?php

namespace Omnipay\WanPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\WanPay\Message\AuthorizeRequest;
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


    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}
