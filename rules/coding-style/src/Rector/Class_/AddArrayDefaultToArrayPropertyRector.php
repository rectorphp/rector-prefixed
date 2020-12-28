<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\CodingStyle\TypeAnalyzer\IterableTypeAnalyzer;
use Rector\Core\PhpParser\Node\Manipulator\PropertyFetchManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\CodingStyle\Tests\Rector\Class_\AddArrayDefaultToArrayPropertyRector\AddArrayDefaultToArrayPropertyRectorTest
 * @see https://3v4l.org/dPlUg
 */
final class AddArrayDefaultToArrayPropertyRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var PropertyFetchManipulator
     */
    private $propertyFetchManipulator;
    /**
     * @var IterableTypeAnalyzer
     */
    private $iterableTypeAnalyzer;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\PropertyFetchManipulator $propertyFetchManipulator, \Rector\CodingStyle\TypeAnalyzer\IterableTypeAnalyzer $iterableTypeAnalyzer)
    {
        $this->propertyFetchManipulator = $propertyFetchManipulator;
        $this->iterableTypeAnalyzer = $iterableTypeAnalyzer;
    }
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Adds array default value to property to prevent foreach over null error', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private $values;

    public function isEmpty()
    {
        return $this->values === null;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private $values = [];

    public function isEmpty()
    {
        return $this->values === [];
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
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $changedProperties = $this->collectPropertyNamesWithMissingDefaultArray($node);
        if ($changedProperties === []) {
            return null;
        }
        $this->completeDefaultArrayToPropertyNames($node, $changedProperties);
        // $this->variable !== null && count($this->variable) > 0 → count($this->variable) > 0
        $this->clearNotNullBeforeCount($node, $changedProperties);
        // $this->variable === null → $this->variable === []
        $this->replaceNullComparisonOfArrayPropertiesWithArrayComparison($node, $changedProperties);
        return $node;
    }
    /**
     * @return string[]
     */
    private function collectPropertyNamesWithMissingDefaultArray(\PhpParser\Node\Stmt\Class_ $class) : array
    {
        $propertyNames = [];
        $this->traverseNodesWithCallable($class, function (\PhpParser\Node $node) use(&$propertyNames) {
            if (!$node instanceof \PhpParser\Node\Stmt\PropertyProperty) {
                return null;
            }
            if ($node->default !== null) {
                return null;
            }
            $varType = $this->resolveVarType($node);
            if ($varType === null) {
                return null;
            }
            if (!$this->iterableTypeAnalyzer->detect($varType)) {
                return null;
            }
            $propertyNames[] = $this->getName($node);
            return null;
        });
        return $propertyNames;
    }
    /**
     * @param string[] $propertyNames
     */
    private function completeDefaultArrayToPropertyNames(\PhpParser\Node\Stmt\Class_ $class, array $propertyNames) : void
    {
        $this->traverseNodesWithCallable($class, function (\PhpParser\Node $class) use($propertyNames) : ?PropertyProperty {
            if (!$class instanceof \PhpParser\Node\Stmt\PropertyProperty) {
                return null;
            }
            if (!$this->isNames($class, $propertyNames)) {
                return null;
            }
            $class->default = new \PhpParser\Node\Expr\Array_();
            return $class;
        });
    }
    /**
     * @param string[] $propertyNames
     */
    private function clearNotNullBeforeCount(\PhpParser\Node\Stmt\Class_ $class, array $propertyNames) : void
    {
        $this->traverseNodesWithCallable($class, function (\PhpParser\Node $node) use($propertyNames) : ?Expr {
            if (!$node instanceof \PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
                return null;
            }
            if (!$this->isLocalPropertyOfNamesNotIdenticalToNull($node->left, $propertyNames)) {
                return null;
            }
            $isNextNodeCountingProperty = (bool) $this->betterNodeFinder->findFirst($node->right, function (\PhpParser\Node $node) use($propertyNames) : ?bool {
                if (!$node instanceof \PhpParser\Node\Expr\FuncCall) {
                    return null;
                }
                if (!$this->isName($node, 'count')) {
                    return null;
                }
                if (!isset($node->args[0])) {
                    return null;
                }
                $countedArgument = $node->args[0]->value;
                if (!$countedArgument instanceof \PhpParser\Node\Expr\PropertyFetch) {
                    return null;
                }
                return $this->isNames($countedArgument, $propertyNames);
            });
            if (!$isNextNodeCountingProperty) {
                return null;
            }
            return $node->right;
        });
    }
    /**
     * @param string[] $propertyNames
     */
    private function replaceNullComparisonOfArrayPropertiesWithArrayComparison(\PhpParser\Node\Stmt\Class_ $class, array $propertyNames) : void
    {
        // replace comparison to "null" with "[]"
        $this->traverseNodesWithCallable($class, function (\PhpParser\Node $node) use($propertyNames) : ?BinaryOp {
            if (!$node instanceof \PhpParser\Node\Expr\BinaryOp) {
                return null;
            }
            if ($this->propertyFetchManipulator->isLocalPropertyOfNames($node->left, $propertyNames) && $this->isNull($node->right)) {
                $node->right = new \PhpParser\Node\Expr\Array_();
            }
            if ($this->propertyFetchManipulator->isLocalPropertyOfNames($node->right, $propertyNames) && $this->isNull($node->left)) {
                $node->left = new \PhpParser\Node\Expr\Array_();
            }
            return $node;
        });
    }
    private function resolveVarType(\PhpParser\Node\Stmt\PropertyProperty $propertyProperty) : ?\PHPStan\Type\Type
    {
        /** @var Property $property */
        $property = $propertyProperty->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        // we need docblock
        $propertyPhpDocInfo = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if (!$propertyPhpDocInfo instanceof \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo) {
            return null;
        }
        return $propertyPhpDocInfo->getVarType();
    }
    /**
     * @param string[] $propertyNames
     */
    private function isLocalPropertyOfNamesNotIdenticalToNull(\PhpParser\Node\Expr $expr, array $propertyNames) : bool
    {
        if (!$expr instanceof \PhpParser\Node\Expr\BinaryOp\NotIdentical) {
            return \false;
        }
        if ($this->propertyFetchManipulator->isLocalPropertyOfNames($expr->left, $propertyNames) && $this->isNull($expr->right)) {
            return \true;
        }
        if (!$this->propertyFetchManipulator->isLocalPropertyOfNames($expr->right, $propertyNames)) {
            return \false;
        }
        return $this->isNull($expr->left);
    }
}
