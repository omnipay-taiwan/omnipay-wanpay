<?php

namespace Omnipay\WanPay\Tests;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\WanPay\Gateway;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize([
            'orgno' => '21008024',
            'key' => 'QGbZvggxNdGgMUnp',
        ]);
    }

    public function testPurchase()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->gateway->purchase([
            'secondtimestamp' => 1674157848,
            'nonce_str' => 'nonce',
            'sign' => 'abc',
            'total_fee' => 100,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'returnurl' => 'https://foo.bar/returnurl',
        ])->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('https://api.wan-pay.com/wxzfservice/waporder/', $response->getRedirectUrl());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals([
            'orgno' => '21008024',
            'secondtimestamp' => 1674157848,
            'nonce_str' => 'nonce',
            'sign' => '7052EFB30EA3421DC450DF1966002D79',
            'total_fee' => 100,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'type' => 'AUTH_3DTRXTOKEN',
            'returnurl' => 'https://foo.bar/returnurl',
            'ipoolid' => '10703',
        ], $response->getRedirectData());
    }
}
