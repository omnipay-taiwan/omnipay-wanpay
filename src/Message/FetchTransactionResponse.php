<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class FetchTransactionResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->getCode() === '0000';
    }

    public function getCode()
    {
        return $this->getResult('orderstatus');
    }

    public function getTransactionId()
    {
        return $this->getResult('out_trade_no');
    }

    public function getResult($key)
    {
        $data = $this->getData();

        if (empty($data['data']) || empty($data['data'][$key])) {
            return null;
        }

        return $data['data'][$key];
    }
}
