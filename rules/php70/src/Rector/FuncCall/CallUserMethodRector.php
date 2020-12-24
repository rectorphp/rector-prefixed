<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php70\Rector\FuncCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Name;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Php70\Tests\Rector\FuncCall\CallUserMethodRector\CallUserMethodRectorTest
 */
final class CallUserMethodRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const OLD_TO_NEW_FUNCTIONS = ['call_user_method' => 'call_user_func', 'call_user_method_array' => 'call_user_func_array'];
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes call_user_method()/call_user_method_array() to call_user_func()/call_user_func_array()', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('call_user_method($method, $obj, "arg1", "arg2");', 'call_user_func(array(&$obj, "method"), "arg1", "arg2");')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        $oldFunctionNames = \array_keys(self::OLD_TO_NEW_FUNCTIONS);
        if (!$this->isNames($node, $oldFunctionNames)) {
            return null;
        }
        $newName = self::OLD_TO_NEW_FUNCTIONS[$this->getName($node)];
        $node->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Name($newName);
        $oldArgs = $node->args;
        unset($node->args[1]);
        $newArgs = [$this->createArg([$oldArgs[1]->value, $oldArgs[0]->value])];
        unset($oldArgs[0]);
        unset($oldArgs[1]);
        $node->args = $this->appendArgs($newArgs, $oldArgs);
        return $node;
    }
}
