<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\FileSystemRector\Rector\FileNode;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\CustomNode\FileNode;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoperb75b35f52b74\Webmozart\Assert\Assert;
/**
 * @see \Rector\FileSystemRector\Tests\Rector\FileNode\RemoveProjectFileRector\RemoveProjectFileRectorTest
 */
final class RemoveProjectFileRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector implements \_PhpScoperb75b35f52b74\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const FILE_PATHS_TO_REMOVE = 'file_paths_to_remove';
    /**
     * @var string[]
     */
    private $filePathsToRemove = [];
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove file relative to project directory', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
// someFile/ToBeRemoved.txt
CODE_SAMPLE
, <<<'CODE_SAMPLE'
CODE_SAMPLE
, [self::FILE_PATHS_TO_REMOVE => ['someFile/ToBeRemoved.txt']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\CustomNode\FileNode::class];
    }
    /**
     * @param FileNode $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->filePathsToRemove === []) {
            return null;
        }
        $projectDirectory = \getcwd();
        $smartFileInfo = $node->getFileInfo();
        $relativePathInProject = $smartFileInfo->getRelativeFilePathFromDirectory($projectDirectory);
        foreach ($this->filePathsToRemove as $filePathsToRemove) {
            if (!$this->isFilePathToRemove($relativePathInProject, $filePathsToRemove)) {
                continue;
            }
            $this->removeFile($smartFileInfo);
        }
        return null;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        $filePathsToRemove = $configuration[self::FILE_PATHS_TO_REMOVE] ?? [];
        \_PhpScoperb75b35f52b74\Webmozart\Assert\Assert::allString($filePathsToRemove);
        $this->filePathsToRemove = $filePathsToRemove;
    }
    private function isFilePathToRemove(string $relativePathInProject, string $filePathToRemove) : bool
    {
        if (\_PhpScoperb75b35f52b74\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun() && \_PhpScoperb75b35f52b74\Nette\Utils\Strings::endsWith($relativePathInProject, $filePathToRemove)) {
            // only for tests
            return \true;
        }
        return $relativePathInProject === $filePathToRemove;
    }
}
