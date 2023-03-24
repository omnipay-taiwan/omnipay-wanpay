<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationResponse extends CompletePurchaseResponse implements NotificationInterface
{
    /**
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->isSuccessful() ? self::STATUS_COMPLETED : self::STATUS_FAILED;
    }

    /**
     * @return string
     */
    public function getReply()
    {
        return 'success';
    }
}
