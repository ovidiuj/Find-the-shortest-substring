<?php

namespace Command;


use Application\ServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShortestSubStringCommand
 * @package Command
 */
class ShortestSubStringCommand extends Command
{
    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * ShortestSubStringCommand constructor.
     * @param ServiceInterface $service
     * @param null $name
     */
    public function __construct(ServiceInterface $service, $name = null)
    {
        parent::__construct($name);
        $this->service = $service;
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            $input = new ArgvInput();
        }

        if (null === $output) {
            $output = new ConsoleOutput();
        }

        parent::run($input, $output);
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('get-shortest-sub-sctring')
            // the short description shown while running "php bin/console list"
            ->setDescription('Values piped directly into the process using `cat input.txt | console get-shortest-sub-sctring `')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command determine the length of the shortest substring of s that contains all search characters.")
            ->addArgument(
                'characters',
                InputArgument::REQUIRED,
                'search characters'
            )
            ->addArgument(
                'stream',
                InputArgument::OPTIONAL,
                'string'
            )
            ->addOption(
                'debug',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will run in debug mode'
            );;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $characters = $input->getArgument('characters');
        $stream = $input->getArgument('stream');
        if (!$stream && 0 === ftell(STDIN)) {
            $stream = '';
            while (!feof(STDIN)) {
                $stream .= fread(STDIN, 1024);
            }
        }
        if(!$stream) {
            $output->writeln("Please pipe a file content to STDIN or add the string  as an argument");
            return;
        }


        $this->service->setStream($stream);
        $this->service->setSearchCharacters($characters);
        $this->service->getMinimumSubString();

        $output->writeln("The length of the shortest substring containing all search characters is: " . $this->service->getShortestSubStringLength());
        $output->writeln("The match would be here: " . $this->service->getHighlightedStream(false));
    }
}