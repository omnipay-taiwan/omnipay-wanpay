<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\WanPay\Traits\HasAmount;
use Omnipay\WanPay\Traits\HasCommon;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasAmount;
    use HasCommon;

    public function getStatus()
    {
        return $this->getParameter('status');
    }

    public function setStatus($value)
    {
        return $this->setParameter('status', $value);
    }

    public function getResult()
    {
        return $this->getParameter('result');
    }

    public function setResult($value)
    {
        return $this->setParameter('result', $value);
    }

    public function getOrderdate()
    {
        return $this->getParameter('orderdate');
    }

    public function setOrderdate($value)
    {
        return $this->setParameter('orderdate', $value);
    }

    public function getAuthcode()
    {
        return $this->getParameter('authcode');
    }

    public function setAuthcode($value)
    {
        return $this->setParameter('authcode', $value);
    }

    public function getBankcard()
    {
        return $this->getParameter('bankcoard');
    }

    public function setBankcard($value)
    {
        return $this->setParameter('bankcoard', $value);
    }

    public function getPeriods()
    {
        return $this->getParameter('periods');
    }

    public function setPeriods($value)
    {
        return $this->setParameter('periods', $value);
    }

    public function getData()
    {
        return [
            'orgno' => $this->getOrgNo(),
            'secondtimestamp' => $this->getSecondTimestamp(),
            'nonce_str' => $this->getNonceStr(),
            'sign' => $this->getSign(),
            'out_trade_no' => $this->getTransactionId(),
            'status' => $this->getStatus(),
            'result' => $this->getResult(),
            'total_fee' => $this->getAmount(),
            'orderdate' => $this->getOrderdate(),
            'authcode' => $this->getAuthcode(),
            'bankcard' => $this->getBankcard(),
            'periods' => $this->getPeriods(),
        ];
    }

    /**
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        if ($data['sign'] !== $this->makeHash($data)) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
