<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\WanPay\Hasher;
use Omnipay\WanPay\Traits\HasWanPay;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use HasWanPay;

    public function getEndpoint()
    {
        return 'https://api.wan-pay.com/';
    }

    protected function mergeSign(array $data)
    {
        return array_merge([
            'orgno' => '',
            'secondtimestamp' => '',
            'nonce_str' => '',
            'sign' => $this->makeHash($data),
        ], $data);
    }

    private function makeHash(array $data)
    {
        $hasher = new Hasher($this->getKey());

        return $hasher->make($data);
    }
}
