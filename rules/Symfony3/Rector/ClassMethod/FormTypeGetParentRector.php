<?php

declare (strict_types=1);
namespace Rector\Symfony3\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Symfony3\FormHelper\FormTypeStringToTypeProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\Symfony3\Rector\ClassMethod\FormTypeGetParentRector\FormTypeGetParentRectorTest
 */
final class FormTypeGetParentRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var FormTypeStringToTypeProvider
     */
    private $formTypeStringToTypeProvider;
    /**
     * @param \Rector\Symfony3\FormHelper\FormTypeStringToTypeProvider $formTypeStringToTypeProvider
     */
    public function __construct($formTypeStringToTypeProvider)
    {
        $this->formTypeStringToTypeProvider = $formTypeStringToTypeProvider;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns string Form Type references to their CONSTANT alternatives in `getParent()` and `getExtendedType()` methods in Form in Symfony', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractType;

class SomeType extends AbstractType
{
    public function getParent()
    {
        return 'collection';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractType;

class SomeType extends AbstractType
{
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\CollectionType::class;
    }
}
CODE_SAMPLE
), new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractTypeExtension;

class SomeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return 'collection';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractTypeExtension;

class SomeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return \Symfony\Component\Form\Extension\Core\Type\CollectionType::class;
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
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->isClassAndMethodMatch($node)) {
            return null;
        }
        $this->traverseNodesWithCallable((array) $node->stmts, function (\PhpParser\Node $node) : ?Node {
            if (!$node instanceof \PhpParser\Node\Stmt\Return_) {
                return null;
            }
            if ($node->expr === null) {
                return null;
            }
            if (!$node->expr instanceof \PhpParser\Node\Scalar\String_) {
                return null;
            }
            $this->replaceStringWIthFormTypeClassConstIfFound($node->expr->value, $node);
            return $node;
        });
        return null;
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     */
    private function isClassAndMethodMatch($classMethod) : bool
    {
        $classLike = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return \false;
        }
        if ($this->isObjectType($classLike, new \PHPStan\Type\ObjectType('Symfony\\Component\\Form\\AbstractType'))) {
            return $this->isName($classMethod->name, 'getParent');
        }
        if ($this->isObjectType($classMethod, new \PHPStan\Type\ObjectType('Symfony\\Component\\Form\\AbstractTypeExtension'))) {
            return $this->isName($classMethod->name, 'getExtendedType');
        }
        return \false;
    }
    /**
     * @param string $stringValue
     * @param \PhpParser\Node\Stmt\Return_ $return
     */
    private function replaceStringWIthFormTypeClassConstIfFound($stringValue, $return) : void
    {
        $formClass = $this->formTypeStringToTypeProvider->matchClassForNameWithPrefix($stringValue);
        if ($formClass === null) {
            return;
        }
        $return->expr = $this->nodeFactory->createClassConstReference($formClass);
    }
}
