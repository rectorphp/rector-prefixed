<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command;

use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Command\Command;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Exception\InvalidArgumentException;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input\InputInterface;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input\InputOption;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Cloner\Data;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command\Descriptor\HtmlDescriptor;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Dumper\CliDumper;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Dumper\HtmlDumper;
use _PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Server\DumpServer;
/**
 * Starts a dump server to collect and output dumps on a single place with multiple formats support.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 *
 * @final
 */
class ServerDumpCommand extends \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'server:dump';
    private $server;
    /** @var DumpDescriptorInterface[] */
    private $descriptors;
    public function __construct(\_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Server\DumpServer $server, array $descriptors = [])
    {
        $this->server = $server;
        $this->descriptors = $descriptors + ['cli' => new \_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor(new \_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Dumper\CliDumper()), 'html' => new \_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Command\Descriptor\HtmlDescriptor(new \_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Dumper\HtmlDumper())];
        parent::__construct();
    }
    protected function configure()
    {
        $availableFormats = \implode(', ', \array_keys($this->descriptors));
        $this->addOption('format', null, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, \sprintf('The output format (%s)', $availableFormats), 'cli')->setDescription('Starts a dump server that collects and displays dumps in a single place')->setHelp(<<<'EOF'
<info>%command.name%</info> starts a dump server that collects and displays
dumps in a single place for debugging you application:

  <info>php %command.full_name%</info>

You can consult dumped data in HTML format in your browser by providing the <comment>--format=html</comment> option
and redirecting the output to a file:

  <info>php %command.full_name% --format="html" > dump.html</info>

EOF
);
    }
    protected function execute(\_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Input\InputInterface $input, \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $io = new \Symfony\Component\Console\Style\SymfonyStyle($input, $output);
        $format = $input->getOption('format');
        if (!($descriptor = $this->descriptors[$format] ?? null)) {
            throw new \_PhpScoperbd5d0c5f7638\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('Unsupported format "%s".', $format));
        }
        $errorIo = $io->getErrorStyle();
        $errorIo->title('Symfony Var Dumper Server');
        $this->server->start();
        $errorIo->success(\sprintf('Server listening on %s', $this->server->getHost()));
        $errorIo->comment('Quit the server with CONTROL-C.');
        $this->server->listen(function (\_PhpScoperbd5d0c5f7638\Symfony\Component\VarDumper\Cloner\Data $data, array $context, int $clientId) use($descriptor, $io) {
            $descriptor->describe($io, $data, $context, $clientId);
        });
    }
}