<?php

namespace Omnipay\WanPay\Tests;

use Omnipay\WanPay\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testGenerate(): void
    {
        $data = [
            't0t1' => 'T1',
            'secondtimestamp' => '1489215551',
            'orgno' => '1265',
            'total_fee' => '8888',
            'nonce_str' => '71669965',
            'body' => 'good',
        ];

        self::assertEquals(
            'CF949AE048C0C3B23D5FADFE9495B319',
            Helper::sign($data, 'n12bQ9Ew1_2342X4rEcO')
        );
    }
}
