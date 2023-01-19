<?php

namespace Omnipay\WanPay;

use Omnipay\Common\Item as BaseItem;

class Item extends BaseItem
{
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($url)
    {
        return $this->setParameter('currency', $url);
    }

    public function getUrl()
    {
        return $this->getParameter('url');
    }

    public function setUrl($url)
    {
        return $this->setParameter('url', $url);
    }

    public function toArray()
    {
        return [
            'productname' => $this->getName(),
            'price' => $this->getPrice(),
            'count' => $this->getQuantity(),
            'currency' => $this->getCurrency() ?? 'TWD',
            'platformurl' => $this->getUrl(),
        ];
    }
}
