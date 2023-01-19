<?php

namespace Omnipay\WanPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\WanPay\Message\AuthorizeRequest;

/**
 * WanPay Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'WanPay';
    }

    public function getDefaultParameters()
    {
        return [
            'key' => '',
            'testMode' => false,
        ];
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}
