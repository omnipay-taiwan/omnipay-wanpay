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

    public function testCompletePurchase()
    {
        $response = 'https://member.healthchain.com.tw/MHC01SSV2TEST/Checkout/CheckOutShowResultWangPay.aspx?authcode=154566&bankcard=552199******1898&nonce_str=46444248&orgno=21001719&out_trade_no=040911560243087HRC&result=核准&secondtimestamp=1586404583&status=0000&total_fee=100&orderdate=2020-04-09 11:56:02&trxtoken=&storename=旺旺電子商務-快點付&details=固定金額 免收件地址&payername=0409&payermobile=0409&payeremail=0409&sign=C720CD30C24DB5A372A33B90D2906C46';
        $parsed = parse_url($response);
        $options = [];
        parse_str($parsed['query'], $options);

        $response = $this->gateway->completePurchase($options)->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('0000', $response->getCode());
        self::assertEquals('040911560243087HRC', $response->getTransactionId());
        self::assertEquals('核准', $response->getMessage());
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
        self::assertEquals('1000201701201708041015eNuBrl6P', $response->getTransactionId());
    }
}
