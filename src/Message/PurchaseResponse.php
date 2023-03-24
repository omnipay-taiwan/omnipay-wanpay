<?php

namespace Omnipay\WanPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data['html'];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getTransactionId()
    {
        return $this->data['out_trade_no'];
    }
}
