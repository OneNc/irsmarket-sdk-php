<?php

namespace IRSMarket\API\Tests;

use PHPUnit\Framework\TestCase;
use IRSMarket\API\Response\Response;

class ResponseTest extends TestCase
{
    /**
     * Test successful response
     */
    public function testSuccessResponse()
    {
        $data = [
            'success' => true,
            'rc' => '00',
            'reff' => '1940164',
            'destination' => '085235716489',
            'productcode' => 'S5',
            'msg' => 'Success'
        ];

        $response = new Response($data);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isFailed());
        $this->assertEquals('00', $response->getCode());
        $this->assertEquals('1940164', $response->getReff());
        $this->assertEquals('085235716489', $response->getDestination());
        $this->assertEquals('S5', $response->getProductCode());
    }

    /**
     * Test pending response
     */
    public function testPendingResponse()
    {
        $data = [
            'success' => true,
            'rc' => '68',
            'reff' => '1940164',
            'msg' => 'under process'
        ];

        $response = new Response($data);

        $this->assertFalse($response->isSuccess());
        $this->assertTrue($response->isPending());
        $this->assertFalse($response->isFailed());
    }

    /**
     * Test failed response
     */
    public function testFailedResponse()
    {
        $data = [
            'success' => false,
            'rc' => '11',
            'msg' => 'Invalid API Key'
        ];

        $response = new Response($data);

        $this->assertFalse($response->isSuccess());
        $this->assertFalse($response->isPending());
        $this->assertTrue($response->isFailed());
        $this->assertEquals('11', $response->getCode());
    }

    /**
     * Test response with data
     */
    public function testResponseWithData()
    {
        $data = [
            'success' => true,
            'rc' => '00',
            'msg' => 'Success',
            'data' => [
                'membername' => 'Test Member',
                'balance' => '1000000'
            ]
        ];

        $response = new Response($data);

        $this->assertTrue($response->isSuccess());
        $responseData = $response->getData();
        $this->assertEquals('Test Member', $responseData['membername']);
        $this->assertEquals('1000000', $responseData['balance']);
    }

    /**
     * Test magic getter
     */
    public function testMagicGetter()
    {
        $data = [
            'success' => true,
            'rc' => '00',
            'custom_field' => 'custom_value'
        ];

        $response = new Response($data);

        $this->assertEquals('custom_value', $response->custom_field);
        $this->assertNull($response->nonexistent_field);
    }

    /**
     * Test code description
     */
    public function testCodeDescription()
    {
        $data = [
            'success' => false,
            'rc' => '61',
            'msg' => 'Insufficient Balance'
        ];

        $response = new Response($data);

        $this->assertEquals('Insufficient Balance', $response->getCodeDescription());
    }
}
