<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Nette\Rector\ClassMethod;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Assign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Coalesce;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Param;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\LNumber;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PhpParser\NodeTraverser;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/nette/utils/pull/178
 * @see https://github.com/contributte/translation/commit/d374c4c05b57dff1e5b327bb9bf98c392769806c
 *
 * @see \Rector\Nette\Tests\Rector\ClassMethod\TranslateClassMethodToVariadicsRector\TranslateClassMethodToVariadicsRectorTest
 * @note must be run before "composer update nette/utils:^3.0", because param contract break causes fatal error
 */
final class TranslateClassMethodToVariadicsRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const PARAMETERS = 'parameters';
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change translate() method call 2nd arg to variadic', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Nette\Localization\ITranslator;

final class SomeClass implements ITranslator
{
    public function translate($message, $count = null)
    {
        return [$message, $count];
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Nette\Localization\ITranslator;

final class SomeClass implements ITranslator
{
    public function translate($message, ... $parameters)
    {
        $count = $parameters[0] ?? null;
        return [$message, $count];
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, '_PhpScopere8e811afab72\\Nette\\Localization\\ITranslator')) {
            return null;
        }
        if (!$this->isName($node->name, 'translate')) {
            return null;
        }
        if (!isset($node->params[1])) {
            return null;
        }
        $secondParam = $node->params[1];
        if (!$secondParam->var instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable) {
            return null;
        }
        if ($secondParam->variadic) {
            return null;
        }
        $this->replaceSecondParamInClassMethodBody($node, $secondParam);
        $secondParam->default = null;
        $secondParam->variadic = \true;
        $secondParam->var->name = self::PARAMETERS;
        return $node;
    }
    private function replaceSecondParamInClassMethodBody(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScopere8e811afab72\PhpParser\Node\Param $param) : void
    {
        $paramName = $this->getName($param->var);
        $this->traverseNodesWithCallable((array) $classMethod->stmts, function (\_PhpScopere8e811afab72\PhpParser\Node $node) use($paramName) : ?int {
            if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable) {
                return null;
            }
            if (!$this->isName($node, $paramName)) {
                return null;
            }
            // instantiate
            $assign = $this->createCoalesceAssign($node);
            $currentStmt = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_STATEMENT);
            $positionNode = $currentStmt ?? $node;
            $this->addNodeBeforeNode($assign, $positionNode);
            return \_PhpScopere8e811afab72\PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
    }
    private function createCoalesceAssign(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable $variable) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign
    {
        $arrayDimFetch = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayDimFetch(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable(self::PARAMETERS), new \_PhpScopere8e811afab72\PhpParser\Node\Scalar\LNumber(0));
        $coalesce = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Coalesce($arrayDimFetch, $this->createNull());
        return new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign(new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable($variable->name), $coalesce);
    }
}
