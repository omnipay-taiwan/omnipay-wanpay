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

        ksort($data);
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = "$key=$value";
        }
        $result[] = "key=$this->secret";

        return strtoupper(md5(implode('&', $result)));
    }
}
