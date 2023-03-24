<?php

namespace Omnipay\WanPay\Message;

use Omnipay\WanPay\Hasher;
use Omnipay\WanPay\Traits\HasCommon;
use Omnipay\WanPay\Traits\HasWanPay;

class FetchTransactionRequest extends AbstractRequest
{
    use HasWanPay;
    use HasCommon;

    public function getData()
    {
        $this->validate('transactionId');

        $data = [
            'orgno' => $this->getOrgNo(),
            'secondtimestamp' => $this->getSecondTimestamp(),
            'nonce_str' => $this->getNonceStr(),
            'out_trade_no' => $this->getTransactionId(),
        ];

        $data = array_filter($data, static function ($value) {
            return ! empty($value);
        });

        $hasher = new Hasher($this->getKey());

        return array_merge([
            'orgno' => '',
            'secondtimestamp' => '',
            'nonce_str' => '',
            'sign' => $hasher->make($data),
        ], $data);
    }

    public function sendData($data)
    {
        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint().'wxzfservice/orderquery',
            $data
        );

        return $this->response = new FetchTransactionResponse(
            $this,
            json_decode((string) $response->getBody(), true)
        );
    }
}
