<?php

namespace Omnipay\WanPay\Traits;

trait HasWanPay
{
    public function getOrgNo()
    {
        return $this->getParameter('orgno');
    }

    public function setOrgNo($value)
    {
        return $this->setParameter('orgno', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }
}
