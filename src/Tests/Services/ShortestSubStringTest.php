<?php

namespace Tests\Services;


use Services\ShortestSubString;

class ShortestSubStringTest extends \PHPUnit_Framework_TestCase
{
    private $service;

    private $stream = 'THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG';

    private $searchCharacters = 'EOT';

    public function setUp()
    {
        parent::setUp();
        $this->service = new ShortestSubString();
    }

    public function testStream()
    {
        $this->service->setStream($this->stream);
        $this->assertEquals($this->stream, $this->service->getStream());
    }

    public function testSearchCharacters()
    {
        $this->service->setSearchCharacters($this->searchCharacters);
        $this->assertEquals($this->searchCharacters, $this->service->getSearchCharacters());
    }

    public function testGetMinimumSubString()
    {

        $this->service->setStream($this->stream);
        $this->service->setSearchCharacters($this->searchCharacters);
        $this->service->getMinimumSubString();
        $found = substr($this->stream, $this->service->getStartPointer(), $this->service->getShortestSubStringLength());

        $this->assertEquals(5, $this->service->getShortestSubStringLength());
        $this->assertEquals(21, $this->service->getStartPointer());
        $this->assertEquals(25, $this->service->getEndPointer());
        $this->assertEquals(preg_replace("/($found)/i", "<b><u>$0</u></b>", $this->stream), $this->service->getHighlightedStream());
    }
}