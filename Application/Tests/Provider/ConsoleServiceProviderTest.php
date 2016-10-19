<?php
namespace Application\Tests\Provider;

use Application\Provider\ConsoleServiceProvider;
use Symfony\Component\Console\Command\Command;

class ConsoleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    private $command;
    private $app;

    public function setUp()
    {
        parent::setUp();
        $this->command = new Command('test');
        $router = $this->getMock('Application\Routing\Router');
        $this->app = $this->getMock('Application\Application', [], [$router]);
    }

    public function testConstructor()
    {
        $provider = new ConsoleServiceProvider($this->command);
        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $provider->getConsole());
    }

    public function testRegister()
    {
        $provider = new ConsoleServiceProvider($this->command);
        $provider->register($this->app);
        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $provider->getConsole());
        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $this->app->container['console']);
    }
}