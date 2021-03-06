<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\Class_;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\PHPUnit\PHPUnitDataProviderTagValueNode;
use Rector\Core\Rector\AbstractRector;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://stackoverflow.com/a/46693675/1348344
 *
 * @see \Rector\Tests\PHPUnit\Rector\Class_\RemoveDataProviderTestPrefixRector\RemoveDataProviderTestPrefixRectorTest
 */
final class RemoveDataProviderTestPrefixRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private $providerMethodNamesToNewNames = [];
    /**
     * @var TestsNodeAnalyzer
     */
    private $testsNodeAnalyzer;
    /**
     * @param \Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer $testsNodeAnalyzer
     */
    public function __construct($testsNodeAnalyzer)
    {
        $this->testsNodeAnalyzer = $testsNodeAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Data provider methods cannot start with "test" prefix', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider testProvideData()
     */
    public function test()
    {
        $nothing = 5;
    }

    public function testProvideData()
    {
        return ['123'];
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test()
    {
        $nothing = 5;
    }

    public function provideData()
    {
        return ['123'];
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }
        $this->providerMethodNamesToNewNames = [];
        $this->renameDataProviderAnnotationsAndCollectRenamedMethods($node);
        $this->renameProviderMethods($node);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function renameDataProviderAnnotationsAndCollectRenamedMethods($class) : void
    {
        foreach ($class->getMethods() as $classMethod) {
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
            /** @var PHPUnitDataProviderTagValueNode[] $phpunitDataProviderTagValueNodes */
            $phpunitDataProviderTagValueNodes = $phpDocInfo->findAllByType(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\PHPUnit\PHPUnitDataProviderTagValueNode::class);
            if ($phpunitDataProviderTagValueNodes === []) {
                continue;
            }
            foreach ($phpunitDataProviderTagValueNodes as $phpunitDataProviderTagValueNode) {
                $oldMethodName = $phpunitDataProviderTagValueNode->getMethodName();
                if (!\RectorPrefix20210317\Nette\Utils\Strings::startsWith($oldMethodName, 'test')) {
                    continue;
                }
                $newMethodName = $this->createNewMethodName($oldMethodName);
                $phpunitDataProviderTagValueNode->changeMethodName($newMethodName);
                $phpDocInfo->markAsChanged();
                $this->providerMethodNamesToNewNames[$oldMethodName] = $newMethodName;
            }
        }
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     */
    private function renameProviderMethods($class) : void
    {
        foreach ($class->getMethods() as $classMethod) {
            foreach ($this->providerMethodNamesToNewNames as $oldName => $newName) {
                if (!$this->isName($classMethod, $oldName)) {
                    continue;
                }
                $classMethod->name = new \PhpParser\Node\Identifier($newName);
            }
        }
    }
    /**
     * @param string $oldMethodName
     */
    private function createNewMethodName($oldMethodName) : string
    {
        $newMethodName = \RectorPrefix20210317\Nette\Utils\Strings::substring($oldMethodName, \strlen('test'));
        return \lcfirst($newMethodName);
    }
}
