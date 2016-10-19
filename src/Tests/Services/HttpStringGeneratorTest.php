<?php

namespace Tests\Services;


use GuzzleHttp\Client;
use Services\HttpStringGenerator;

class HttpStringGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $stringGenerator;

    public function setUp()
    {
        parent::setUp();
        $this->stringGenerator = new HttpStringGenerator(new Client(), 'https://test.org');
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('Application\ServiceInterface', $this->stringGenerator);
        $this->assertInstanceOf('GuzzleHttp\Client', $this->stringGenerator->getHttpClient());
        $this->assertEquals('https://test.org', $this->stringGenerator->getRequestUrl());
        $this->assertInternalType('array', $this->stringGenerator->getParameters());
    }

    public function testGenerate()
    {
        $response = $this->getMock('GuzzleHttp\Psr7\Response');
        $httpClient = $this->getMock('GuzzleHttp\Client');

        $response->expects($this->once())
            ->method("getStatusCode")
            ->will($this->returnValue(200));

        $httpClient->expects($this->once())
            ->method("request")
            ->with('GET', 'https://test.org?' . http_build_query($this->stringGenerator->getParameters()))
            ->will($this->returnValue($response));

        $stringGenerator = new HttpStringGenerator($httpClient, 'https://test.org');
        $stringGenerator->generate();
    }


}