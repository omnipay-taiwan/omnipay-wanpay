<?php

namespace Omnipay\WanPay\Tests;

use Omnipay\Common\Message\NotificationInterface;
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
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase([
            'secondtimestamp' => 1674157848,
            'total_fee' => 100,
            'out_trade_no' => '100020170117111010101',
            'returnurl' => 'https://foo.bar/returnurl',
            'description' => '商品',
        ])->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('https://ixmfree.net/index.php?g=wxuser&m=payment&a=fast', $response->getRedirectUrl());
        self::assertEquals('GET', $response->getRedirectMethod());
        self::assertEquals('100020170117111010101', $response->getTransactionId());

        $request = $this->getMockClient()->getLastRequest();
        $requestBody = [];
        parse_str((string) $request->getBody(), $requestBody);

        self::assertEquals([
            'orgno' => '21008024',
            'secondtimestamp' => '1674157848',
            'nonce_str' => '78cc54e7',
            'sign' => 'DE642DE89F22E1B293E7FB816A2F9252',
            'total_fee' => '10000',
            'out_trade_no' => '100020170117111010101',
            'type' => 'AUTH_3DTRXTOKEN',
            'returnurl' => 'https://foo.bar/returnurl',
            'ipoolid' => '10703',
            'body' => '商品',
        ], $requestBody);
    }

    public function testCompletePurchase()
    {
        $response = 'https://member.healthchain.com.tw/MHC01SSV2TEST/Checkout/CheckOutShowResultWangPay.aspx?authcode=154566&bankcard=552199******1898&nonce_str=46444248&orgno=21001719&out_trade_no=040911560243087HRC&result=核准&secondtimestamp=1586404583&status=0000&total_fee=100&orderdate=2020-04-09 11:56:02&trxtoken=&storename=旺旺電子商務-快點付&details=固定金額 免收件地址&payername=0409&payermobile=0409&payeremail=0409&sign=7F015EB2B4674CD76C9C62090B6DF3E2';
        $parsed = parse_url($response);
        $options = [];
        parse_str($parsed['query'], $options);

        $response = $this->gateway->completePurchase($options)->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('0000', $response->getCode());
        self::assertEquals('040911560243087HRC', $response->getTransactionId());
        self::assertEquals('核准', $response->getMessage());
    }

    public function testAcceptNotification()
    {
        $response = 'https://member.healthchain.com.tw/MHC01SSV2TEST/Checkout/CheckOutShowResultWangPay.aspx?authcode=154566&bankcard=552199******1898&nonce_str=46444248&orgno=21001719&out_trade_no=040911560243087HRC&result=核准&secondtimestamp=1586404583&status=0000&total_fee=100&orderdate=2020-04-09 11:56:02&trxtoken=&storename=旺旺電子商務-快點付&details=固定金額 免收件地址&payername=0409&payermobile=0409&payeremail=0409&sign=C720CD30C24DB5A372A33B90D2906C46';
        $parsed = parse_url($response);
        $options = [];
        parse_str($parsed['query'], $options);

        $request = $this->gateway->acceptNotification($options);
        self::assertEquals('核准', $request->getMessage());
        self::assertEquals(NotificationInterface::STATUS_COMPLETED, $request->getTransactionStatus());

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('040911560243087HRC', $response->getTransactionId());
        self::assertEquals('0000', $response->getCode());
        self::assertEquals('success', $response->getReply());
    }

    public function testFetchTransaction()
    {
        $this->setMockHttpResponse('FetchTransaction.txt');

        $response = $this->gateway->fetchTransaction([
            'secondtimestamp' => 1674157848,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
        ])->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('0000', $response->getCode());
        self::assertEquals('1000201701201708041015eNuBrl6P', $response->getTransactionId());
    }
}
