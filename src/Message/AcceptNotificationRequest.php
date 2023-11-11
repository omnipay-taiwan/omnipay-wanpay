<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationRequest extends CompletePurchaseRequest implements NotificationInterface
{
    /**
     * @param  array  $data
     * @return AcceptNotificationResponse
     */
    public function sendData($data)
    {
        return $this->response = new AcceptNotificationResponse($this, $data);
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getNotificationResponse()->getTransactionId();
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getNotificationResponse()->getTransactionReference();
    }

    /**
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->getNotificationResponse()->getTransactionStatus();
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->getNotificationResponse()->getMessage();
    }

    /**
     * @return AcceptNotificationResponse
     */
    private function getNotificationResponse()
    {
        return ! $this->response ? $this->send() : $this->response;
    }

    /**
     * @return string
     */
    public function getReply()
    {
        return $this->getNotificationResponse()->getReply();
    }
}
