<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodingStyle\Tests\Rector\MethodCall\UseMessageVariableForSprintfInSymfonyStyleRector\UseMessageVariableForSprintfInSymfonyStyleRectorTest
 */
final class UseMessageVariableForSprintfInSymfonyStyleRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Decouple $message property from sprintf() calls in $this->symfonyStyle->method()', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Console\Style\SymfonyStyle;

final class SomeClass
{
    public function run(SymfonyStyle $symfonyStyle)
    {
        $symfonyStyle->info(sprintf('Hi %s', 'Tom'));
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Console\Style\SymfonyStyle;

final class SomeClass
{
    public function run(SymfonyStyle $symfonyStyle)
    {
        $message = sprintf('Hi %s', 'Tom');
        $symfonyStyle->info($message);
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isObjectType($node, 'Symfony\\Component\\Console\\Style\\SymfonyStyle')) {
            return null;
        }
        if (!isset($node->args[0])) {
            return null;
        }
        $argValue = $node->args[0]->value;
        if (!$this->isFuncCallName($argValue, 'sprintf')) {
            return null;
        }
        $messageVariable = new \PhpParser\Node\Expr\Variable('message');
        $assign = new \PhpParser\Node\Expr\Assign($messageVariable, $argValue);
        $this->addNodeBeforeNode($assign, $node);
        $node->args[0]->value = $messageVariable;
        return $node;
    }
}
