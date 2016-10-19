<?php
namespace Application\Provider;


use Application\ContainerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class ConsoleServiceProvider
 * @package Application\Provider
 */
class ConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Command
     */
    protected $console;

    /**
     * ConsoleServiceProvider constructor.
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->console = $command;
    }

    /**
     * @param ContainerInterface $app
     */
    public function register(ContainerInterface $app)
    {
        $app->container['console'] = $this->console;
    }

    /**
     * @return Command
     */
    public function getConsole() 
    {
        return $this->console;
    }
}