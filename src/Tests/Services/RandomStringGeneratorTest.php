<?php

namespace Tests\Services;


use Services\RandomStringGenerator;

class RandomStringGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $stringGenerator;

    public function setUp()
    {
        parent::setUp();
        $this->stringGenerator = new RandomStringGenerator();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('Application\ServiceInterface', $this->stringGenerator);
        $this->assertInternalType('string', $this->stringGenerator->getAlphabet());
    }

    public function testSetLength()
    {
        $this->stringGenerator->setLength(200);
        $this->assertEquals(200, $this->stringGenerator->getLength());
    }

    public function testGenerate()
    {
        $stream = $this->stringGenerator->generate();
        $this->assertInternalType('string', $stream);
        $this->assertEquals($this->stringGenerator->getLength(), strlen($stream));
    }
}