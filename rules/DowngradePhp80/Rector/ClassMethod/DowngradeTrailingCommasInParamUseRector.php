<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\StaticInstanceOf;
use Rector\DowngradePhp73\Tokenizer\FollowedByCommaAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\DowngradePhp80\Rector\ClassMethod\DowngradeTrailingCommasInParamUseRector\DowngradeTrailingCommasInParamUseRectorTest
 */
final class DowngradeTrailingCommasInParamUseRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var FollowedByCommaAnalyzer
     */
    private $followedByCommaAnalyzer;
    /**
     * @param \Rector\DowngradePhp73\Tokenizer\FollowedByCommaAnalyzer $followedByCommaAnalyzer
     */
    public function __construct($followedByCommaAnalyzer)
    {
        $this->followedByCommaAnalyzer = $followedByCommaAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove trailing commas in param or use list', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(string $value1, string $value2,)
    {
        function (string $value1, string $value2,) {
        };

        function () use ($value1, $value2,) {
        };
    }
}

function inFunction(string $value1, string $value2,)
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(string $value1, string $value2)
    {
        function (string $value1, string $value2) {
        };

        function () use ($value1, $value2) {
        };
    }
}

function inFunction(string $value1, string $value2)
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
        return [\PhpParser\Node\Stmt\ClassMethod::class, \PhpParser\Node\Stmt\Function_::class, \PhpParser\Node\Expr\Closure::class, \PhpParser\Node\Expr\StaticCall::class, \PhpParser\Node\Expr\FuncCall::class, \PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\New_::class];
    }
    /**
     * @param ClassMethod|Function_|Closure|FuncCall|MethodCall|StaticCall|New_ $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (\Rector\Core\Util\StaticInstanceOf::isOneOf($node, [\PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\FuncCall::class, \PhpParser\Node\Expr\StaticCall::class, \PhpParser\Node\Expr\New_::class])) {
            /** @var MethodCall|FuncCall|StaticCall|New_ $node */
            return $this->processArgs($node);
        }
        if ($node instanceof \PhpParser\Node\Expr\Closure) {
            $node = $this->processUses($node);
        }
        /** @var ClassMethod|Function_ $node */
        return $this->processParams($node);
    }
    /**
     * @param FuncCall|MethodCall|StaticCall|New_ $node
     */
    private function processArgs($node) : ?\PhpParser\Node
    {
        if ($node->args === []) {
            return null;
        }
        return $this->cleanTrailingComma($node, $node->args);
    }
    /**
     * @param \PhpParser\Node\Expr\Closure $node
     */
    private function processUses($node) : \PhpParser\Node\Expr\Closure
    {
        if ($node->uses === []) {
            return $node;
        }
        return $this->cleanTrailingComma($node, $node->uses);
    }
    /**
     * @param ClassMethod|Function_|Closure $node
     */
    private function processParams($node) : ?\PhpParser\Node
    {
        if ($node->params === []) {
            return null;
        }
        return $this->cleanTrailingComma($node, $node->params);
    }
    /**
     * @param ClosureUse[]|Param[]|Arg[] $array
     * @param \PhpParser\Node $node
     */
    private function cleanTrailingComma($node, $array) : \PhpParser\Node
    {
        $lastPosition = \array_key_last($array);
        $last = $array[$lastPosition];
        if (!$this->followedByCommaAnalyzer->isFollowed($last)) {
            return $node;
        }
        $node->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        $last->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FUNC_ARGS_TRAILING_COMMA, \false);
        return $node;
    }
}
