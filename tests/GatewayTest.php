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
        $response = $this->gateway->purchase([
            'secondtimestamp' => 1674157848,
            'total_fee' => 100,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'returnurl' => 'https://foo.bar/returnurl',
        ])->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('https://api.wan-pay.com/wxzfservice/waporder', $response->getRedirectUrl());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals([
            'orgno' => '21008024',
            'secondtimestamp' => 1674157848,
            'nonce_str' => '8c90afc4',
            'sign' => 'AC2B51CAA7996D8CC95A11080AC6F011',
            'total_fee' => '10000',
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'type' => 'AUTH_3DTRXTOKEN',
            'returnurl' => 'https://foo.bar/returnurl',
            'ipoolid' => '10703',
        ], $response->getRedirectData());
    }

    public function testFetchTransaction()
    {
        $this->setMockHttpResponse('FetchTransaction.txt');

        $response = $this->gateway->fetchTransaction([
            'secondtimestamp' => 1674157848,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
        ])->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('0000', $response->isSuccessful());
    }
}
