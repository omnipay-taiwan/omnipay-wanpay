<?php

namespace Omnipay\WanPay\Traits;

trait HasCommon
{
    public function getSecondTimestamp()
    {
        return $this->getParameter('secondtimestamp') ?? time();
    }

    public function setSecondTimestamp($value)
    {
        return $this->setParameter('secondtimestamp', $value);
    }

    public function getOutTradeNo()
    {
        return $this->getTransactionId();
    }

    public function setOutTradeNo($value)
    {
        return $this->setTransactionId($value);
    }

    public function getNonceStr()
    {
        $nonceStr = $this->getParameter('nonce_str');

        return ! empty($nonceStr) ? $nonceStr : $this->getNonceHashStr();
    }

    public function setNonceStr($value)
    {
        return $this->setParameter('nonce_str', $value);
    }

    public function getSign()
    {
        return $this->getParameter('sign');
    }

    public function setSign($value)
    {
        return $this->setParameter('sign', $value);
    }

    public function getTotalFee()
    {
        return $this->getAmount();
    }

    public function setTotalFee($value)
    {
        return $this->setAmount($value);
    }

    private function getNonceHashStr()
    {
        return substr(md5($this->getTransactionId()), 0, 8);
    }
}
