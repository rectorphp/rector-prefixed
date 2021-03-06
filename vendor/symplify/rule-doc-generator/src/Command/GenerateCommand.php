<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\Command;

use RectorPrefix20210317\Symfony\Component\Console\Input\InputArgument;
use RectorPrefix20210317\Symfony\Component\Console\Input\InputInterface;
use RectorPrefix20210317\Symfony\Component\Console\Input\InputOption;
use RectorPrefix20210317\Symfony\Component\Console\Output\OutputInterface;
use RectorPrefix20210317\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use RectorPrefix20210317\Symplify\PackageBuilder\Console\ShellCode;
use Symplify\RuleDocGenerator\DirectoryToMarkdownPrinter;
use Symplify\RuleDocGenerator\ValueObject\Option;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
final class GenerateCommand extends \RectorPrefix20210317\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var DirectoryToMarkdownPrinter
     */
    private $directoryToMarkdownPrinter;
    /**
     * @param \Symplify\RuleDocGenerator\DirectoryToMarkdownPrinter $directoryToMarkdownPrinter
     */
    public function __construct($directoryToMarkdownPrinter)
    {
        parent::__construct();
        $this->directoryToMarkdownPrinter = $directoryToMarkdownPrinter;
    }
    protected function configure() : void
    {
        $this->setDescription('Generated Markdown documentation based on documented rules found in directory');
        $this->addArgument(\Symplify\RuleDocGenerator\ValueObject\Option::PATHS, \RectorPrefix20210317\Symfony\Component\Console\Input\InputArgument::REQUIRED | \RectorPrefix20210317\Symfony\Component\Console\Input\InputArgument::IS_ARRAY, 'Path to directory of your project');
        $this->addOption(\Symplify\RuleDocGenerator\ValueObject\Option::OUTPUT_FILE, null, \RectorPrefix20210317\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Path to output generated markdown file', \getcwd() . '/docs/rules_overview.md');
        $this->addOption(\Symplify\RuleDocGenerator\ValueObject\Option::CATEGORIZE, null, \RectorPrefix20210317\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Group in categories');
    }
    protected function execute(\RectorPrefix20210317\Symfony\Component\Console\Input\InputInterface $input, \RectorPrefix20210317\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $paths = (array) $input->getArgument(\Symplify\RuleDocGenerator\ValueObject\Option::PATHS);
        $shouldCategorize = (bool) $input->getOption(\Symplify\RuleDocGenerator\ValueObject\Option::CATEGORIZE);
        // dump markdown file
        $outputFilePath = (string) $input->getOption(\Symplify\RuleDocGenerator\ValueObject\Option::OUTPUT_FILE);
        $markdownFileDirectory = \dirname($outputFilePath);
        $markdownFileContent = $this->directoryToMarkdownPrinter->print($markdownFileDirectory, $paths, $shouldCategorize);
        $this->smartFileSystem->dumpFile($outputFilePath, $markdownFileContent);
        $outputFileInfo = new \RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo($outputFilePath);
        $message = \sprintf('File "%s" was created', $outputFileInfo->getRelativeFilePathFromCwd());
        $this->symfonyStyle->success($message);
        return \RectorPrefix20210317\Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
    }
}
