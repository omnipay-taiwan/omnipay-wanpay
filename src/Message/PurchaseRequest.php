<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\ItemBag;
use Omnipay\Common\ItemInterface;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\WanPay\Helper;
use Omnipay\WanPay\Item;
use Omnipay\WanPay\Traits\HasWanPay;

class PurchaseRequest extends AbstractRequest
{
    use HasWanPay;

    public function getSecondTimestamp()
    {
        return $this->getParameter('secondtimestamp') ?? time();
    }

    public function setSecondTimestamp($value)
    {
        return $this->setParameter('secondtimestamp', $value);
    }

    public function getNonceStr()
    {
        return $this->getParameter('nonce_str');
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

    public function getOutTradeNo()
    {
        return $this->getTransactionId();
    }

    public function setOutTradeNo($value)
    {
        return $this->setTransactionId($value);
    }

    public function getType()
    {
        return $this->getParameter('type');
    }

    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    public function getBackUrl()
    {
        return $this->getNotifyUrl();
    }

    public function setBackUrl($value)
    {
        return $this->setNotifyUrl($value);
    }

    public function getBody()
    {
        return $this->getDescription();
    }

    public function setBody($value)
    {
        return $this->setDescription($value);
    }

    public function getIpoolId()
    {
        return $this->getParameter('ipoolid');
    }

    public function setIpoolId($value)
    {
        return $this->setParameter('ipoolid', $value);
    }

    public function getChannel()
    {
        return $this->getParameter('channel');
    }

    public function setChannel($value)
    {
        return $this->setParameter('channel', $value);
    }

    public function getPayCard()
    {
        return $this->getParameter('paycard');
    }

    public function setPayCard($value)
    {
        return $this->setParameter('paycard', $value);
    }

    public function getCardExpiredDate()
    {
        return $this->getParameter('cardexpireddate');
    }

    public function setCardExpiredDate($value)
    {
        return $this->setParameter('cardexpireddate', $value);
    }

    public function getProductList()
    {
        $itemBag = $this->getItems();

        if (! $itemBag) {
            return $this->getParameter('productlist');
        }

        $items = array_map(static function (Item $item) {
            return $item->toArray();
        }, $itemBag->all());

        return json_encode($items);
    }

    public function setProductList($value)
    {
        return $this->setParameter('productlist', $value);
    }

    public function getMemberNo()
    {
        return $this->getParameter('memberno');
    }

    public function setMemberNo($value)
    {
        return $this->setParameter('memberno', $value);
    }

    public function setItems($items)
    {
        $itemBag = new ItemBag();
        foreach ($items as $item) {
            $itemBag->add(new Item($item instanceof ItemInterface ? $item->getParameters() : $item));
        }

        return $this->setParameter('items', $itemBag);
    }

    public function getData()
    {
        $this->validate('orgno', 'amount', 'transactionId', 'returnUrl');

        $data = array_filter([
            'orgno' => $this->getOrgNo(),
            'secondtimestamp' => $this->getSecondTimestamp(),
            'nonce_str' => $this->getNonceStr() ?? Helper::random(),
            'total_fee' => (int) $this->getAmount(),
            'out_trade_no' => $this->getTransactionId(),
            'type' => $this->getType() ?? 'AUTH_3DTRXTOKEN',
            'returnurl' => $this->getReturnUrl(),
            'backurl' => $this->getNotifyUrl(),
            'body' => $this->getDescription(),
            'ipoolid' => $this->getIpoolId() ?? '10703',
            'channel' => $this->getChannel(),
            'paycard' => $this->getPayCard(),
            'cardexpireddate' => $this->getCardExpiredDate(),
            'productlist' => $this->getProductList(),
            'currency' => $this->getCurrency(),
            'memberno' => $this->getMemberNo(),
        ], static function ($value) {
            return ! empty($value);
        });

        return array_merge([
            'orgno' => '',
            'secondtimestamp' => '',
            'nonce_str' => '',
            'sign' => Helper::sign($data, $this->getKey()),
        ], $data);
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
