<?php

namespace Omnipay\WanPay;

class Hasher
{
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function make(array $data)
    {
        if (array_key_exists('sign', $data)) {
            unset($data['sign']);
        }

        if (array_key_exists('key', $data)) {
            unset($data['key']);
        }

        $data = array_filter($data, static function ($value) {
            return ! empty($value);
        });

        ksort($data);
        $data['key'] = $this->secret;

        $body = '';
        foreach ($data as $key => $value) {
            $body .= $key.'='.$value.'&';
        }
        $body = substr($body, 0, -1);

        return strtoupper(md5($body));
    }
}
