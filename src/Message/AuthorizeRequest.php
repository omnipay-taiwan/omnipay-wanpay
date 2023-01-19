<?php

namespace Omnipay\WanPay\Message;

use Omnipay\WanPay\Traits\HasWanPay;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    use HasWanPay;

    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        return $this->getBaseData();
    }
}
