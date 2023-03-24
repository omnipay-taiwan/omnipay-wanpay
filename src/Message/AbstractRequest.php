<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    public function getEndpoint()
    {
        return 'https://api.wan-pay.com/';
    }
}
