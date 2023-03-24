<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->getCode() === '0000';
    }

    public function getCode()
    {
        return $this->data['status'];
    }

    public function getTransactionId()
    {
        return $this->data['out_trade_no'];
    }

    public function getMessage()
    {
        return $this->data['result'];
    }
}
