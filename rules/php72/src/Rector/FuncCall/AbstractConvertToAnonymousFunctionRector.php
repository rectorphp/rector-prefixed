<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php72\Rector\FuncCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Closure;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ClosureUse;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Variable;
use _PhpScoperb75b35f52b74\PhpParser\Node\Param;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Rector\Php72\Contract\ConvertToAnonymousFunctionRectorInterface;
/**
 * @see https://www.php.net/functions.anonymous
 */
abstract class AbstractConvertToAnonymousFunctionRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector implements \_PhpScoperb75b35f52b74\Rector\Php72\Contract\ConvertToAnonymousFunctionRectorInterface
{
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $body = $this->getBody($node);
        $parameters = $this->getParameters($node);
        $useVariables = $this->resolveUseVariables($body, $parameters);
        $anonymousFunctionNode = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Closure();
        $anonymousFunctionNode->params = $parameters;
        foreach ($useVariables as $useVariable) {
            $anonymousFunctionNode->uses[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ClosureUse($useVariable);
        }
        $anonymousFunctionNode->returnType = $this->getReturnType($node);
        if ($body !== []) {
            $anonymousFunctionNode->stmts = $body;
        }
        return $anonymousFunctionNode;
    }
    /**
     * @param Node[] $nodes
     * @param Param[] $paramNodes
     * @return Variable[]
     */
    private function resolveUseVariables(array $nodes, array $paramNodes) : array
    {
        $paramNames = [];
        foreach ($paramNodes as $paramNode) {
            $paramNames[] = $this->getName($paramNode);
        }
        $variableNodes = $this->betterNodeFinder->findInstanceOf($nodes, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Variable::class);
        /** @var Variable[] $filteredVariables */
        $filteredVariables = [];
        $alreadyAssignedVariables = [];
        foreach ($variableNodes as $variableNode) {
            // "$this" is allowed
            if ($this->isName($variableNode, 'this')) {
                continue;
            }
            $variableName = $this->getName($variableNode);
            if ($variableName === null) {
                continue;
            }
            if (\in_array($variableName, $paramNames, \true)) {
                continue;
            }
            $parentNode = $variableNode->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parentNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Assign) {
                $alreadyAssignedVariables[] = $variableName;
            }
            if ($this->isNames($variableNode, $alreadyAssignedVariables)) {
                continue;
            }
            $filteredVariables[$variableName] = $variableNode;
        }
        return $filteredVariables;
    }
}
