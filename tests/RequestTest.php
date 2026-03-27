<?php

namespace IRSMarket\API\Tests;

use PHPUnit\Framework\TestCase;
use IRSMarket\API\Config;
use IRSMarket\API\Request\TransactionRequest;
use IRSMarket\API\Request\BalanceRequest;

class RequestTest extends TestCase
{
    /**
     * Test TransactionRequest building POST data
     */
    public function testTransactionRequestBuildData()
    {
        $request = new TransactionRequest(
            'test_api_key',
            'TSEL_5000',
            'TRX_001',
            '081234567890'
        );

        $request->setApiSecret('test_api_secret');
        $request->setMaxPrice(5500);

        $data = $request->buildData();

        $this->assertEquals('test_api_key', $data['apikey']);
        $this->assertEquals('TSEL_5000', $data['productcode']);
        $this->assertEquals('TRX_001', $data['trxid']);
        $this->assertEquals('081234567890', $data['customerno']);
        $this->assertEquals('test_api_secret', $data['apisecret']);
        $this->assertEquals('5500', $data['maxprice']);
        $this->assertFalse(isset($data['sign']));
    }

    /**
     * Test TransactionRequest with signature
     */
    public function testTransactionRequestWithSignature()
    {
        $request = new TransactionRequest(
            'test_api_key',
            'TSEL_5000',
            'TRX_001',
            '081234567890'
        );

        $request->generateSignature('test_api_secret');

        $data = $request->buildData();

        $this->assertEquals('test_api_key', $data['apikey']);
        $this->assertFalse(isset($data['apisecret']));
        $this->assertTrue(isset($data['sign']));
        $this->assertEquals(md5('test_api_keytest_api_secretTRX_001'), $data['sign']);
    }

    /**
     * Test TransactionRequest GET query
     */
    public function testTransactionRequestBuildQuery()
    {
        $request = new TransactionRequest(
            'test_api_key',
            'TSEL_5000',
            'TRX_001',
            '081234567890'
        );

        $request->setApiSecret('test_api_secret');

        $query = $request->buildQuery();

        $this->assertEquals('test_api_key', $query['apikey']);
        $this->assertEquals('TSEL_5000', $query['productcode']);
        $this->assertEquals('test_api_secret', $query['apisecret']);
    }

    /**
     * Test BalanceRequest
     */
    public function testBalanceRequest()
    {
        $request = new BalanceRequest('test_api_key', 'test_api_secret');

        $data = $request->buildData();

        $this->assertEquals('test_api_key', $data['apikey']);
        $this->assertEquals('test_api_secret', $data['apisecret']);
    }

    /**
     * Test Config response codes
     */
    public function testConfigResponseCodes()
    {
        $this->assertEquals('Success', Config::getResponseCodeMessage('00'));
        $this->assertEquals('Invalid API Key', Config::getResponseCodeMessage('11'));
        $this->assertEquals('Insufficient Balance', Config::getResponseCodeMessage('61'));
    }

    /**
     * Test Config base URL
     */
    public function testConfigBaseUrl()
    {
        $this->assertEquals('https://api.irsmarket.com/v1', Config::getBaseUrl());
    }
}
