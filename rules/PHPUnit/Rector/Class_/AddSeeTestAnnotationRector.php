<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\Class_;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Rector\PHPUnit\TestClassResolver\TestClassResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector\AddSeeTestAnnotationRectorTest
 */
final class AddSeeTestAnnotationRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const SEE = 'see';
    /**
     * @var TestClassResolver
     */
    private $testClassResolver;
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @var PhpDocTagRemover
     */
    private $phpDocTagRemover;
    /**
     * @param \Rector\PHPUnit\TestClassResolver\TestClassResolver $testClassResolver
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     * @param \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover $phpDocTagRemover
     */
    public function __construct($testClassResolver, $reflectionProvider, $phpDocTagRemover)
    {
        $this->testClassResolver = $testClassResolver;
        $this->reflectionProvider = $reflectionProvider;
        $this->phpDocTagRemover = $phpDocTagRemover;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add @see annotation test of the class for faster jump to test. Make it FQN, so it stays in the annotation, not in the PHP source code.', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeService
{
}

use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
/**
 * @see \SomeServiceTest
 */
class SomeService
{
}

use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
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
        $testCaseClassName = $this->testClassResolver->resolveFromClass($node);
        if ($testCaseClassName === null) {
            return null;
        }
        if ($this->shouldSkipClass($node, $testCaseClassName)) {
            return null;
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if ($this->hasAlreadySeeAnnotation($phpDocInfo, $testCaseClassName)) {
            return null;
        }
        $this->removeNonExistingClassSeeAnnotation($phpDocInfo);
        $newSeeTagNode = $this->createSeePhpDocTagNode($testCaseClassName);
        $phpDocInfo->addPhpDocTagNode($newSeeTagNode);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\Class_ $class
     * @param string $testCaseClassName
     */
    private function shouldSkipClass($class, $testCaseClassName) : bool
    {
        // we are in the test case
        if ($this->isName($class, '*Test')) {
            return \true;
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($class);
        $seeTags = $phpDocInfo->getTagsByName(self::SEE);
        // is the @see annotation already added
        foreach ($seeTags as $seeTag) {
            if (!$seeTag->value instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode) {
                continue;
            }
            /** @var GenericTagValueNode $genericTagValueNode */
            $genericTagValueNode = $seeTag->value;
            $seeTagClass = \ltrim($genericTagValueNode->value, '\\');
            if ($seeTagClass === $testCaseClassName) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param string $className
     */
    private function createSeePhpDocTagNode($className) : \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode
    {
        return new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode('@see', new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode('\\' . $className));
    }
    /**
     * @param \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo
     * @param string $testCaseClassName
     */
    private function hasAlreadySeeAnnotation($phpDocInfo, $testCaseClassName) : bool
    {
        $seePhpDocTagNodes = $phpDocInfo->getTagsByName(self::SEE);
        foreach ($seePhpDocTagNodes as $seePhpDocTagNode) {
            if (!$seePhpDocTagNode->value instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode) {
                continue;
            }
            $possibleClassName = $seePhpDocTagNode->value->value;
            // annotation already exists
            if ($possibleClassName === '\\' . $testCaseClassName) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo
     */
    private function removeNonExistingClassSeeAnnotation($phpDocInfo) : void
    {
        $seePhpDocTagNodes = $phpDocInfo->getTagsByName(self::SEE);
        foreach ($seePhpDocTagNodes as $seePhpDocTagNode) {
            if (!$seePhpDocTagNode->value instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode) {
                continue;
            }
            $possibleClassName = $seePhpDocTagNode->value->value;
            if (!$this->isSeeTestCaseClass($possibleClassName)) {
                continue;
            }
            if ($this->reflectionProvider->hasClass($possibleClassName)) {
                continue;
            }
            // remove old annotation
            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $seePhpDocTagNode);
        }
    }
    /**
     * @param string $possibleClassName
     */
    private function isSeeTestCaseClass($possibleClassName) : bool
    {
        if (!\RectorPrefix20210317\Nette\Utils\Strings::startsWith($possibleClassName, '\\')) {
            return \false;
        }
        return \RectorPrefix20210317\Nette\Utils\Strings::endsWith($possibleClassName, 'Test');
    }
}
