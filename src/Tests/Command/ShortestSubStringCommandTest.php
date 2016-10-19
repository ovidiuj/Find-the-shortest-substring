<?php
namespace Tests;


use Command\ShortestSubStringCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class RenderSpaceCommandTest
 * @package Tests
 */
class ShortestSubStringCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    private $app;

    /**
     * @var
     */
    private $service;

    /**
     *
     */
    public function setUp()
    {
        $this->service = $this->getMock('Services\ShortestSubString');
    }

    /**
     *
     */
    public function testCommand()
    {
        $app = new Application();
        $app->add(new ShortestSubStringCommand($this->service));
        $command = $app->find('get-shortest-sub-sctring');

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'characters' => "EOT",
            'stream' => "THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG"
        ));

        $this->assertEquals("The length of the shortest substring containing all search characters is: \nThe match would be here: \n", $tester->getDisplay());
    }

    /**
     *
     */
    public function testCommandWhenStreamIsNotPiped()
    {
        $app = new Application();
        $app->add(new ShortestSubStringCommand($this->service));
        $command = $app->find('get-shortest-sub-sctring');

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'characters' => "EOT"
        ));

        $this->assertEquals("Please pipe a file content to STDIN or add the string  as an argument\n", $tester->getDisplay());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRenderSpaceWhenMissingCharacters()
    {
        $app = new Application();
        $app->add(new ShortestSubStringCommand($this->service));
        $command = $app->find('get-shortest-sub-sctring');

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName()
        ));
    }
}