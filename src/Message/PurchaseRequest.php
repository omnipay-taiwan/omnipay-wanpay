<?php

namespace Omnipay\WanPay\Message;

use JsonException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\ItemBag;
use Omnipay\Common\ItemInterface;
use Omnipay\WanPay\Item;
use Omnipay\WanPay\Traits\HasAmount;
use Omnipay\WanPay\Traits\HasCommon;

class PurchaseRequest extends AbstractRequest
{
    use HasAmount;
    use HasCommon;

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

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('orgno', 'amount', 'transactionId', 'returnUrl');

        $data = [
            'orgno' => $this->getOrgNo(),
            'secondtimestamp' => $this->getSecondTimestamp(),
            'nonce_str' => $this->getNonceStr(),
            'total_fee' => $this->getAmount().'00',
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
        ];

        $data = array_filter($data, static function ($value) {
            return ! empty($value);
        });

        return $this->mergeSign($data);
    }

    /**
     * @throws InvalidResponseException
     * @throws JsonException
     */
    public function sendData($data)
    {
        $body = '';
        foreach ($data as $key => $value) {
            $body .= $key.'='.$value.'&';
        }
        $body = substr($body, 0, -1);

        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint().'wxzfservice/waporder',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            $body
        );

        $responseData = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($responseData['status'] !== '900') {
            throw new InvalidResponseException($responseData['info'], $responseData['status']);
        }

        return $this->response = new PurchaseResponse($this, $responseData['data']);
    }
}
