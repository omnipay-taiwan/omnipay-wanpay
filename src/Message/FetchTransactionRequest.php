<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\WanPay\Traits\HasCommon;

class FetchTransactionRequest extends AbstractRequest
{
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

        return $this->mergeSign($data);
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint().'wxzfservice/orderquery',
            $data
        );

        $body = (string) $response->getBody();
        $responseData = json_decode($body, true);

        if (empty($responseData['status']) || $responseData['status'] !== '900') {
            throw new InvalidResponseException($body);
        }

        return $this->response = new FetchTransactionResponse($this, $responseData['data']);
    }
}
