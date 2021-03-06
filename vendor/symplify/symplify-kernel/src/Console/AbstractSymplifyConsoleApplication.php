<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\SymplifyKernel\Console;

use RectorPrefix20210317\Symfony\Component\Console\Application;
use RectorPrefix20210317\Symfony\Component\Console\Command\Command;
use RectorPrefix20210317\Symplify\PackageBuilder\Console\Command\CommandNaming;
abstract class AbstractSymplifyConsoleApplication extends \RectorPrefix20210317\Symfony\Component\Console\Application
{
    /**
     * @var CommandNaming
     */
    private $commandNaming;
    /**
     * @param Command[] $commands
     * @param string $name
     * @param string $version
     */
    public function __construct($commands, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->commandNaming = new \RectorPrefix20210317\Symplify\PackageBuilder\Console\Command\CommandNaming();
        $this->addCommands($commands);
        parent::__construct($name, $version);
    }
    /**
     * Add names to all commands by class-name convention
     *
     * @param Command[] $commands
     */
    public function addCommands($commands) : void
    {
        foreach ($commands as $command) {
            $commandName = $this->commandNaming->resolveFromCommand($command);
            $command->setName($commandName);
        }
        parent::addCommands($commands);
    }
}
