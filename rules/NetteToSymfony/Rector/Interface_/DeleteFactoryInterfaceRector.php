<?php

declare (strict_types=1);
namespace Rector\NetteToSymfony\Rector\Interface_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use Rector\Core\Rector\AbstractRector;
use Rector\NetteToSymfony\NodeAnalyzer\NetteControlFactoryInterfaceAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\Tests\NetteToSymfony\Rector\Interface_\DeleteFactoryInterfaceRector\DeleteFactoryInterfaceFileSystemRectorTest
 */
final class DeleteFactoryInterfaceRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NetteControlFactoryInterfaceAnalyzer
     */
    private $netteControlFactoryInterfaceAnalyzer;
    /**
     * @param \Rector\NetteToSymfony\NodeAnalyzer\NetteControlFactoryInterfaceAnalyzer $netteControlFactoryInterfaceAnalyzer
     */
    public function __construct($netteControlFactoryInterfaceAnalyzer)
    {
        $this->netteControlFactoryInterfaceAnalyzer = $netteControlFactoryInterfaceAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Interface factories are not needed in Symfony. Clear constructor injection is used instead', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
interface SomeControlFactoryInterface
{
    public function create();
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Interface_::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $smartFileInfo = $node->getAttribute(\RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo::class);
        if ($smartFileInfo === null) {
            return null;
        }
        if (!$this->netteControlFactoryInterfaceAnalyzer->isComponentFactoryInterface($node)) {
            return null;
        }
        $this->removedAndAddedFilesCollector->removeFile($smartFileInfo);
        return null;
    }
}
