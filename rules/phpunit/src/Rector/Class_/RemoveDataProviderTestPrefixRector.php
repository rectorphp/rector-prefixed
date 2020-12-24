<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PHPUnit\Rector\Class_;

use _PhpScopere8e811afab72\Nette\Utils\Strings;
use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Identifier;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\DataProviderTagValueNode;
use _PhpScopere8e811afab72\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://stackoverflow.com/a/46693675/1348344
 *
 * @see \Rector\PHPUnit\Tests\Rector\Class_\RemoveDataProviderTestPrefixRector\RemoveDataProviderTestPrefixRectorTest
 */
final class RemoveDataProviderTestPrefixRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractPHPUnitRector
{
    /**
     * @var string[]
     */
    private $providerMethodNamesToNewNames = [];
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Data provider methods cannot start with "test" prefix', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
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
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isInTestClass($node)) {
            return null;
        }
        $this->providerMethodNamesToNewNames = [];
        $this->renameDataProviderAnnotationsAndCollectRenamedMethods($node);
        $this->renameProviderMethods($node);
        return $node;
    }
    private function renameDataProviderAnnotationsAndCollectRenamedMethods(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_ $class) : void
    {
        foreach ($class->getMethods() as $classMethod) {
            /** @var PhpDocInfo|null $phpDocInfo */
            $phpDocInfo = $classMethod->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
            if ($phpDocInfo === null) {
                continue;
            }
            /** @var DataProviderTagValueNode[] $dataProviderTagValueNodes */
            $dataProviderTagValueNodes = $phpDocInfo->findAllByType(\_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\DataProviderTagValueNode::class);
            if ($dataProviderTagValueNodes === []) {
                continue;
            }
            foreach ($dataProviderTagValueNodes as $dataProviderTagValueNode) {
                $oldMethodName = $dataProviderTagValueNode->getMethod();
                if (!\_PhpScopere8e811afab72\Nette\Utils\Strings::startsWith($oldMethodName, 'test')) {
                    continue;
                }
                $newMethodName = $this->createNewMethodName($oldMethodName);
                $dataProviderTagValueNode->changeMethod($newMethodName);
                $oldMethodName = \trim($oldMethodName, '()');
                $newMethodName = \trim($newMethodName, '()');
                $this->providerMethodNamesToNewNames[$oldMethodName] = $newMethodName;
            }
        }
    }
    private function renameProviderMethods(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_ $class) : void
    {
        foreach ($class->getMethods() as $classMethod) {
            foreach ($this->providerMethodNamesToNewNames as $oldName => $newName) {
                if (!$this->isName($classMethod, $oldName)) {
                    continue;
                }
                $classMethod->name = new \_PhpScopere8e811afab72\PhpParser\Node\Identifier($newName);
            }
        }
    }
    private function createNewMethodName(string $oldMethodName) : string
    {
        $newMethodName = \_PhpScopere8e811afab72\Nette\Utils\Strings::substring($oldMethodName, \strlen('test'));
        return \lcfirst($newMethodName);
    }
}
