<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\WanPay\Traits\HasAmount;
use Omnipay\WanPay\Traits\HasCommon;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    /**
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        if (! hash_equals($this->httpRequest->request->get('sign', ''), $this->makeHash($data))) {
            throw new InvalidRequestException('Incorrect hash');
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function makeHash(array $data)
    {
        $temp = [];
        $keys = [
            'authcode', 'bankcard', 'nonce_str', 'orderdate', 'orgno', 'out_trade_no', 'result', 'status',
            'secondtimestamp', 'total_fee',
        ];

        foreach ($keys as $key) {
            $temp[$key] = $data[$key];
        }

        return parent::makeHash($temp);
    }
}
