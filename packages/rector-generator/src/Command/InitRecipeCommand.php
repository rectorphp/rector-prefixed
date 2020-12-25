<?php

declare (strict_types=1);
namespace Rector\RectorGenerator\Command;

use Rector\RectorGenerator\TemplateInitializer;
use _PhpScoperfce0de0de1ce\Symfony\Component\Console\Command\Command;
use _PhpScoperfce0de0de1ce\Symfony\Component\Console\Input\InputInterface;
use _PhpScoperfce0de0de1ce\Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\ShellCode;
final class InitRecipeCommand extends \_PhpScoperfce0de0de1ce\Symfony\Component\Console\Command\Command
{
    /**
     * @var TemplateInitializer
     */
    private $templateInitializer;
    public function __construct(\Rector\RectorGenerator\TemplateInitializer $templateInitializer)
    {
        parent::__construct();
        $this->templateInitializer = $templateInitializer;
    }
    protected function configure() : void
    {
        $this->setDescription('[DEV] Initialize "rector-recipe.php" config');
        $this->setAliases(['recipe-init']);
    }
    protected function execute(\_PhpScoperfce0de0de1ce\Symfony\Component\Console\Input\InputInterface $input, \_PhpScoperfce0de0de1ce\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $this->templateInitializer->initialize(__DIR__ . '/../../../../templates/rector-recipe.php.dist', 'rector-recipe.php');
        return \Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
    }
}
