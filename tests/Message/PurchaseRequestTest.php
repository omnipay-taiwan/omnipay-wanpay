<?php

namespace Omnipay\WanPay\Tests\Message;

use Omnipay\Tests\TestCase;
use Omnipay\WanPay\Item;
use Omnipay\WanPay\Message\PurchaseRequest;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    private $initialize = [
        'orgno' => '21008024',
        'key' => 'QGbZvggxNdGgMUnp',
    ];

    public function setUp(): void
    {
        parent::setUp();

        $options = [
            'secondtimestamp' => 1674157848,
            'total_fee' => 100,
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'type' => 'AUTH_3DTRXTOKEN',
            'returnurl' => 'https://foo.bar/returnurl',
            'backurl' => 'https://foo.bar/backurl',
            'body' => 'body',
            'ipoolid' => '10703',
            'channel' => 'ec03',
            'paycard' => '4311-2222-2222-2222',
            'cardexpireddate' => '2301',
            'memberno' => '15335',
        ];

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array_merge($this->initialize, $options));
        $this->request->setItems([
            new Item([
                'name' => 'nike',
                'price' => 300,
                'quantity' => 1,
                'url' => 'https://www.amazon.cn/',
            ]),
            new Item([
                'name' => 'gola',
                'price' => 300,
                'quantity' => 1,
                'url' => 'https://www.amazon.cn/',
            ]),
        ]);
    }

    public function testGetData(): void
    {
        $productList = '[{"productname":"nike","price":300,"count":1,"currency":"TWD","platformurl":"https:\/\/www.amazon.cn\/"},{"productname":"gola","price":300,"count":1,"currency":"TWD","platformurl":"https:\/\/www.amazon.cn\/"}]';

        self::assertEquals([
            'orgno' => '21008024',
            'secondtimestamp' => 1674157848,
            'nonce_str' => '8c90afc4',
            'sign' => '4FADE949618C9800F707DC83A227C91A',
            'total_fee' => '10000',
            'out_trade_no' => '1000201701201708041015eNuBrl6P',
            'type' => 'AUTH_3DTRXTOKEN',
            'returnurl' => 'https://foo.bar/returnurl',
            'backurl' => 'https://foo.bar/backurl',
            'body' => 'body',
            'ipoolid' => '10703',
            'channel' => 'ec03',
            'paycard' => '4311-2222-2222-2222',
            'cardexpireddate' => '2301',
            'productlist' => $productList,
            'memberno' => '15335',
        ], $this->request->getData());
    }
}
