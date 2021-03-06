<?php

declare (strict_types=1);
namespace Rector\Symfony4\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Core\PhpParser\NodeTransformer;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20210317\Symfony\Component\Console\Input\StringInput;
use RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/symfony/symfony/pull/27821/files
 * @see \Rector\Tests\Symfony4\Rector\New_\StringToArrayArgumentProcessRector\StringToArrayArgumentProcessRectorTest
 */
final class StringToArrayArgumentProcessRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NodeTransformer
     */
    private $nodeTransformer;
    /**
     * @param \Rector\Core\PhpParser\NodeTransformer $nodeTransformer
     */
    public function __construct($nodeTransformer)
    {
        $this->nodeTransformer = $nodeTransformer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes Process string argument to an array', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Process\Process;
$process = new Process('ls -l');
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Process\Process;
$process = new Process(['ls', '-l']);
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\New_::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param New_|MethodCall $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $expr = $node instanceof \PhpParser\Node\Expr\New_ ? $node->class : $node->var;
        if ($this->isObjectType($expr, new \PHPStan\Type\ObjectType('Symfony\\Component\\Process\\Process'))) {
            return $this->processArgumentPosition($node, 0);
        }
        if ($this->isObjectType($expr, new \PHPStan\Type\ObjectType('Symfony\\Component\\Console\\Helper\\ProcessHelper'))) {
            return $this->processArgumentPosition($node, 1);
        }
        return null;
    }
    /**
     * @param New_|MethodCall $node
     * @param int $argumentPosition
     */
    private function processArgumentPosition($node, $argumentPosition) : ?\PhpParser\Node
    {
        if (!isset($node->args[$argumentPosition])) {
            return null;
        }
        $firstArgument = $node->args[$argumentPosition]->value;
        if ($firstArgument instanceof \PhpParser\Node\Expr\Array_) {
            return null;
        }
        // type analyzer
        if ($this->nodeTypeResolver->isStaticType($firstArgument, \PHPStan\Type\StringType::class)) {
            $this->processStringType($node, $argumentPosition, $firstArgument);
        }
        return $node;
    }
    /**
     * @param New_|MethodCall $expr
     * @param int $argumentPosition
     * @param \PhpParser\Node\Expr $firstArgumentExpr
     */
    private function processStringType($expr, $argumentPosition, $firstArgumentExpr) : void
    {
        if ($firstArgumentExpr instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
            $arrayNode = $this->nodeTransformer->transformConcatToStringArray($firstArgumentExpr);
            if ($arrayNode !== null) {
                $expr->args[$argumentPosition] = new \PhpParser\Node\Arg($arrayNode);
            }
            return;
        }
        if ($firstArgumentExpr instanceof \PhpParser\Node\Expr\FuncCall && $this->isName($firstArgumentExpr, 'sprintf')) {
            $arrayNode = $this->nodeTransformer->transformSprintfToArray($firstArgumentExpr);
            if ($arrayNode !== null) {
                $expr->args[$argumentPosition]->value = $arrayNode;
            }
        } elseif ($firstArgumentExpr instanceof \PhpParser\Node\Scalar\String_) {
            $parts = $this->splitProcessCommandToItems($firstArgumentExpr->value);
            $expr->args[$argumentPosition]->value = $this->nodeFactory->createArray($parts);
        }
        $this->processPreviousAssign($expr, $firstArgumentExpr);
    }
    /**
     * @return string[]
     * @param string $process
     */
    private function splitProcessCommandToItems($process) : array
    {
        $privatesCaller = new \RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesCaller();
        return $privatesCaller->callPrivateMethod(new \RectorPrefix20210317\Symfony\Component\Console\Input\StringInput(''), 'tokenize', [$process]);
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr $firstArgumentExpr
     */
    private function processPreviousAssign($node, $firstArgumentExpr) : void
    {
        $previousNodeAssign = $this->findPreviousNodeAssign($node, $firstArgumentExpr);
        if (!$previousNodeAssign instanceof \PhpParser\Node\Expr\Assign) {
            return;
        }
        if (!$this->nodeNameResolver->isFuncCallName($previousNodeAssign->expr, 'sprintf')) {
            return;
        }
        /** @var FuncCall $funcCall */
        $funcCall = $previousNodeAssign->expr;
        $arrayNode = $this->nodeTransformer->transformSprintfToArray($funcCall);
        if ($arrayNode !== null) {
            $previousNodeAssign->expr = $arrayNode;
        }
    }
    /**
     * @param \PhpParser\Node $node
     * @param \PhpParser\Node\Expr $firstArgumentExpr
     */
    private function findPreviousNodeAssign($node, $firstArgumentExpr) : ?\PhpParser\Node\Expr\Assign
    {
        /** @var Assign|null $assign */
        $assign = $this->betterNodeFinder->findFirstPrevious($node, function (\PhpParser\Node $checkedNode) use($firstArgumentExpr) : ?Assign {
            if (!$checkedNode instanceof \PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$this->nodeComparator->areNodesEqual($checkedNode->var, $firstArgumentExpr)) {
                return null;
            }
            return $checkedNode;
        });
        return $assign;
    }
}
