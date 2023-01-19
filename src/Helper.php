<?php

namespace Omnipay\WanPay;

class Helper
{
    public static function sign(array $data, $secret)
    {
        if (array_key_exists('sign', $data)) {
            unset($data['sign']);
        }

        ksort($data);
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = "$key=$value";
        }
        $result[] = "key=$secret";

        return strtoupper(md5(implode('&', $result)));
    }

    public static function random($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $characters[rand(0, $charactersLength - 1)];
        }

        return $str;
    }
}
