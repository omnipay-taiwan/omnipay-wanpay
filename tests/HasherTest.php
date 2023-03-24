<?php

namespace Omnipay\WanPay\Tests;

use Omnipay\WanPay\Hasher;
use PHPUnit\Framework\TestCase;

class HasherTest extends TestCase
{
    public function testGenerate(): void
    {
        $hasher = new Hasher('sbgSpVkimGvNgRnu');
        $data = [
            'backurl' => 'https://test.com',
            'body' => 'good',
            'currency' => 'USD',
            'nonce_str' => '84778745',
            'orgno' => '21004280',
            'out_trade_no' => '20230327093049',
            'returnurl' => 'https://test.com',
            'secondtimestamp' => '1679880649',
            't0t1' => 'T1',
            'total_fee' => '1',
            'key' => 'sbgSpVkimGvNgRnu',
        ];

        self::assertEquals('2100EA95EFC3BEAF774EF536FAB8B132', $hasher->make($data));
    }
}
